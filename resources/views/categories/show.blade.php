@extends('layouts.app')

@section('title', 'Kategorijos informacija')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ $category->name }}</h2>
            <div>
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Redaguoti
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Grįžti
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Kategorijos informacija</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Pavadinimas:</strong> {{ $category->name }}</p>
                        <p><strong>Aprašymas:</strong> {{ $category->description ?? 'Aprašymas nėra' }}</p>
                        <p><strong>Sukurta:</strong> {{ $category->created_at->format('Y m. d.') }}</p>
                        <p><strong>Prekių kiekis:</strong> <span class="badge bg-primary">{{ $category->products->count() }}</span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Prekės šioje kategorijoje</h5>
                    </div>
                    <div class="card-body">
                        @if($category->products->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Pavadinimas</th>
                                        <th>SKU</th>
                                        <th>Kiekis</th>
                                        <th>Kaina</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($category->products as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->sku }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td>€{{ number_format($product->price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Šioje kategorijoje dar nėra prekių.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
