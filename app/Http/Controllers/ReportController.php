<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('reports.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:products,categories,low_stock,inventory_value',
        ]);

        $data = $this->generateReportData($request->type, $request->all());

        Report::create([
            'title' => $request->title,
            'type' => $request->type,
            'filters' => $request->except(['_token', 'title', 'type']),
            'data' => $data['data'],
            'records_count' => $data['count'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('reports.index')->with('success', 'Ataskaita sėkmingai sukurta!');
    }

    public function show(Report $report)
    {
        // ar ataskaita priklauso dabartiniam vartotojui
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        return view('reports.show', compact('report'));
    }

    public function destroy(Report $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        $report->delete();
        return redirect()->route('reports.index')->with('success', 'Ataskaita ištrinta!');
    }

    private function generateReportData($type, $filters)
    {
        switch ($type) {
            case 'products':
                return $this->generateProductsReport($filters);
            case 'categories':
                return $this->generateCategoriesReport();
            case 'low_stock':
                return $this->generateLowStockReport();
            case 'inventory_value':
                return $this->generateInventoryValueReport();
            default:
                return ['data' => [], 'count' => 0];
        }
    }

    private function generateProductsReport($filters)
    {
        $query = Product::with('category');

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] == 'low_stock') {
                $query->whereColumn('quantity', '<=', 'min_stock');
            } elseif ($filters['status'] == 'in_stock') {
                $query->whereColumn('quantity', '>', 'min_stock');
            }
        }

        $products = $query->get();

        $data = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'category' => $product->category->name,
                'quantity' => $product->quantity,
                'min_stock' => $product->min_stock,
                'price' => $product->price,
                'total_value' => $product->quantity * $product->price,
                'status' => $product->isLowStock() ? 'Mažas likutis' : 'Yra sandėlyje',
            ];
        });

        return ['data' => $data, 'count' => $products->count()];
    }

    private function generateCategoriesReport()
    {
        $categories = Category::withCount('products')->get();

        $data = $categories->map(function ($category) {
            $totalValue = $category->products->sum(function ($product) {
                return $product->quantity * $product->price;
            });

            return [
                'id' => $category->id,
                'name' => $category->name,
                'products_count' => $category->products_count,
                'total_value' => $totalValue,
            ];
        });

        return ['data' => $data, 'count' => $categories->count()];
    }

    private function generateLowStockReport()
    {
        $products = Product::with('category')
            ->whereColumn('quantity', '<=', 'min_stock')
            ->get();

        $data = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'category' => $product->category->name,
                'current_stock' => $product->quantity,
                'min_stock' => $product->min_stock,
                'difference' => $product->min_stock - $product->quantity,
                'price' => $product->price,
            ];
        });

        return ['data' => $data, 'count' => $products->count()];
    }

    private function generateInventoryValueReport()
    {
        $products = Product::with('category')->get();
        $totalValue = $products->sum(function ($product) {
            return $product->quantity * $product->price;
        });

        $categoriesData = Category::all()->map(function ($category) {
            $categoryValue = $category->products->sum(function ($product) {
                return $product->quantity * $product->price;
            });

            return [
                'category' => $category->name,
                'products_count' => $category->products->count(),
                'total_value' => $categoryValue,
            ];
        });

        return [
            'data' => [
                'total_value' => $totalValue,
                'total_products' => $products->count(),
                'categories_breakdown' => $categoriesData,
                'generated_at' => now()->format('Y-m-d H:i:s'),
            ],
            'count' => 1
        ];
    }
}
