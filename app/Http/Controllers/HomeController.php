<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $books = Book::whereIn('status', ['AVAILABLE', 'available'])
            ->with(['seller', 'category'])
            ->latest('created_at')
            ->paginate(12);

        return view('index', compact('books'));
    }
}
