@extends('layouts.app')

@section('title', 'Kategorijos')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kategorijos</h2>
            <a href="{{ route('categories.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Pridėti naują kategoriją
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Pavadinimas</th>
                                <th>Aprašymas</th>
                                <th>Prekių kiekis</th>
                                <th>Sukurta</th>
                                <th>Veiksmai</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td><strong>{{ $category->name }}</strong></td>
                                    <td>{{ $category->description ?? 'Aprašymas nėra' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $category->products->count() }}</span>
                                    </td>
                                    <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('categories.show', $category) }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> Peržiūrėti
                                            </a>
                                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil"></i> Redaguoti
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ar tikrai norite ištrinti?')">
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
                        <i class="bi bi-tags fs-1 text-muted"></i>
                        <h5 class="text-muted mt-3">Kategorijų nerasta</h5>
                        <p class="text-muted">Pradėkite kurdami pirmą kategoriją</p>
                        <a href="{{ route('categories.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> Kurti kategoriją
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
