@extends('layouts.app')

@section('title', 'Pagrindinis puslapis')

@section('content')
    <div class="container-fluid">
        <!-- Statistikos kortelės -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Prekių iš viso</h6>
                                <h2 class="mb-0">{{ $totalProducts }}</h2>
                                <small>Prekių sandėlyje</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-box fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Kategorijos</h6>
                                <h2 class="mb-0">{{ $totalCategories }}</h2>
                                <small>Prekių kategorijos</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-tags fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Tiekėjai</h6>
                                <h2 class="mb-0">{{ $totalSuppliers }}</h2>
                                <small>Aktyvūs tiekėjai</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-truck fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Mažas likutis</h6>
                                <h2 class="mb-0">{{ $lowStockProducts }}</h2>
                                <small>Prekės reikalaujančios papildymo</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-exclamation-triangle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Antra eilutė su inventoriaus verte -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card stats-card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Bendra inventoriaus vertė</h6>
                                <h2 class="mb-0">${{ number_format($totalInventoryValue, 2) }}</h2>
                                <small>Esamo inventoriaus vertė</small>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-currency-dollar fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Turinys kortelės -->
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Naujausi produktai</h5>
                    </div>
                    <div class="card-body">
                        @if($recentProducts->count() > 0)
                            @foreach($recentProducts as $product)
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <small class="text-muted d-block">{{ $product->category->name }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary">{{ $product->quantity }}</span>
                                        <small class="text-muted d-block">${{ number_format($product->price, 2) }}</small>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center mt-3">
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">Žiūrėti visus produktus</a>
                            </div>
                        @else
                            <p class="text-muted">Nėra naujausių produktų</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Mažas likutis</h5>
                    </div>
                    <div class="card-body">
                        @if($lowStockItems->count() > 0)
                            @foreach($lowStockItems as $product)
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <small class="text-muted d-block">{{ $product->category->name }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-danger">{{ $product->quantity }}</span>
                                        <small class="text-muted d-block">Min: {{ $product->min_stock }}</small>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center mt-3">
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-danger">Valdyti atsargas</a>
                            </div>
                        @else
                            <div class="text-center">
                                <i class="bi bi-check-circle text-success fs-2"></i>
                                <p class="text-success mt-2">Visos prekės turi pakankamas atsargas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Populiariausios kategorijos</h5>
                    </div>
                    <div class="card-body">
                        @if($topCategories->count() > 0)
                            @foreach($topCategories as $category)
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <div>
                                        <strong>{{ $category->name }}</strong>
                                    </div>
                                    <span class="badge bg-secondary">{{ $category->products_count }} prekės</span>
                                </div>
                            @endforeach
                            <div class="text-center mt-3">
                                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">Žiūrėti visas kategorijas</a>
                            </div>
                        @else
                            <p class="text-muted">Nėra prieinamų kategorijų</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
