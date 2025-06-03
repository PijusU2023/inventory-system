@extends('layouts.app')

@section('title', 'Tiekėjo detalės')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Tiekėjas: {{ $supplier->name }}</h2>
            <div>
                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Redaguoti
                </a>
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Grįžti
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Informacija apie tiekėją</h5>
            </div>
            <div class="card-body">
                <p><strong>Pavadinimas:</strong> {{ $supplier->name }}</p>
                <p><strong>El. paštas:</strong>
                    <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">{{ $supplier->email }}</a>
                </p>
                <p><strong>Telefonas:</strong> {{ $supplier->phone ?: 'Nenurodyta' }}</p>
                <p><strong>Adresas:</strong> {{ $supplier->address ?: 'Nenurodyta' }}</p>
            </div>
        </div>
    </div>
@endsection
