@extends('layouts.app')

@section('title', 'Tiekėjai')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tiekėjai</h2>
            <a href="{{ route('suppliers.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Pridėti naują tiekėją
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($suppliers->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Pavadinimas</th>
                                <th>El. paštas</th>
                                <th>Telefonas</th>
                                <th>Adresas</th>
                                <th>Veiksmai</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->id }}</td>
                                    <td><strong>{{ $supplier->name }}</strong></td>
                                    <td>
                                        <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">
                                            {{ $supplier->email }}
                                        </a>
                                    </td>
                                    <td>{{ $supplier->phone ?: 'Nenurodyta' }}</td>
                                    <td>{{ Str::limit($supplier->address, 30) ?: 'Nenurodyta' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> Peržiūrėti
                                            </a>
                                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Redaguoti
                                            </a>
                                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ar tikrai norite ištrinti šį tiekėją?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i> Ištrinti
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
                        <i class="bi bi-truck fs-1 text-muted"></i>
                        <h5 class="text-muted mt-3">Tiekėjų nerasta</h5>
                        <p class="text-muted">Pradėkite pridėdami pirmą tiekėją</p>
                        <a href="{{ route('suppliers.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> Pridėti tiekėją
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
