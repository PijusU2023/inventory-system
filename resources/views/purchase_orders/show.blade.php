@extends('layouts.app')

@section('title', 'Tiekimo užsakymo detalės')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tiekimo užsakymas: {{ $purchaseOrder->order_number }}</h2>
            <div>
                @if(!in_array($purchaseOrder->status, ['received', 'cancelled']))
                    <a href="{{ route('purchase_orders.edit', $purchaseOrder) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Redaguoti
                    </a>
                @endif
                <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Grįžti
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informacija</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Tiekėjas:</strong> {{ $purchaseOrder->supplier->name }}</p>
                        <p><strong>Būsena:</strong>
                            @switch($purchaseOrder->status)
                                @case('pending') Laukiama @break
                                @case('processing') Vykdoma @break
                                @case('received') Gauta @break
                                @case('cancelled') Atšaukta @break
                                @default {{ ucfirst($purchaseOrder->status) }}
                            @endswitch
                        </p>
                        <p><strong>Sukurta:</strong> {{ $purchaseOrder->created_at->format('Y-m-d H:i') }}</p>
                        @if($purchaseOrder->received_at)
                            <p><strong>Gauta:</strong> {{ $purchaseOrder->received_at->format('Y-m-d H:i') }}</p>
                        @endif
                        @if($purchaseOrder->notes)
                            <hr>
                            <p><strong>Pastabos:</strong></p>
                            <p class="text-muted">{{ $purchaseOrder->notes }}</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Prekės</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Prekė</th>
                                    <th>Kiekis</th>
                                    <th>Kaina</th>
                                    <th>Suma</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($purchaseOrder->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>€{{ number_format($item->unit_price, 2, ',', ' ') }}</td>
                                        <td>€{{ number_format($item->total_price, 2, ',', ' ') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Bendra suma:</th>
                                    <th>€{{ number_format($purchaseOrder->total_amount, 2, ',', ' ') }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Santrauka</div>
                    <div class="card-body">
                        <p><strong>Prekių kiekis:</strong> {{ $purchaseOrder->items->sum('quantity') }}</p>
                        <p><strong>Bendra suma:</strong> €{{ number_format($purchaseOrder->total_amount, 2, ',', ' ') }}</p>
                        @if($purchaseOrder->status == 'cancelled')
                            <form action="{{ route('purchase_orders.destroy', $purchaseOrder) }}" method="POST" onsubmit="return confirm('Ar tikrai norite ištrinti šį užsakymą?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mt-3">
                                    <i class="bi bi-trash"></i> Ištrinti užsakymą
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
