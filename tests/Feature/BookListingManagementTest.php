<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookListingManagementTest extends TestCase
{
    use RefreshDatabase;

    private function category(): Category
    {
        return Category::firstOrCreate(['name' => 'Buku Kuliah']);
    }

    private function makeSeller(): User
    {
        return User::factory()->create(['role' => 'user']);
    }

    private function makeBook(User $seller, array $overrides = []): Book
    {
        return Book::create(array_merge([
            'user_id'     => $seller->id,
            'category_id' => $this->category()->id,
            'title'       => 'Algoritma Dasar',
            'author'      => 'Dosen Unsoed',
            'condition'   => 'GOOD',
            'price'       => 45000,
            'status'      => 'AVAILABLE',
        ], $overrides));
    }

    // 1. User login dapat membuat Book
    public function test_authenticated_user_can_create_book(): void
    {
        $seller = $this->makeSeller();

        $response = $this->actingAs($seller)->post(route('books.store'), [
            'category_id' => $this->category()->id,
            'title'       => 'Pemrograman Web',
            'author'      => 'Dosen IT',
            'condition'   => 'LIKE_NEW',
            'price'       => 60000,
        ]);

        $response->assertRedirect(route('books.my-listings'));
        $this->assertDatabaseHas('books', ['title' => 'Pemrograman Web', 'user_id' => $seller->id]);
    }

    // 2. Book otomatis memiliki seller/user_id authenticated user
    public function test_book_user_id_is_always_authenticated_user(): void
    {
        $seller = $this->makeSeller();

        $this->actingAs($seller)->post(route('books.store'), [
            'category_id' => $this->category()->id,
            'title'       => 'Fisika Lanjut',
            'author'      => 'Prof. Sains',
            'condition'   => 'GOOD',
            'price'       => 35000,
        ]);

        $this->assertDatabaseHas('books', [
            'title'   => 'Fisika Lanjut',
            'user_id' => $seller->id,
        ]);
    }

    // 3. User tidak dapat membuat Book atas nama user lain (user_id dari form diabaikan)
    public function test_user_cannot_forge_another_sellers_user_id(): void
    {
        $seller = $this->makeSeller();
        $other  = $this->makeSeller();

        // Meskipun ada 'user_id' di payload, controller mengabaikannya
        $this->actingAs($seller)->post(route('books.store'), [
            'user_id'     => $other->id, // dicoba di-forge
            'category_id' => $this->category()->id,
            'title'       => 'Buku Forged',
            'author'      => 'Hacker',
            'condition'   => 'FAIR',
            'price'       => 10000,
        ]);

        $book = Book::where('title', 'Buku Forged')->first();
        $this->assertNotNull($book);
        $this->assertEquals($seller->id, $book->user_id);
    }

    // 4. Seller dapat melihat listing miliknya
    public function test_seller_can_view_own_listings(): void
    {
        $seller = $this->makeSeller();
        $this->makeBook($seller);

        $response = $this->actingAs($seller)->get(route('books.my-listings'));
        $response->assertStatus(200);
        $response->assertSee('Algoritma Dasar');
    }

    // 5. Seller dapat mengedit listing miliknya
    public function test_seller_can_edit_own_book(): void
    {
        $seller = $this->makeSeller();
        $book   = $this->makeBook($seller);

        $response = $this->actingAs($seller)->put(route('books.update', $book), [
            'category_id' => $this->category()->id,
            'title'       => 'Judul Diperbarui',
            'author'      => 'Dosen Unsoed',
            'condition'   => 'FAIR',
            'price'       => 40000,
        ]);

        $response->assertRedirect(route('books.my-listings'));
        $this->assertEquals('Judul Diperbarui', $book->fresh()->title);
    }

    // 6. Seller tidak dapat mengedit listing user lain
    public function test_seller_cannot_edit_other_users_book(): void
    {
        $seller = $this->makeSeller();
        $other  = $this->makeSeller();
        $book   = $this->makeBook($other);

        $response = $this->actingAs($seller)->put(route('books.update', $book), [
            'category_id' => $this->category()->id,
            'title'       => 'Percobaan Ubah',
            'author'      => 'x',
            'condition'   => 'FAIR',
            'price'       => 1000,
        ]);

        $response->assertStatus(403);
    }

    // 7. Seller dapat menghapus listing yang belum memiliki transaksi
    public function test_seller_can_delete_available_book_with_no_orders(): void
    {
        $seller = $this->makeSeller();
        $book   = $this->makeBook($seller);

        $response = $this->actingAs($seller)->delete(route('books.destroy', $book));

        $response->assertRedirect(route('books.my-listings'));
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }

    // 8. Book yang sudah SOLD tidak dapat dihapus
    public function test_sold_book_cannot_be_deleted(): void
    {
        $seller = $this->makeSeller();
        $book   = $this->makeBook($seller, ['status' => 'SOLD']);

        $response = $this->actingAs($seller)->delete(route('books.destroy', $book));
        $response->assertStatus(403);

        $this->assertDatabaseHas('books', ['id' => $book->id]);
    }

    // 9. User lain dapat melihat Book yang AVAILABLE di public listing
    public function test_any_user_can_view_available_books(): void
    {
        $seller = $this->makeSeller();
        $this->makeBook($seller);

        $response = $this->get(route('books.index'));
        $response->assertStatus(200);
        $response->assertSee('Algoritma Dasar');
    }

    // 10. Book SOLD tidak ditampilkan di public marketplace
    public function test_sold_book_not_shown_in_marketplace(): void
    {
        $seller = $this->makeSeller();
        $this->makeBook($seller, ['title' => 'Buku Sudah Terjual', 'status' => 'SOLD']);

        $response = $this->get(route('books.index'));
        $response->assertStatus(200);
        $response->assertDontSee('Buku Sudah Terjual');
    }

    // 11. Validasi price bekerja
    public function test_negative_price_is_rejected(): void
    {
        $seller = $this->makeSeller();

        $response = $this->actingAs($seller)->post(route('books.store'), [
            'category_id' => $this->category()->id,
            'title'       => 'Buku Harga Negatif',
            'author'      => 'X',
            'condition'   => 'GOOD',
            'price'       => -5000,
        ]);

        $response->assertSessionHasErrors('price');
    }

    // 12. Validasi field wajib bekerja
    public function test_required_fields_are_validated(): void
    {
        $seller = $this->makeSeller();

        $response = $this->actingAs($seller)->post(route('books.store'), []);

        $response->assertSessionHasErrors(['title', 'author', 'category_id', 'condition', 'price']);
    }

    // 13. Category relationship bekerja
    public function test_book_category_relationship_works(): void
    {
        $seller = $this->makeSeller();
        $book   = $this->makeBook($seller);

        $this->assertInstanceOf(Category::class, $book->category);
        $this->assertEquals('Buku Kuliah', $book->category->name);
    }

    // 14. User → Books relationship bekerja
    public function test_user_books_relationship_works(): void
    {
        $seller = $this->makeSeller();
        $this->makeBook($seller);
        $this->makeBook($seller, ['title' => 'Buku Kedua']);

        $this->assertCount(2, $seller->books);
    }
}
