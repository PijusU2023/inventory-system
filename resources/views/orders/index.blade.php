@extends('layouts.app')

@section('title', 'Užsakymai')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Užsakymai</h3>
                        <a href="{{ route('orders.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Naujas užsakymas
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-3">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show m-3">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-dark">
                                <tr>
                                    <th>Užsakymo Nr.</th>
                                    <th>Klientas</th>
                                    <th>Data</th>
                                    <th>Statusas</th>
                                    <th>Suma</th>
                                    <th>Veiksmai</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_number }}</strong></td>
                                        <td>{{ $order->customer->name }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_badge }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td class="fw-bold">€{{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info" title="Peržiūrėti">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning" title="Redaguoti">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($order->status == 'cancelled')
                                                <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Ar tikrai norite ištrinti?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Ištrinti">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Užsakymų nerasta</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
