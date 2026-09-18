<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    private function category(): Category
    {
        return Category::firstOrCreate(['name' => 'Buku Kuliah']);
    }

    private function makeUser(): User
    {
        return User::factory()->create(['role' => 'user']);
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

    private function placeOrder(User $buyer, array $bookIds): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($buyer)->post(route('orders.store'), [
            'book_ids' => $bookIds,
        ]);
    }

    // 1. Buyer dapat membuat Order dari Book AVAILABLE
    public function test_buyer_can_create_order_from_available_book(): void
    {
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller, ['price' => 20000]);

        $response = $this->placeOrder($buyer, [$book->id]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['buyer_id' => $buyer->id, 'seller_id' => $seller->id]);
        $this->assertDatabaseHas('order_items', ['book_id' => $book->id, 'price' => 20000]);
    }

    // 2. Buyer tidak dapat membeli Book miliknya sendiri
    public function test_buyer_cannot_order_own_book(): void
    {
        $user = $this->makeUser();
        $book = $this->makeBook($user);

        $response = $this->placeOrder($user, [$book->id]);

        $response->assertSessionHasErrors('order');
        $this->assertDatabaseCount('orders', 0);
    }

    // 3. Book RESERVED tidak dapat dipesan lagi
    public function test_reserved_book_cannot_be_ordered(): void
    {
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller, ['status' => 'RESERVED']);

        $response = $this->placeOrder($buyer, [$book->id]);

        $response->assertSessionHasErrors('order');
        $this->assertDatabaseCount('orders', 0);
    }

    // 4. Book SOLD tidak dapat dipesan
    public function test_sold_book_cannot_be_ordered(): void
    {
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller, ['status' => 'SOLD']);

        $response = $this->placeOrder($buyer, [$book->id]);

        $response->assertSessionHasErrors('order');
        $this->assertDatabaseCount('orders', 0);
    }

    // 5. Order hanya boleh memiliki satu seller
    public function test_order_cannot_contain_books_from_multiple_sellers(): void
    {
        $buyer   = $this->makeUser();
        $seller1 = $this->makeUser();
        $seller2 = $this->makeUser();
        $book1   = $this->makeBook($seller1);
        $book2   = $this->makeBook($seller2);

        $response = $this->placeOrder($buyer, [$book1->id, $book2->id]);

        $response->assertSessionHasErrors('order');
        $this->assertDatabaseCount('orders', 0);
    }

    // 6. OrderItem menyimpan harga transaksi (snapshot)
    public function test_order_item_stores_price_at_time_of_transaction(): void
    {
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller, ['price' => 45000]);

        $this->placeOrder($buyer, [$book->id]);

        $item = OrderItem::first();
        $this->assertEquals('45000.00', $item->price);

        // Ubah harga book setelah transaksi
        $book->update(['price' => 99999]);

        // Harga di OrderItem tidak berubah
        $this->assertEquals('45000.00', $item->fresh()->price);
    }

    // 7. Subtotal dihitung dengan benar
    public function test_subtotal_is_correctly_calculated(): void
    {
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book1  = $this->makeBook($seller, ['price' => 20000]);
        $book2  = $this->makeBook($seller, ['price' => 15000]);

        $this->placeOrder($buyer, [$book1->id, $book2->id]);

        $order = Order::first();
        $this->assertEquals('35000.00', $order->subtotal);
    }

    // 8. Service fee dihitung dengan benar (Rp1.000)
    public function test_service_fee_is_correctly_applied(): void
    {
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller, ['price' => 20000]);

        $this->placeOrder($buyer, [$book->id]);

        $order = Order::first();
        $this->assertEquals(Order::SERVICE_FEE, (int) $order->service_fee);
    }

    // 9. Total amount = subtotal + service_fee
    public function test_total_amount_equals_subtotal_plus_service_fee(): void
    {
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller, ['price' => 20000]);

        $this->placeOrder($buyer, [$book->id]);

        $order = Order::first();
        $expected = (float) $order->subtotal + (float) $order->service_fee;
        $this->assertEquals(number_format($expected, 2, '.', ''), $order->total_amount);
    }

    // 10. Book berubah menjadi RESERVED setelah Order dibuat
    public function test_book_becomes_reserved_after_order(): void
    {
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller);

        $this->placeOrder($buyer, [$book->id]);

        $this->assertEquals('RESERVED', $book->fresh()->status);
    }

    // 11. Buyer tidak dapat melihat Order buyer lain
    public function test_buyer_cannot_view_other_buyers_order(): void
    {
        $buyer1 = $this->makeUser();
        $buyer2 = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller);

        $this->placeOrder($buyer1, [$book->id]);
        $order = Order::first();

        $response = $this->actingAs($buyer2)->get(route('orders.show', $order));
        $response->assertStatus(403);
    }

    // 12. Seller tidak dapat melihat Order seller lain
    public function test_seller_cannot_view_other_sellers_order(): void
    {
        $buyer   = $this->makeUser();
        $seller1 = $this->makeUser();
        $seller2 = $this->makeUser();
        $book    = $this->makeBook($seller1);

        $this->placeOrder($buyer, [$book->id]);
        $order = Order::first();

        $response = $this->actingAs($seller2)->get(route('seller.orders.show', $order));
        $response->assertStatus(403);
    }

    // 13. Order PENDING dapat dibatalkan oleh buyer
    public function test_buyer_can_cancel_pending_order(): void
    {
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller);

        $this->placeOrder($buyer, [$book->id]);
        $order = Order::first();

        $response = $this->actingAs($buyer)->post(route('orders.cancel', $order));

        $response->assertRedirect(route('orders.index'));
        $this->assertEquals('CANCELLED', $order->fresh()->status);
    }

    // 14. Saat Order dibatalkan, Book kembali AVAILABLE
    public function test_book_returns_to_available_when_order_cancelled(): void
    {
        $buyer  = $this->makeUser();
        $seller = $this->makeUser();
        $book   = $this->makeBook($seller);

        $this->placeOrder($buyer, [$book->id]);
        $order = Order::first();

        $this->actingAs($buyer)->post(route('orders.cancel', $order));

        $this->assertEquals('AVAILABLE', $book->fresh()->status);
    }

    // 15. Error dalam transaksi tidak meninggalkan Order setengah jadi
    public function test_failed_validation_leaves_no_partial_order(): void
    {
        $user = $this->makeUser();
        // Pesan buku milik sendiri → harus gagal
        $book = $this->makeBook($user);

        $this->placeOrder($user, [$book->id]);

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        // Book tidak berubah menjadi RESERVED
        $this->assertEquals('AVAILABLE', $book->fresh()->status);
    }
}
