@extends('layouts.app')

@section('title', 'Ataskaitos')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Ataskaitos</h2>
            <a href="{{ route('reports.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Sukurti naują ataskaitą
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($reports->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Pavadinimas</th>
                                <th>Tipas</th>
                                <th>Įrašų skaičius</th>
                                <th>Sukurta</th>
                                <th>Veiksmai</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td>{{ $report->id }}</td>
                                    <td><strong>{{ $report->title }}</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ $report->type_name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $report->records_count }}</span>
                                    </td>
                                    <td>{{ $report->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('reports.show', $report) }}" class="btn btn-info btn-sm">
                                                <i class="bi bi-eye"></i> Žiūrėti
                                            </a>
                                            <form action="{{ route('reports.destroy', $report) }}" method="POST" style="display: inline;" onsubmit="return confirm('Ar tikrai norite ištrinti?')">
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
                        <i class="bi bi-graph-up fs-1 text-muted"></i>
                        <h5 class="text-muted mt-3">Ataskaitų nerasta</h5>
                        <p class="text-muted">Pradėkite sukurdami savo pirmą ataskaitą</p>
                        <a href="{{ route('reports.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> Sukurti ataskaitą
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
