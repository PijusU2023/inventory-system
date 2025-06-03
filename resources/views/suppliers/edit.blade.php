@extends('layouts.app')

@section('title', 'Redaguoti tiekėją')

@section('content')
    <div class="container">
        <h2>Redaguoti tiekėją: {{ $supplier->name }}</h2>

        <form action="{{ route('suppliers.update', $supplier) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Pavadinimas</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name', $supplier->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">El. paštas</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email', $supplier->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Telefonas</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                       id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Adresas</label>
                <textarea class="form-control @error('address') is-invalid @enderror"
                          id="address" name="address" rows="3">{{ old('address', $supplier->address) }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Išsaugoti pakeitimus</button>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Atšaukti</a>
        </form>
    </div>
@endsection
