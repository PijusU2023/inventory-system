@extends('layouts.app')

@section('title', 'Profilis')

@section('content')
    <div class="container">
        <h2>Mano profilis</h2>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Vardas</label>
                <input id="name" name="name" type="text" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                @error('name')
                <div class="text-danger">Prašome įvesti vardą.</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">El. paštas</label>
                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                @error('email')
                <div class="text-danger">Prašome įvesti galiojantį el. pašto adresą.</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Naujas slaptažodis (palikite tuščią, jei nenorite keisti)</label>
                <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
                @error('password')
                <div class="text-danger">Slaptažodis turi būti bent 8 simbolių ir sutapti su patvirtinimu.</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Patvirtinkite slaptažodį</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primary">Išsaugoti</button>
        </form>
    </div>
@endsection
