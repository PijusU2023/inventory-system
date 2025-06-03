@extends('layouts.app')

@section('title', 'Redaguoti prekę')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Redaguoti prekę: {{ $product->name }}</h2>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Atgal į prekes
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('products.update', $product) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Prekės pavadinimas</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku" class="form-label">SKU kodas</label>
                                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                               id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                        @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Aprašymas</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Kategorija</label>
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                                id="category_id" name="category_id" required>
                                            <option value="">Pasirinkite kategoriją</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="supplier_id" class="form-label">Tiekėjas</label>
                                        <select class="form-select @error('supplier_id') is-invalid @enderror"
                                                id="supplier_id" name="supplier_id" required>
                                            <option value="">Pasirinkite tiekėją</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}"
                                                    {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Kaina (€)</label>
                                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                               id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                        @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Dabartinis kiekis</label>
                                        <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                               id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                                        @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="min_stock" class="form-label">Minimalus atsargų kiekis</label>
                                <input type="number" class="form-control @error('min_stock') is-invalid @enderror"
                                       id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required>
                                @error('min_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Atnaujinti prekę
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">Atšaukti</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6>Dabartinė būsena</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Būsena:</strong>
                            @if($product->isLowStock())
                                <span class="badge bg-danger">Mažas kiekis</span>
                            @else
                                <span class="badge bg-success">Sandėlyje</span>
                            @endif
                        </p>
                        <p><strong>Sukurta:</strong> {{ $product->created_at->format('Y-m-d') }}</p>
                        <p><strong>Paskutinį kartą atnaujinta:</strong> {{ $product->updated_at->format('Y-m-d') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
