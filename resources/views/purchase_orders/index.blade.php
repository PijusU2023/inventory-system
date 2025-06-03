@extends('layouts.app')

@section('title', 'Tiekimo užsakymai')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tiekimo užsakymai</h2>
            <a href="{{ route('purchase_orders.create') }}" class="btn btn-success">
                <i class="bi bi-plus"></i> Naujas užsakymas
            </a>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                        <tr>
                            <th>Užsakymo Nr.</th>
                            <th>Tiekėjas</th>
                            <th>Statusas</th>
                            <th>Bendra suma</th>
                            <th>Sukurta</th>
                            <th>Veiksmai</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($purchaseOrders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->supplier->name }}</td>
                                <td>
                                    <span class="badge
                                        @switch($order->status)
                                            @case('cancelled') bg-danger @break
                                            @case('received') bg-success @break
                                            @case('processing') bg-info @break
                                            @default bg-secondary
                                        @endswitch
                                    ">
                                        @switch($order->status)
                                            @case('pending') Laukiama @break
                                            @case('processing') Vykdoma @break
                                            @case('received') Gauta @break
                                            @case('cancelled') Atšaukta @break
                                            @default {{ ucfirst($order->status) }}
                                        @endswitch
                                    </span>
                                </td>
                                <td>€{{ number_format($order->total_amount, 2, ',', ' ') }}</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('purchase_orders.show', $order) }}" class="btn btn-sm btn-primary" title="Peržiūrėti">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('purchase_orders.edit', $order) }}" class="btn btn-sm btn-warning" title="Redaguoti">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('purchase_orders.destroy', $order) }}" method="POST" style="display:inline;" onsubmit="return confirm('Ar tikrai norite ištrinti šį užsakymą?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Ištrinti">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $purchaseOrders->links() }}
        </div>
    </div>
@endsection
