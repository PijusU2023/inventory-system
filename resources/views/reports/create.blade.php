@extends('layouts.app')

@section('title', 'Sukurti ataskaitą')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Sukurti naują ataskaitą</h2>
            <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Grįžti į ataskaitas
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('reports.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Ataskaitos pavadinimas</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Ataskaitos tipas</label>
                                <select class="form-select @error('type') is-invalid @enderror"
                                        id="type" name="type" required onchange="toggleFilters()">
                                    <option value="">Pasirinkite ataskaitos tipą</option>
                                    <option value="products" {{ old('type') == 'products' ? 'selected' : '' }}>Produktų ataskaita</option>
                                    <option value="categories" {{ old('type') == 'categories' ? 'selected' : '' }}>Kategorijų ataskaita</option>
                                    <option value="low_stock" {{ old('type') == 'low_stock' ? 'selected' : '' }}>Mažų atsargų ataskaita</option>
                                    <option value="inventory_value" {{ old('type') == 'inventory_value' ? 'selected' : '' }}>Inventoriaus vertės ataskaita</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Filtrai produktų ataskaitai -->
                            <div id="product-filters" style="display: none;">
                                <h6>Filtrai (neprivaloma)</h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Kategorija</label>
                                            <select class="form-select" id="category" name="category">
                                                <option value="">Visos kategorijos</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Būklė</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="">Visos būklės</option>
                                                <option value="in_stock" {{ old('status') == 'in_stock' ? 'selected' : '' }}>Yra sandėlyje</option>
                                                <option value="low_stock" {{ old('status') == 'low_stock' ? 'selected' : '' }}>Mažas likutis</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i> Sukurti ataskaitą
                                </button>
                                <a href="{{ route('reports.index') }}" class="btn btn-secondary">Atšaukti</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6>Ataskaitų tipai</h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <ul class="mb-0">
                                <li><strong>Produktų ataskaita:</strong> Visi produktai su jų duomenimis</li>
                                <li><strong>Kategorijų ataskaita:</strong> Kategorijų statistikos</li>
                                <li><strong>Mažų atsargų ataskaita:</strong> Produktai, kuriems reikia papildymo</li>
                                <li><strong>Inventoriaus vertės ataskaita:</strong> Bendra inventoriaus analizė</li>
                            </ul>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFilters() {
            const type = document.getElementById('type').value;
            const productFilters = document.getElementById('product-filters');

            if (type === 'products') {
                productFilters.style.display = 'block';
            } else {
                productFilters.style.display = 'none';
            }
        }

        // Show filters if products is already selected
        document.addEventListener('DOMContentLoaded', function() {
            toggleFilters();
        });
    </script>
@endsection
