@extends('layouts.app')

@section('title', 'Inventoriaus transakcijos')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Inventoriaus transakcijos</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Atgal į pradinį
            </a>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Prekė</th>
                        <th>Tipas</th>
                        <th>Kiekis</th>
                        <th>Buvo</th>
                        <th>Dabar</th>
                        <th>Priežastis</th>
                        <th>Vartotojas</th>
                        <th>Data</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->product->name ?? 'Trūksta' }}</td>
                            <td>
                                <span class="badge bg-{{ $transaction->type == 'in' ? 'success' : ($transaction->type == 'out' ? 'danger' : 'warning') }} text-uppercase">
                                    {{ $transaction->type }}
                                </span>
                            </td>
                            <td>{{ $transaction->quantity }}</td>
                            <td>{{ $transaction->previous_quantity }}</td>
                            <td>{{ $transaction->new_quantity }}</td>
                            <td>{{ $transaction->reason }}</td>
                            <td>{{ $transaction->user->name ?? '-' }}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection
