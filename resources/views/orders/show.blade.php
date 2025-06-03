@extends('layouts.app')

@section('title', 'Užsakymo detalės')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Užsakymas: {{ $order->order_number }}</h2>
            <div>
                @role('admin|manager|worker')
                @if(in_array($order->status, ['pending', 'processing']))
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Redaguoti
                    </a>
                @endif
                @endrole
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Grįžti
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Užsakymo informacija</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Užsakymo Nr.:</strong> {{ $order->order_number }}</p>
                                <p><strong>Klientas:</strong> {{ $order->customer->name }}</p>
                                <p><strong>El. paštas:</strong>
                                    <a href="mailto:{{ $order->customer->email }}" class="text-decoration-none">
                                        {{ $order->customer->email }}
                                    </a>
                                </p>
                                <p><strong>Telefonas:</strong> {{ $order->customer->phone ?: 'Nenurodytas' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Statusas:</strong>
                                    <span class="badge bg-{{ $order->status_badge }}">
                                        {{ $order->status_label }}
                                    </span>
                                </p>
                                <p><strong>Sukurta:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                                @if($order->shipped_at)
                                    <p><strong>Išsiųsta:</strong> {{ $order->shipped_at->format('Y-m-d H:i') }}</p>
                                @endif
                                @if($order->delivered_at)
                                    <p><strong>Pristatyta:</strong> {{ $order->delivered_at->format('Y-m-d H:i') }}</p>
                                @endif
                            </div>
                        </div>
                        @if($order->notes)
                            <hr>
                            <p><strong>Pastabos:</strong></p>
                            <p class="text-muted">{{ $order->notes }}</p>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Užsakytos prekės</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Prekė</th>
                                    <th>SKU</th>
                                    <th>Kiekis</th>
                                    <th>Vieneto kaina</th>
                                    <th>Suma</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td><strong>{{ $item->product->name }}</strong></td>
                                        <td><code>{{ $item->product->sku }}</code></td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>€{{ number_format($item->unit_price, 2) }}</td>
                                        <td>€{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Bendra suma:</th>
                                    <th>€{{ number_format($order->total_amount, 2) }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6>Užsakymo būsena</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="fs-1">
                                @switch($order->status)
                                    @case('pending')
                                        <i class="bi bi-clock text-warning"></i>
                                        @break
                                    @case('processing')
                                        <i class="bi bi-gear text-info"></i>
                                        @break
                                    @case('shipped')
                                        <i class="bi bi-truck text-primary"></i>
                                        @break
                                    @case('delivered')
                                        <i class="bi bi-check-circle text-success"></i>
                                        @break
                                    @case('cancelled')
                                        <i class="bi bi-x-circle text-danger"></i>
                                        @break
                                @endswitch
                            </div>
                            <h5 class="mt-2">{{ $order->status_label }}</h5>
                        </div>

                        @role('admin|manager|worker')
                        @if($order->status != 'cancelled' && $order->status != 'delivered')
                            <div class="d-grid">
                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Keisti statusą
                                </a>
                            </div>
                        @endif
                        @endrole
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6>Suvestinė</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Prekių kiekis:</strong>
                            <span class="badge bg-primary">{{ $order->orderItems->sum('quantity') }}</span>
                        </p>
                        <p><strong>Bendra suma:</strong>
                            <span class="fs-5 text-success">€{{ number_format($order->total_amount, 2) }}</span>
                        </p>
                    </div>
                </div>

                @role('admin|manager|worker')
                @if($order->status == 'cancelled')
                    <div class="card mt-3">
                        <div class="card-body text-center">
                            <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Ar tikrai norite ištrinti šį užsakymą?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Ištrinti užsakymą
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
                @endrole
            </div>
        </div>
    </div>
@endsection
