<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Pagrindiniai skaičiai
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'min_stock')->count();

        // Bendras inventoriaus vertė
        $totalInventoryValue = Product::sum(\DB::raw('quantity * price'));

        // Kritinių atsargų produktai (detaliai)
        $lowStockItems = Product::with('category')
            ->whereColumn('quantity', '<=', 'min_stock')
            ->take(5)
            ->get();

        // Naujausi produktai
        $recentProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        // Top kategorijos pagal produktų kiekį
        $topCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'lowStockProducts',
            'totalInventoryValue',
            'recentProducts',
            'lowStockItems',
            'topCategories'
        ));
    }
}
