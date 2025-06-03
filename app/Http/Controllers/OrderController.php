<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'orderItems'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('quantity', '>', 0)->orderBy('name')->get();

        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_id' => $request->customer_id,
                'status' => 'pending',
                'notes' => $request->notes,
                'total_amount' => 0
            ]);

            $totalAmount = 0;

            foreach ($request->products as $item) {
                $product = Product::find($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    throw new \Exception("Nepakanka prekių: {$product->name}. Turima: {$product->quantity}");
                }

                $unitPrice = $product->price;
                $totalPrice = $unitPrice * $item['quantity'];

                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice
                ]);

                $previousQuantity = $product->quantity;
                $product->decrement('quantity', $item['quantity']);
                $newQuantity = $product->quantity;

                InventoryTransaction::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'previous_quantity' => $previousQuantity,
                    'new_quantity' => $newQuantity,
                    'reason' => 'Order #' . $order->order_number,
                    'user_id' => Auth::id(),
                ]);

                $totalAmount += $totalPrice;
            }

            $order->update(['total_amount' => $totalAmount]);

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Užsakymas sėkmingai sukurtas!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Klaida: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'orderItems.product']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        if (!in_array($order->status, ['pending', 'processing'])) {
            return redirect()->route('orders.index')
                ->with('error', 'Šio užsakymo redaguoti nebegalima.');
        }

        $statuses = [
            'pending' => 'Laukiama',
            'processing' => 'Vykdoma',
            'shipped' => 'Išsiųsta',
            'delivered' => 'Pristatyta',
            'cancelled' => 'Atšaukta'
        ];

        return view('orders.edit', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string'
        ]);

        $oldStatus = $order->status;

        $order->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        if ($request->status == 'shipped' && !$order->shipped_at) {
            $order->update(['shipped_at' => now()]);
        }

        if ($request->status == 'delivered' && !$order->delivered_at) {
            $order->update(['delivered_at' => now()]);
        }

        if ($request->status == 'cancelled' && $oldStatus != 'cancelled') {
            foreach ($order->orderItems as $item) {
                $previousQuantity = $item->product->quantity;
                $item->product->increment('quantity', $item->quantity);
                $newQuantity = $item->product->quantity;

                InventoryTransaction::create([
                    'product_id' => $item->product->id,
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'previous_quantity' => $previousQuantity,
                    'new_quantity' => $newQuantity,
                    'reason' => 'Order #' . $order->order_number . ' cancel',
                    'user_id' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Užsakymo statusas atnaujintas!');
    }

    public function destroy(Order $order)
    {
        if ($order->status != 'cancelled') {
            return redirect()->route('orders.index')
                ->with('error', 'Galima trinti tik atšauktus užsakymus.');
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Užsakymas ištrintas!');
    }
}
