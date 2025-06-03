@extends('layouts.app')

@section('title', 'Naujas tiekimo užsakymas')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Naujas tiekimo užsakymas</h2>
            <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Grįžti
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Tiekėjo pasirinkimas -->
                <form action="{{ route('purchase_orders.create') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="supplier_id" class="form-label">Tiekėjas</label>
                            <select name="supplier_id" id="supplier_id" class="form-select" onchange="this.form.submit()" required>
                                <option value="">-- Pasirinkite tiekėją --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                @if($selectedSupplier)
                    @if($products->count())
                        <form action="{{ route('purchase_orders.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="supplier_id" value="{{ $selectedSupplier }}">

                            <div class="mb-3">
                                <label for="notes" class="form-label">Pastabos</label>
                                <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
                            </div>

                            <h5>Prekės</h5>
                            <div id="products-container">
                                <div class="product-row mb-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <select name="products[0][product_id]" class="form-select product-select" required>
                                                <option value="">-- Pasirinkite prekę --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->name }} ({{ $product->sku }}) - €{{ $product->price }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="products[0][quantity]" class="form-control quantity-input" placeholder="Kiekis" min="1" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control subtotal" readonly placeholder="Suma">
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger remove-product" style="display:none;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="add-product" class="btn btn-secondary mb-3">
                                <i class="bi bi-plus"></i> Pridėti prekę
                            </button>

                            <div class="row">
                                <div class="col-md-6 offset-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5>Bendra suma: €<span id="total-amount">0.00</span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success">Sukurti užsakymą</button>
                        </form>
                    @else
                        <div class="alert alert-warning mt-4">
                            Pasirinktas tiekėjas neturi prekių.
                        </div>
                    @endif
                @else
                    <div class="alert alert-info mt-4">
                        Prašome pasirinkti tiekėją, kad galėtumėte pridėti prekes.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let productIndex = 0;

            document.getElementById('add-product')?.addEventListener('click', function() {
                productIndex++;
                const container = document.getElementById('products-container');
                const newRow = document.querySelector('.product-row').cloneNode(true);

                newRow.querySelectorAll('select, input').forEach(function(element) {
                    if (element.name) {
                        element.name = element.name.replace('[0]', `[${productIndex}]`);
                    }
                    if (element.classList.contains('quantity-input')) {
                        element.value = '';
                    }
                    if (element.classList.contains('subtotal')) {
                        element.value = '';
                    }
                });

                newRow.querySelector('.remove-product').style.display = 'inline-block';
                container.appendChild(newRow);
                updateListeners();
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-product') || e.target.parentElement.classList.contains('remove-product')) {
                    const row = e.target.closest('.product-row');
                    row.remove();
                    calculateTotal();
                }
            });

            function updateListeners() {
                document.querySelectorAll('.product-select, .quantity-input').forEach(function(element) {
                    element.removeEventListener('change', calculateRow);
                    element.addEventListener('change', calculateRow);
                });
            }

            function calculateRow(e) {
                const row = e.target.closest('.product-row');
                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const subtotalInput = row.querySelector('.subtotal');

                const price = parseFloat(select.options[select.selectedIndex]?.dataset.price || 0);
                const quantity = parseInt(quantityInput.value || 0);
                const subtotal = price * quantity;

                subtotalInput.value = subtotal.toFixed(2);
                calculateTotal();
            }

            function calculateTotal() {
                let total = 0;
                document.querySelectorAll('.subtotal').forEach(function(input) {
                    total += parseFloat(input.value || 0);
                });
                document.getElementById('total-amount').textContent = total.toFixed(2);
            }

            updateListeners();
        });
    </script>
@endsection
