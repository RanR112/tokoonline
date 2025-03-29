@extends('frontend.v_layouts.app')
@section('content')
    <div class="col-md-12">
        <div class="order-summary clearfix">
            <div class="section-title">
                <p>PENGIRIMAN</p>
                <h3 class="title">Pilih Metode Pengiriman</h3>
            </div>
            
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <strong>{{ session('error') }}</strong>
                </div>
            @endif
            
            <form action="{{ route('order.updateongkir') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="province">Pilih Provinsi</label>
                    <select name="province" id="province" class="form-control">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach ($provinces as $province)
                            <option value="{{ $province['province_id'] }}">{{ $province['province'] }}</option>
                        @endforeach
                    </select>
                </div>
        
                <div class="form-group">
                    <label for="city">Pilih Kota</label>
                    <select name="city" id="city" class="form-control">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>
        
                <div class="form-group">
                    <label for="courier">Pilih Kurir</label>
                    <select name="courier" id="courier" class="form-control">
                        <option value="jne">JNE</option>
                        <option value="tiki">TIKI</option>
                        <option value="pos">POS</option>
                    </select>
                </div>
        
                <div class="form-group">
                    <label for="weight">Berat Paket (gram)</label>
                    <input type="number" name="weight" id="weight" class="form-control" value="1000">
                </div>
        
                <div class="form-group">
                    <button type="button" id="cekOngkir" class="btn btn-info">Cek Ongkir</button>
                </div>
        
                <div class="form-group">
                    <label for="cost">Ongkir</label>
                    <select name="cost" id="cost" class="form-control">
                        <option value="">-- Pilih Ongkir --</option>
                    </select>
                </div>
        
                <button type="submit" class="btn btn-primary">Lanjutkan Checkout</button>
            </form>
        </div>
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
            let weight = document.getElementById('weight').value;
            let courier = document.getElementById('courier').value;
            let costDropdown = document.getElementById('cost');
    
            costDropdown.innerHTML = '<option value="">-- Memuat Ongkir --</option>';
    
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
                costDropdown.innerHTML = '<option value="">-- Pilih Ongkir --</option>';
                data.rajaongkir.results[0].costs.forEach(service => {
                    service.cost.forEach(price => {
                        costDropdown.innerHTML += `<option value="${price.value}">${service.service} - Rp${price.value}</option>`;
                    });
                });
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
@endsection
