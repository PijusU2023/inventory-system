<h5>Inventoriaus vertės ataskaita</h5>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>${{ number_format($data['total_value'], 2) }}</h3>
                <p class="mb-0">Bendra inventoriaus vertė</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3>{{ $data['total_products'] }}</h3>
                <p class="mb-0">Produktų iš viso</p>
            </div>
        </div>
    </div>
</div>

<h6>Vertės paskirstymas pagal kategorijas:</h6>

@if(count($data['categories_breakdown']) > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
            <tr>
                <th>Kategorija</th>
                <th>Produktų skaičius</th>
                <th>Bendra vertė</th>
                <th>Procentas nuo bendros vertės</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data['categories_breakdown'] as $category)
                <tr>
                    <td><strong>{{ $category['category'] }}</strong></td>
                    <td><span class="badge bg-primary">{{ $category['products_count'] }}</span></td>
                    <td>${{ number_format($category['total_value'], 2) }}</td>
                    <td>
                        @php
                            $percentage = $data['total_value'] > 0 ? ($category['total_value'] / $data['total_value']) * 100 : 0;
                        @endphp
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" style="width: {{ $percentage }}%">
                                {{ number_format($percentage, 1) }}%
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nėra duomenų apie kategorijas.
    </div>
@endif

<div class="mt-4">
    <small class="text-muted">
        <i class="bi bi-info-circle"></i>
        Ataskaita sugeneruota: {{ $data['generated_at'] }}
    </small>
</div>
