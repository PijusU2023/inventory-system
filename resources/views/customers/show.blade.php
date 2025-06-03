@extends('layouts.app')

@section('title', 'Kliento duomenys')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ $customer->name }}</h2>
            <div>
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Redaguoti
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Grįžti
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Kliento informacija</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Vardas:</strong> {{ $customer->name }}</p>
                                <p><strong>El. paštas:</strong>
                                    <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                        {{ $customer->email }}
                                    </a>
                                </p>
                                <p><strong>Telefonas:</strong> {{ $customer->phone ?: 'Nenurodytas' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Adresas:</strong></p>
                                <p class="text-muted">{{ $customer->address ?: 'Nenurodyta' }}</p>
                                <p><strong>Registracijos data:</strong> {{ $customer->created_at->format('Y-m-d') }}</p>
                                <p><strong>Paskutinis atnaujinimas:</strong> {{ $customer->updated_at->format('Y-m-d') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6>Statistika</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Užsakymų skaičius:</strong> <span class="badge bg-primary">0</span></p>
                        <p><strong>Bendra vertė:</strong> <span class="text-success">$0.00</span></p>
                        <p><strong>Paskutinis užsakymas:</strong> <span class="text-muted">Nėra</span></p>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6>Veiksmai</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Redaguoti klientą
                            </a>
                            <button class="btn btn-info" disabled>
                                <i class="bi bi-plus-circle"></i> Sukurti užsakymą (Netrukus)
                            </button>
                            <button class="btn btn-secondary" disabled>
                                <i class="bi bi-clock-history"></i> Užsakymų istorija (Netrukus)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
