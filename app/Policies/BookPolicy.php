<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * Admin dapat melakukan semua tindakan.
     */
    public function before(User $user): ?bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * Any authenticated user can view the book list.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Any authenticated user can view a single book.
     */
    public function view(User $user, Book $book): bool
    {
        return true;
    }

    /**
     * Any authenticated user can create a book listing.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the seller (owner) can update their own listing.
     * Editing is blocked if the book is SOLD.
     * Editing price is blocked if the book is RESERVED.
     */
    public function update(User $user, Book $book): bool
    {
        return $user->id === $book->user_id && $book->status !== 'SOLD';
    }

    /**
     * Only the seller (owner) can delete their listing.
     * Deletion is blocked if the book has been part of any order (has order items).
     * Deletion is blocked if status is RESERVED or SOLD.
     */
    public function delete(User $user, Book $book): bool
    {
        if ($user->id !== $book->user_id) {
            return false;
        }

        if (in_array($book->status, ['RESERVED', 'SOLD'])) {
            return false;
        }

        // Juga blok hapus jika sudah pernah masuk ke order_items
        if ($book->orderItems()->exists()) {
            return false;
        }

        return true;
    }
}
