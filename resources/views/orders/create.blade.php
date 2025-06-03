@extends('layouts.app')

@section('title', 'Naujas užsakymas')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Naujas užsakymas</h3>
                    </div>

                    <div class="card-body">
                        <!-- Užsakymo forma -->
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="customer_id" class="form-label">Klientas</label>
                                        <select name="customer_id" id="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                                            <option value="">-- Pasirinkite klientą --</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }} ({{ $customer->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('customer_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Pastabos</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <h4>Prekės</h4>
                            <div id="products-container">
                                <div class="product-row mb-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <select name="products[0][product_id]" class="form-select product-select" required>
                                                <option value="">-- Pasirinkite prekę --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}"
                                                            data-price="{{ $product->price }}"
                                                            data-stock="{{ $product->quantity }}">
                                                        {{ $product->name }} (Turima: {{ $product->quantity }}) - €{{ number_format($product->price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="products[0][quantity]" class="form-control quantity-input"
                                                   placeholder="Kiekis" min="1" required>
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

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Sukurti užsakymą</button>
                                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Atšaukti</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let productIndex = 0;

            document.getElementById('add-product').addEventListener('click', function() {
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
                updateEventListeners();
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-product') || e.target.parentElement.classList.contains('remove-product')) {
                    const row = e.target.closest('.product-row');
                    row.remove();
                    calculateTotal();
                }
            });

            function updateEventListeners() {
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
                const stock = parseInt(select.options[select.selectedIndex]?.dataset.stock || 0);
                const quantity = parseInt(quantityInput.value || 0);

                if (quantity > stock) {
                    alert('Nepakanka prekių! Turima: ' + stock);
                    quantityInput.value = stock;
                    return;
                }

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

            updateEventListeners();
        });
    </script>
@endsection
