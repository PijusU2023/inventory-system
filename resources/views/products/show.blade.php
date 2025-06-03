@extends('layouts.app')

@section('title', 'Produkto detalės')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ $product->name }}</h2>
            <div>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Redaguoti
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Grįžti
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Produkto informacija</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Pavadinimas:</strong> {{ $product->name }}</p>
                                <p><strong>SKU:</strong> <code>{{ $product->sku }}</code></p>
                                <p><strong>Kategorija:</strong>
                                    <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                </p>
                                <p><strong>Aprašymas:</strong></p>
                                <p class="text-muted">{{ $product->description ?: 'Aprašymas nėra pateiktas' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Kaina:</strong> <span class="fs-4 text-success">€{{ number_format($product->price, 2, ',', ' ') }}</span></p>
                                <p><strong>Esamas kiekis:</strong>
                                    <span class="badge {{ $product->isLowStock() ? 'bg-danger' : 'bg-success' }} fs-6">
                                        {{ $product->quantity }} vnt.
                                    </span>
                                </p>
                                <p><strong>Minimalus kiekis:</strong> {{ $product->min_stock }} vnt.</p>
                                <p><strong>Būklė:</strong>
                                    @if($product->isLowStock())
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-triangle"></i> Mažas likutis
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Yra sandėlyje
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6>Greitosios funkcijos</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Redaguoti produktą
                            </a>
                            <button class="btn btn-info" disabled>
                                <i class="bi bi-plus-circle"></i> Pridėti atsargų (Greitai)
                            </button>
                            <button class="btn btn-secondary" disabled>
                                <i class="bi bi-clock-history"></i> Peržiūrėti istoriją (Greitai)
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6>Datos</h6>
                    </div>
                    <div class="card-body">
                        <small>
                            <p><strong>Sukurta:</strong><br>{{ $product->created_at->format('Y-m-d H:i') }}</p>
                            <p><strong>Paskutinį kartą atnaujinta:</strong><br>{{ $product->updated_at->format('Y-m-d H:i') }}</p>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
