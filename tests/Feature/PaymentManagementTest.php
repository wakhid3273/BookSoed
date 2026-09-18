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

class PaymentManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $buyer;
    private User $seller;
    private User $otherUser;
    private Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->buyer = User::factory()->create(['name' => 'Buyer User']);
        $this->seller = User::factory()->create(['name' => 'Seller User']);
        $this->otherUser = User::factory()->create(['name' => 'Other User']);

        $category = Category::create(['name' => 'Teknik', 'slug' => 'teknik']);

        $book = Book::create([
            'user_id' => $this->seller->id,
            'category_id' => $category->id,
            'title' => 'Kalkulus Dasar',
            'author' => 'Author Test',
            'price' => 50000,
            'condition' => 'GOOD',
            'status' => 'RESERVED',
        ]);

        $this->order = Order::create([
            'buyer_id' => $this->buyer->id,
            'seller_id' => $this->seller->id,
            'subtotal' => 50000,
            'service_fee' => Order::SERVICE_FEE,
            'total_amount' => 51000,
            'status' => Order::STATUS_PENDING,
            'order_date' => now(),
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'book_id' => $book->id,
            'price' => 50000,
        ]);
    }

    public function test_buyer_can_view_payment_form(): void
    {
        $response = $this->actingAs($this->buyer)->get(route('payments.create', $this->order));

        $response->assertStatus(200);
        $response->assertSee('Pilih Metode Pembayaran');
        $response->assertSee('51.000');
    }

    public function test_non_buyer_cannot_view_payment_form(): void
    {
        $response = $this->actingAs($this->seller)->get(route('payments.create', $this->order));

        $response->assertStatus(403);
    }

    public function test_buyer_can_create_ewallet_payment(): void
    {
        $response = $this->actingAs($this->buyer)->post(route('payments.store', $this->order), [
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => 'GoPay',
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => 'GoPay',
            'amount' => 51000,
            'payment_status' => Payment::STATUS_PENDING,
        ]);

        $payment = Payment::where('order_id', $this->order->id)->first();
        $response->assertRedirect(route('payments.show', $payment));
    }

    public function test_buyer_can_create_cod_payment(): void
    {
        $response = $this->actingAs($this->buyer)->post(route('payments.store', $this->order), [
            'payment_method' => Payment::METHOD_COD,
        ]);

        $this->assertDatabaseHas('payments', [
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_COD,
            'ewallet_provider' => null,
            'amount' => 51000,
            'payment_status' => Payment::STATUS_PENDING,
        ]);

        $payment = Payment::where('order_id', $this->order->id)->first();
        $response->assertRedirect(route('payments.show', $payment));
    }

    public function test_ewallet_requires_provider(): void
    {
        $response = $this->actingAs($this->buyer)->post(route('payments.store', $this->order), [
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => '',
        ]);

        $response->assertSessionHasErrors('ewallet_provider');
    }

    public function test_payment_amount_is_always_taken_from_order(): void
    {
        // Request does not even have amount field, total_amount comes from order
        $this->actingAs($this->buyer)->post(route('payments.store', $this->order), [
            'payment_method' => Payment::METHOD_COD,
            'amount' => 100, // Should be ignored
        ]);

        $payment = Payment::where('order_id', $this->order->id)->first();
        $this->assertEquals(51000, $payment->amount);
    }

    public function test_cannot_create_duplicate_payment_for_same_order(): void
    {
        // First payment
        Payment::create([
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_COD,
            'amount' => 51000,
            'payment_status' => Payment::STATUS_PENDING,
        ]);

        // Attempt second payment creation
        $response = $this->actingAs($this->buyer)->get(route('payments.create', $this->order));
        $response->assertStatus(403);

        $response2 = $this->actingAs($this->buyer)->post(route('payments.store', $this->order), [
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => 'GoPay',
        ]);
        $response2->assertStatus(403);
    }

    public function test_buyer_can_view_payment_detail(): void
    {
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => 'DANA',
            'amount' => 51000,
            'payment_status' => Payment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->buyer)->get(route('payments.show', $payment));

        $response->assertStatus(200);
        $response->assertSee('Detail Pembayaran');
        $response->assertSee('E-WALLET');
        $response->assertSee('DANA');
    }

    public function test_seller_can_view_payment_detail(): void
    {
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => 'OVO',
            'amount' => 51000,
            'payment_status' => Payment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->seller)->get(route('payments.show', $payment));

        $response->assertStatus(200);
    }

    public function test_unauthorized_user_cannot_view_payment(): void
    {
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => 'OVO',
            'amount' => 51000,
            'payment_status' => Payment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->otherUser)->get(route('payments.show', $payment));

        $response->assertStatus(403);
    }

    public function test_simulating_ewallet_payment_success(): void
    {
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => 'ShopeePay',
            'amount' => 51000,
            'payment_status' => Payment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->buyer)->post(route('payments.simulate-success', $payment));

        $response->assertRedirect(route('payments.show', $payment));

        $payment->refresh();
        $this->assertEquals(Payment::STATUS_PAID, $payment->payment_status);
        $this->assertNotNull($payment->transaction_reference);
        $this->assertStringStartsWith('BOOKSOED-', $payment->transaction_reference);
        $this->assertNotNull($payment->paid_at);

        // Order status should be updated to PROCESSING
        $this->order->refresh();
        $this->assertEquals(Order::STATUS_PROCESSING, $this->order->status);
    }

    public function test_simulating_ewallet_payment_failure(): void
    {
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => 'ShopeePay',
            'amount' => 51000,
            'payment_status' => Payment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->buyer)->post(route('payments.simulate-fail', $payment));

        $response->assertRedirect(route('payments.show', $payment));

        $payment->refresh();
        $this->assertEquals(Payment::STATUS_FAILED, $payment->payment_status);
        $this->assertNull($payment->transaction_reference);

        // Order status should remain PENDING
        $this->order->refresh();
        $this->assertEquals(Order::STATUS_PENDING, $this->order->status);
    }

    public function test_cannot_simulate_non_pending_payment(): void
    {
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => 'ShopeePay',
            'amount' => 51000,
            'payment_status' => Payment::STATUS_PAID,
            'transaction_reference' => 'BOOKSOED-12345',
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($this->buyer)->post(route('payments.simulate-success', $payment));

        $response->assertStatus(403);
    }

    public function test_cannot_simulate_cod_payment(): void
    {
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_COD,
            'amount' => 51000,
            'payment_status' => Payment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->buyer)->post(route('payments.simulate-success', $payment));

        $response->assertStatus(403);
    }

    public function test_seller_cannot_simulate_payment(): void
    {
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => 'GoPay',
            'amount' => 51000,
            'payment_status' => Payment::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->seller)->post(route('payments.simulate-success', $payment));

        $response->assertStatus(403);
    }

    public function test_buyer_can_retry_payment_after_failure(): void
    {
        // First payment failed
        $payment = Payment::create([
            'order_id' => $this->order->id,
            'payment_method' => Payment::METHOD_E_WALLET,
            'ewallet_provider' => 'GoPay',
            'amount' => 51000,
            'payment_status' => Payment::STATUS_FAILED,
        ]);

        // Can view create form again
        $response = $this->actingAs($this->buyer)->get(route('payments.create', $this->order));
        $response->assertStatus(200);

        // Can submit payment again (which updates existing failed payment to PENDING)
        $responseStore = $this->actingAs($this->buyer)->post(route('payments.store', $this->order), [
            'payment_method' => Payment::METHOD_COD,
        ]);

        $responseStore->assertRedirect(route('payments.show', $payment));

        $payment->refresh();
        $this->assertEquals(Payment::METHOD_COD, $payment->payment_method);
        $this->assertEquals(Payment::STATUS_PENDING, $payment->payment_status);
        $this->assertNull($payment->ewallet_provider);
    }

    public function test_payment_for_cancelled_order_is_forbidden(): void
    {
        $this->order->update(['status' => Order::STATUS_CANCELLED]);

        $response = $this->actingAs($this->buyer)->get(route('payments.create', $this->order));
        $response->assertStatus(403);

        $responseStore = $this->actingAs($this->buyer)->post(route('payments.store', $this->order), [
            'payment_method' => Payment::METHOD_COD,
        ]);
        $responseStore->assertStatus(403);
    }
}
