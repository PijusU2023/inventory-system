@extends('layouts.app')

@section('title', 'Ataskaitos peržiūra')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ $report->title }}</h2>
            <div>
                <span class="badge bg-primary me-2">{{ $report->type_name }}</span>
                <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Grįžti
                </a>
            </div>
        </div>

        <!-- Ataskaitos informacija -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Sukurta:</strong> {{ $report->created_at->format('Y-m-d H:i') }}
                            </div>
                            <div class="col-md-3">
                                <strong>Įrašų skaičius:</strong> {{ $report->records_count }}
                            </div>
                            <div class="col-md-3">
                                <strong>Tipas:</strong> {{ $report->type_name }}
                            </div>
                            <div class="col-md-3">
                                <strong>Autorius:</strong> {{ $report->user->name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ataskaitos duomenys -->
        <div class="card">
            <div class="card-body">
                @if($report->type == 'products')
                    @include('reports.partials.products-data', ['data' => $report->data])
                @elseif($report->type == 'categories')
                    @include('reports.partials.categories-data', ['data' => $report->data])
                @elseif($report->type == 'low_stock')
                    @include('reports.partials.low-stock-data', ['data' => $report->data])
                @elseif($report->type == 'inventory_value')
                    @include('reports.partials.inventory-value-data', ['data' => $report->data])
                @endif
            </div>
        </div>
    </div>
@endsection
