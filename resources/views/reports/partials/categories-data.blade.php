<h5>Kategorijų ataskaita</h5>

@if(count($data) > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Kategorijos pavadinimas</th>
                <th>Produktų skaičius</th>
                <th>Bendra vertė</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td><strong>{{ $item['name'] }}</strong></td>
                    <td><span class="badge bg-primary">{{ $item['products_count'] }}</span></td>
                    <td>${{ number_format($item['total_value'], 2) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot class="table-light">
            <tr>
                <th>Iš viso:</th>
                <th>{{ count($data) }} kategorijos</th>
                <th>{{ collect($data)->sum('products_count') }} produktai</th>
                <th>${{ number_format(collect($data)->sum('total_value'), 2) }}</th>
            </tr>
            </tfoot>
        </table>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nerasta kategorijų.
    </div>
@endif
