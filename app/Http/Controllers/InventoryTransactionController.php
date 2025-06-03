<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;

class InventoryTransactionController extends Controller
{
    public function index()
    {
        $transactions = InventoryTransaction::with(['product', 'user'])->latest()->paginate(20);
        return view('inventory_transactions.index', compact('transactions'));
    }
}
