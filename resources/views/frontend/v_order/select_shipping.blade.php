@extends('frontend.v_layouts.app')
@section('content')

<div class="container col-md-12">
    <h3 class="title">PILIH PENGIRIMAN</h3>

    <form id="shipping-form d-flex flex-column gap-3">
        @csrf
        <div class="form-group">
            <label><strong>Provinsi Tujuan:</strong></label>
            <select name="province" id="province" class="form-control">
                <option value="">-- Pilih Provinsi --</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province['province_id'] }}">{{ $province['province'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label><strong>Kota Tujuan:</strong></label>
            <select name="city" id="city" class="form-control">
                <option value="">-- Pilih Kota --</option>
            </select>
        </div>

        <div class="form-group">
            <label><strong>Kurir:</strong></label>
            <select name="courier" id="courier" class="form-control">
                <option value="jne">JNE</option>
                <option value="tiki">TIKI</option>
                <option value="pos">POS</option>
            </select>
        </div>

        <div class="form-group">
            <label><strong>Alamat:</strong></label>
            <textarea name="address" id="address" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label><strong>Kode Pos:</strong></label>
            <input type="text" name="postal_code" id="postal_code" class="form-control">
        </div>

        <div class="form-group">
            <button type="button" id="cekOngkir" class="primary-btn">CEK ONGKIR</button>
        </div>
    </form>

    <table class="table table-bordered mt-4">
        <thead class="thead-dark">
            <tr>
                <th>LAYANAN</th>
                <th>ONGKIR</th>
                <th>ESTIMASI PENGIRIMAN</th>
                <th>TOTAL BERAT</th>
                <th>TOTAL HARGA</th>
                <th>BAYAR</th>
            </tr>
        </thead>
        <tbody id="shipping-options">
            <tr>
                <td colspan="6" class="text-center">Belum ada data pengiriman.</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    document.getElementById('province').addEventListener('change', function () {
        let province_id = this.value;
        let cityDropdown = document.getElementById('city');

        cityDropdown.innerHTML = '<option value="">-- Memuat Kota --</option>';

        fetch(`/cities?province_id=${province_id}`)
            .then(response => response.json())
            .then(data => {
                cityDropdown.innerHTML = '<option value="">-- Pilih Kota --</option>';
                data.rajaongkir.results.forEach(city => {
                    cityDropdown.innerHTML += `<option value="${city.city_id}">${city.city_name}</option>`;
                });
            })
            .catch(error => console.error('Error:', error));
    });

    document.getElementById('cekOngkir').addEventListener('click', function () {
        let origin = 501; // ID kota pengirim (misal: Yogyakarta)
        let destination = document.getElementById('city').value;
        let weight = 2480; // Contoh berat paket
        let courier = document.getElementById('courier').value;
        let shippingOptions = document.getElementById('shipping-options');

        shippingOptions.innerHTML = '<tr><td colspan="6" class="text-center">Memuat...</td></tr>';

        fetch(`/cost`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ origin, destination, weight, courier })
        })
        .then(response => response.json())
        .then(data => {
            shippingOptions.innerHTML = '';
            data.rajaongkir.results[0].costs.forEach(service => {
                service.cost.forEach(price => {
                    shippingOptions.innerHTML += `
                        <tr>
                            <td>${service.service}</td>
                            <td>${price.value} Rupiah</td>
                            <td>${price.etd} hari</td>
                            <td>${weight} Gram</td>
                            <td>Rp. 200.000</td>
                            <td><button class="primary-btn">PILIH PENGIRIMAN</button></td>
                        </tr>
                    `;
                });
            });
        })
        .catch(error => console.error('Error:', error));
    });
</script>

@endsection
