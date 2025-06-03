@extends('layouts.app')

@section('title', 'Redaguoti užsakymą')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Redaguoti užsakymą: {{ $order->order_number }}</h2>
            <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Grįžti
            </a>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Užsakymo statusas</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="status" class="form-label">Statusas</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach($statuses as $value => $label)
                                        <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Pastabos</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="4">{{ old('notes', $order->notes) }}</textarea>
                                @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <strong>Dėmesio:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Pakeitus statusą į „Išsiųsta“, bus nustatyta išsiuntimo data</li>
                                    <li>Pakeitus statusą į „Pristatyta“, bus nustatyta pristatymo data</li>
                                    <li>Atšaukus užsakymą, prekės bus grąžintos į sandėlį</li>
                                </ul>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-pencil me-1"></i> Atnaujinti statusą
                                </button>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-secondary">Atšaukti</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6>Užsakymo informacija</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Užsakymo Nr.:</strong> {{ $order->order_number }}</p>
                        <p><strong>Klientas:</strong> {{ $order->customer->name }}</p>
                        <p><strong>Sukurta:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                        <p><strong>Bendra suma:</strong>
                            <span class="fs-5 text-success">€{{ number_format($order->total_amount, 2) }}</span>
                        </p>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6>Prekės užsakyme</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($order->orderItems as $item)
                                <li class="mb-2">
                                    <strong>{{ $item->product->name }}</strong><br>
                                    <small class="text-muted">
                                        {{ $item->quantity }} vnt. × €{{ number_format($item->unit_price, 2) }}
                                    </small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
