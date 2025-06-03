<h5>Mažų atsargų ataskaita</h5>

@if(count($data) > 0)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i>
        Rasta <strong>{{ count($data) }}</strong> produktų su mažomis atsargomis, kuriems reikia papildymo!
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Pavadinimas</th>
                <th>SKU</th>
                <th>Kategorija</th>
                <th>Dabartinis kiekis</th>
                <th>Minimalus kiekis</th>
                <th>Trūkumas</th>
                <th>Kaina</th>
                <th>Rekomenduojama papildyti</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td><strong>{{ $item['name'] }}</strong></td>
                    <td><code>{{ $item['sku'] }}</code></td>
                    <td><span class="badge bg-secondary">{{ $item['category'] }}</span></td>
                    <td><span class="badge bg-danger">{{ $item['current_stock'] }}</span></td>
                    <td>{{ $item['min_stock'] }}</td>
                    <td><span class="badge bg-warning">{{ $item['difference'] }}</span></td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>{{ $item['min_stock'] * 2 }} vnt.</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i>
        Puiku! Visi produktai turi pakankamas atsargas.
    </div>
@endif
