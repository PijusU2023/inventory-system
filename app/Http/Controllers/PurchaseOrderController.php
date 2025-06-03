<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->latest()->paginate(10);
        return view('purchase_orders.index', compact('purchaseOrders'));
    }

    public function create(Request $request)
    {
        $suppliers = Supplier::all();
        $selectedSupplier = $request->query('supplier_id');
        $products = collect();

        if ($selectedSupplier) {
            $products = Product::where('supplier_id', $selectedSupplier)->get();
        }

        return view('purchase_orders.create', compact('suppliers', 'products', 'selectedSupplier'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $product = Product::find($value);
                    if (!$product) {
                        return $fail("Prekė su ID {$value} nerasta.");
                    }
                    if ($product->supplier_id != $request->supplier_id) {
                        return $fail("Prekė '{$product->name}' nepriklauso pasirinktam tiekėjui.");
                    }
                },
            ],
            'products.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $purchaseOrder = PurchaseOrder::create([
                'order_number' => 'PO-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'supplier_id' => $request->supplier_id,
                'status' => 'pending',
                'notes' => $request->notes,
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $purchaseOrder->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $subtotal,
                ]);
            }

            $purchaseOrder->update(['total_amount' => $total]);

            DB::commit();

            return redirect()->route('purchase_orders.index')->with('success', 'Užsakymas tiekėjui sukurtas!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Klaida: ' . $e->getMessage())->withInput();
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('supplier', 'items.product');
        return view('purchase_orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $statuses = ['pending', 'processing', 'received', 'cancelled'];
        return view('purchase_orders.edit', compact('purchaseOrder', 'statuses'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,received,cancelled',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $purchaseOrder->status;
            $purchaseOrder->update([
                'status' => $request->status,
                'notes' => $request->notes,
                'received_at' => $request->status == 'received' && !$purchaseOrder->received_at ? now() : $purchaseOrder->received_at,
            ]);

            if ($oldStatus != 'received' && $request->status == 'received') {
                foreach ($purchaseOrder->items as $item) {
                    $product = $item->product;
                    $previousQuantity = $product->quantity;
                    $product->increment('quantity', $item->quantity);
                    $newQuantity = $product->quantity;

                    InventoryTransaction::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $item->quantity,
                        'previous_quantity' => $previousQuantity,
                        'new_quantity' => $newQuantity,
                        'reason' => 'Purchase Order #' . $purchaseOrder->order_number,
                        'user_id' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('purchase_orders.show', $purchaseOrder)->with('success', 'Tiekimo užsakymas atnaujintas!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Klaida: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->delete();
        return redirect()->route('purchase_orders.index')->with('success', 'Tiekimo užsakymas ištrintas!');
    }
}
