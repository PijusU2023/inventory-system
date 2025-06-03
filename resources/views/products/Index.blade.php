@extends('layouts.app')

@section('title', 'Produktai')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Produktai</h2>
            <a href="{{ route('products.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Pridėti naują produktą
            </a>
        </div>

        <!-- Paieškos ir filtrų sekcija -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="search" class="form-label">Paieška</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control" id="search" name="search"
                                           value="{{ request('search') }}" placeholder="Ieškoti pagal pavadinimą, SKU, aprašymą...">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="category" class="form-label">Kategorija</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Visos kategorijos</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="status" class="form-label">Būklė</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Visos būklės</option>
                                    <option value="in_stock" {{ request('status') == 'in_stock' ? 'selected' : '' }}>Yra sandėlyje</option>
                                    <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Mažas likutis</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-1">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Ieškoti
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Išvalyti filtrus">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Rezultatų skaičius -->
        @if(request('search') || request('category') || request('status'))
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Rasta <strong>{{ $products->count() }}</strong> produktų pagal jūsų paieškos kriterijus.
                @if(request('search'))
                    Paieška: "<strong>{{ request('search') }}</strong>"
                @endif
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                @if($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Pavadinimas</th>
                                <th>SKU</th>
                                <th>Kategorija</th>
                                <th>Kiekis</th>
                                <th>Kaina</th>
                                <th>Būklė</th>
                                <th>Veiksmai</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td><strong>{{ $product->name }}</strong></td>
                                    <td><code>{{ $product->sku }}</code></td>
                                    <td><span class="badge bg-secondary">{{ $product->category->name }}</span></td>
                                    <td>
                                        <span class="badge {{ $product->isLowStock() ? 'bg-danger' : 'bg-success' }}">
                                            {{ $product->quantity }}
                                        </span>
                                    </td>
                                    <td>€{{ number_format($product->price, 2, ',', ' ') }}</td>
                                    <td>
                                        @if($product->isLowStock())
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle"></i> Mažas likutis
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Yra sandėlyje
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm" title="Peržiūrėti">
                                                <i class="bi bi-eye"></i> Žiūrėti
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm" title="Redaguoti">
                                                <i class="bi bi-pencil"></i> Redaguoti
                                            </a>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ar tikrai norite ištrinti šį produktą?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Ištrinti">
                                                    <i class="bi bi-trash"></i> Šalinti
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        @if(request('search') || request('category') || request('status'))
                            <i class="bi bi-search fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">Produktų nerasta</h5>
                            <p class="text-muted">Pabandykite keisti paieškos kriterijus</p>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">Išvalyti filtrus</a>
                        @else
                            <i class="bi bi-box fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">Produktų nerasta</h5>
                            <p class="text-muted">Pradėkite sukurdami savo pirmą produktą</p>
                            <a href="{{ route('products.create') }}" class="btn btn-success">
                                <i class="bi bi-plus-circle me-1"></i> Sukurti produktą
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
