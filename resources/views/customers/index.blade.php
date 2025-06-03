@extends('layouts.app')

@section('title', 'Klientai')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Klientai</h2>
            <a href="{{ route('customers.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Pridėti naują klientą
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($customers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Vardas</th>
                                <th>El. paštas</th>
                                <th>Telefonas</th>
                                <th>Įmonė</th>
                                <th>Registracijos data</th>
                                <th>Veiksmai</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $customer->id }}</td>
                                    <td><strong>{{ $customer->name }}</strong></td>
                                    <td>
                                        <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                            {{ $customer->email }}
                                        </a>
                                    </td>
                                    <td>{{ $customer->phone ?: 'N/A' }}</td>
                                    <td>{{ $customer->company ?: 'N/A' }}</td>
                                    <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('customers.show', $customer) }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> Žiūrėti
                                            </a>
                                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Redaguoti
                                            </a>
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ar tikrai norite ištrinti?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
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
                        <i class="bi bi-people fs-1 text-muted"></i>
                        <h5 class="text-muted mt-3">Klientų nerasta</h5>
                        <p class="text-muted">Pradėkite pridėdami savo pirmą klientą</p>
                        <a href="{{ route('customers.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> Pridėti klientą
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
