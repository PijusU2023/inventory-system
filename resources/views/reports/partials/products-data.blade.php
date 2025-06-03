<h5>Produktų ataskaita</h5>

@if(count($data) > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Pavadinimas</th>
                <th>SKU</th>
                <th>Kategorija</th>
                <th>Kiekis</th>
                <th>Min. kiekis</th>
                <th>Kaina</th>
                <th>Bendra vertė</th>
                <th>Būklė</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td><strong>{{ $item['name'] }}</strong></td>
                    <td><code>{{ $item['sku'] }}</code></td>
                    <td><span class="badge bg-secondary">{{ $item['category'] }}</span></td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ $item['min_stock'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>${{ number_format($item['total_value'], 2) }}</td>
                    <td>
                        @if($item['status'] == 'Mažas likutis')
                            <span class="badge bg-danger">{{ $item['status'] }}</span>
                        @else
                            <span class="badge bg-success">{{ $item['status'] }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot class="table-light">
            <tr>
                <th colspan="7">Bendra visų produktų vertė:</th>
                <th>${{ number_format(collect($data)->sum('total_value'), 2) }}</th>
                <th></th>
            </tr>
            </tfoot>
        </table>
    </div>
@else
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nerasta produktų pagal nurodytus kriterijus.
    </div>
@endif
