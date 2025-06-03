<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Book;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Admin bisa lihat semua transaksi
    public function index()
    {
        $transactions = Transaction::with(['user', 'book'])->get();

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    // Customer membuat transaksi baru
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::find($request->book_id);
        if (!$book) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $orderNumber = 'ORD-' . time() . '-' . rand(1000, 9999);

        $transaction = Transaction::create([
            'order_number' => $orderNumber,
            'customer_id' => $request->user()->id,
            'book_id' => $book->id,
            'total_amount' => $book->price,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat',
            'data' => $transaction,
        ], 201);
    }

    // Detail transaksi, customer hanya bisa lihat transaksi miliknya
    public function show($id, Request $request)
    {
        $transaction = Transaction::with(['user', 'book'])->find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($request->user()->role === 'customer' && $transaction->customer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['success' => true, 'data' => $transaction]);
    }

    // Update transaksi, customer hanya transaksi miliknya
    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($request->user()->role === 'customer' && $transaction->customer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::find($request->book_id);

        $transaction->update([
            'book_id' => $book->id,
            'total_amount' => $book->price,
        ]);

        return response()->json(['success' => true, 'message' => 'Transaksi diperbarui', 'data' => $transaction]);
    }

    // Hapus transaksi (admin)
    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transaction->delete();

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus']);
    }
}
