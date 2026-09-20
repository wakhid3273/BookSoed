<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminErpTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function makeUser(): User
    {
        return User::factory()->create(['role' => 'user']);
    }

    private function category(): Category
    {
        return Category::firstOrCreate(['name' => 'Test Kategori']);
    }

    private function makeBook(User $seller, array $overrides = []): Book
    {
        return Book::create(array_merge([
            'user_id'     => $seller->id,
            'category_id' => $this->category()->id,
            'title'       => 'Buku Test ' . rand(1, 9999),
            'author'      => 'Penulis',
            'condition'   => 'GOOD',
            'price'       => 20000,
            'status'      => 'AVAILABLE',
        ], $overrides));
    }

    private function makeOrder(User $buyer, User $seller, Book $book): Order
    {
        $order = Order::create([
            'buyer_id'     => $buyer->id,
            'seller_id'    => $seller->id,
            'order_date'   => now(),
            'status'       => 'PENDING',
            'subtotal'     => $book->price,
            'service_fee'  => 1000,
            'total_amount' => $book->price + 1000,
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'book_id'  => $book->id,
            'price'    => $book->price,
            'quantity' => 1,
        ]);
        return $order;
    }

    // 1. Admin dapat membuka dashboard
    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard Admin ERP');
    }

    // 2. User biasa tidak dapat membuka dashboard
    public function test_regular_user_cannot_access_dashboard(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    // 3. Unauthenticated user tidak dapat akses admin
    public function test_unauthenticated_cannot_access_admin(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    // 4. Admin dapat melihat users
    public function test_admin_can_view_users_list(): void
    {
        $admin  = $this->makeAdmin();
        $user   = $this->makeUser();

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee($user->name);
    }

    // 5. User biasa tidak dapat melihat daftar user
    public function test_regular_user_cannot_view_users_list(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertStatus(403);
    }

    // 6. Admin dapat melihat detail user
    public function test_admin_can_view_user_detail(): void
    {
        $admin  = $this->makeAdmin();
        $target = $this->makeUser();

        $response = $this->actingAs($admin)->get(route('admin.users.show', $target));

        $response->assertOk();
        $response->assertSee($target->name);
        $response->assertSee($target->email);
    }

    // 7. Admin dapat melihat listings
    public function test_admin_can_view_books_list(): void
    {
        $admin  = $this->makeAdmin();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller, ['title' => 'Buku Admin Test']);

        $response = $this->actingAs($admin)->get(route('admin.books.index'));

        $response->assertOk();
        $response->assertSee('Buku Admin Test');
    }

    // 8. User biasa tidak dapat melihat listing admin
    public function test_regular_user_cannot_view_admin_books(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get(route('admin.books.index'));

        $response->assertStatus(403);
    }

    // 9. Admin dapat melihat orders
    public function test_admin_can_view_orders_list(): void
    {
        $admin  = $this->makeAdmin();
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller);
        $this->makeOrder($buyer, $seller, $book);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));

        $response->assertOk();
        $response->assertSee($buyer->name);
    }

    // 10. User biasa tidak dapat melihat orders admin
    public function test_regular_user_cannot_view_admin_orders(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get(route('admin.orders.index'));

        $response->assertStatus(403);
    }

    // 11. Admin dapat melihat detail order
    public function test_admin_can_view_order_detail(): void
    {
        $admin  = $this->makeAdmin();
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller);
        $order  = $this->makeOrder($buyer, $seller, $book);

        $response = $this->actingAs($admin)->get(route('admin.orders.show', $order));

        $response->assertOk();
        $response->assertSee($buyer->name);
        $response->assertSee($seller->name);
    }

    // 12. Admin dapat melihat payments
    public function test_admin_can_view_payments_list(): void
    {
        $admin  = $this->makeAdmin();
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller);
        $order  = $this->makeOrder($buyer, $seller, $book);
        Payment::create([
            'order_id'       => $order->id,
            'payment_method' => 'COD',
            'amount'         => $order->total_amount,
            'payment_status' => 'PENDING',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.payments.index'));

        $response->assertOk();
        $response->assertSee('COD');
    }

    // 13. User biasa tidak dapat melihat payments admin
    public function test_regular_user_cannot_view_admin_payments(): void
    {
        $user = $this->makeUser();

        $response = $this->actingAs($user)->get(route('admin.payments.index'));

        $response->assertStatus(403);
    }

    // 14. Dashboard menampilkan data statistik aktual
    public function test_dashboard_shows_correct_statistics(): void
    {
        $admin  = $this->makeAdmin();
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller);
        $this->makeOrder($buyer, $seller, $book);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        // Should see at least 3 users (admin, buyer, seller)
        $response->assertSee('3');
    }

    // 15. Admin dapat mencari user berdasarkan nama
    public function test_admin_can_search_users_by_name(): void
    {
        $admin   = $this->makeAdmin();
        $target  = User::factory()->create(['name' => 'Budi Santoso', 'role' => 'user']);
        $other   = User::factory()->create(['name' => 'Anotheruser',  'role' => 'user']);

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'Budi']));

        $response->assertOk();
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Anotheruser');
    }
}
