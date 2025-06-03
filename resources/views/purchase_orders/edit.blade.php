@extends('layouts.app')

@section('title', 'Redaguoti tiekimo užsakymą')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Redaguoti tiekimo užsakymą: {{ $purchaseOrder->order_number }}</h2>
            <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Grįžti
            </a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('purchase_orders.update', $purchaseOrder) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="status" class="form-label">Būsena</label>
                                <select name="status" id="status" class="form-select" required>
                                    @foreach([
                                        'pending' => 'Laukiama',
                                        'processing' => 'Vykdoma',
                                        'received' => 'Gauta',
                                        'cancelled' => 'Atšaukta'
                                    ] as $value => $label)
                                        <option value="{{ $value }}" {{ $purchaseOrder->status == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Pastabos</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $purchaseOrder->notes) }}</textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Išsaugoti</button>
                                <a href="{{ route('purchase_orders.show', $purchaseOrder) }}" class="btn btn-secondary">Atšaukti</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Santrauka</div>
                    <div class="card-body">
                        <p><strong>Tiekėjas:</strong> {{ $purchaseOrder->supplier->name }}</p>
                        <p><strong>Bendra suma:</strong> €{{ number_format($purchaseOrder->total_amount, 2, ',', ' ') }}</p>
                        <p><strong>Prekių kiekis:</strong> {{ $purchaseOrder->items->sum('quantity') }}</p>
                        <p><strong>Sukurta:</strong> {{ $purchaseOrder->created_at->format('Y-m-d H:i') }}</p>
                        @if($purchaseOrder->received_at)
                            <p><strong>Gauta:</strong> {{ $purchaseOrder->received_at->format('Y-m-d H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
