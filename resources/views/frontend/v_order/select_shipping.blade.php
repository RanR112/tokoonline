@extends('frontend.v_layouts.app')
@section('content')
    <!-- template -->

    <div class="col-md-12" hidden>
        <div class="order-summary clearfix">
            <div class="section-title">
                <p>PENGIRIMAN</p>
                <h3 class="title">Produk</h3>
            </div>
            @if ($order && $order->orderItems->count() > 0)
                <table class="shopping-cart-table table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th></th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalHarga = 0;
                            $totalBerat = 0;
                        @endphp
                        @foreach ($order->orderItems as $item)
                            @php
                                $totalHarga += $item->harga * $item->quantity;
                                $totalBerat += $item->produk->berat * $item->quantity;
                            @endphp
                            <tr>
                                <td class="thumb"><img
                                        src="{{ asset('storage/img-produk/thumb_sm_' . $item->produk->foto) }}"
                                        alt=""></td>
                                <td class="details">
                                    <a>{{ $item->produk->nama_produk }}</a>
                                    <ul>
                                        <li><span>Berat: {{ $item->produk->berat }} Gram</span></li>
                                    </ul>
                                    <ul>
                                        <li><span>Stok: {{ $item->produk->stok }} Gram</span></li>
                                    </ul>
                                </td>
                                <td class="price text-center"><strong>Rp.
                                        {{ number_format($item->harga, 0, ',', '.') }}</strong></td>
                                <td class="qty text-center">
                                    <a> {{ $item->quantity }} </a>
                                </td>
                                <td class="total text-center"><strong class="primary-color">Rp.
                                        {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Keranjang belanja kosong.</p>
            @endif
        </div>
    </div>

    <div class="col-md-12">
        <div class="order-summary clearfix">
            <div class="section-title">
                <p>PENGIRIMAN</p>
                <h3 class="title">Pilih Pengiriman</h3>
            </div>
            <form id="shippingForm">
                <!-- Kota Asal -->
                <input type="hidden" id="city_origin" name="city_origin" value="">
                <input type="hidden" id="city_origin_name" name="city_origin_name" value="">
                <!-- /Kota Asal -->

                <div class="form-group">
                    <label for="province">Provinsi Tujuan:</label>
                    <select name="province" id="province" class="input">
                        <option value="">Pilih Provinsi Tujuan</option>
                        <!-- Data Provinsi Tujuan akan dimuat dengan JavaScript -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="city">Kota Tujuan:</label>
                    <select name="city" id="city" class="input">
                        <option value="">Pilih Kota Tujuan</option>
                        <!-- Data Kota Tujuan akan dimuat dengan JavaScript -->
                    </select>
                </div>
                <input type="hidden" name="weight" id="weight" value="{{ $totalBerat }}">
                <input type="hidden" name="province_name" id="province_name">
                <input type="hidden" name="city_name" id="city_name">
                <div class="form-group">
                    <label for="courier">Kurir:</label>
                    <select name="courier" id="courier" class="input">
                        <option value="">Pilih Kurir</option>
                        <option value="jne">JNE</option>
                        <option value="tiki">TIKI</option>
                        <option value="pos">POS Indonesia</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Alamat</label>
                    <textarea class="input" name="alamat" id="alamat">{{ Auth::user()->alamat }}</textarea>
                </div>
                <div class="form-group">
                    <label for="">Kode Pos</label>
                    <input type="text" class="input" name="kode_pos" id="kode_pos" value="{{ Auth::user()->pos }}">
                </div>
                <input type="hidden" id="totalHargaProduk" value="{{ $totalHarga }}">
                <button type="submit" class="primary-btn">Cek Ongkir</button>
            </form>

            <br>
            <div id="result">
                <table class="shopping-cart-table table">
                    <thead>
                        <tr>
                            <th>Layanan</th>
                            <th>Biaya</th>
                            <th>Estimasi Pengiriman</th>
                            <th>Total Berat</th>
                            <th>Total Harga</th>
                            <th>Bayar</th>
                        </tr>
                    </thead>
                    <tbody id="shippingResults">
                        <!-- Hasil dari pencarian akan dimuat di sini -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const originCityCode = 115; //ganti disini untuk kode kota asal  
            const originCityName = 'Depok'; //ganti disini untuk nama kota asal  

            document.getElementById('city_origin').value = originCityCode;
            document.getElementById('city_origin_name').value = originCityName;

            // Load provinces 
            fetch('/provinces')
                .then(response => response.json())
                .then(data => {
                    if (data.rajaongkir.status.code === 200) {
                        let provinces = data.rajaongkir.results;
                        let provinceSelect = document.getElementById('province');
                        provinces.forEach(province => {
                            let option = document.createElement('option');
                            option.value = province.province_id;
                            option.textContent = province.province;
                            provinceSelect.appendChild(option);
                        });
                    } else {
                        console.error('Failed to fetch provinces',
                            data.rajaongkir.status.description);
                    }
                })
                .catch(error => {
                    console.error('Error fetching provinces:', error);
                });

            // Load cities based on selected province 
            document.getElementById('province').addEventListener('change', function() {
                let provinceId = this.value;
                let provinceName = this.options[this.selectedIndex].text;
                document.getElementById('province_name').value = provinceName;

                fetch(`/cities?province_id=${provinceId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.rajaongkir.status.code === 200) {
                            let cities = data.rajaongkir.results;
                            let citySelect = document.getElementById('city');
                            citySelect.innerHTML = '<option value="">Pilih Kota Tujuan</option>';
                            cities.forEach(city => {
                                let option = document.createElement('option');
                                option.value = city.city_id;
                                option.textContent = city.city_name;
                                citySelect.appendChild(option);
                            });
                        } else {
                            console.error('Failed to fetch cities',
                                data.rajaongkir.status.description);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching cities:', error);
                    });
            });

            document.getElementById('city').addEventListener('change', function() {
                let cityName = this.options[this.selectedIndex].text;
                document.getElementById('city_name').value = cityName;
            });

            // Handle form submission for shipping cost check 
            document.getElementById('shippingForm').addEventListener('submit', function(event) {
                event.preventDefault();
                let origin = document.getElementById('city_origin').value;
                let originName = document.getElementById('city_origin_name').value;
                let destination = document.getElementById('city').value;
                let weight = document.getElementById('weight').value;
                let courier = document.getElementById('courier').value;
                let alamat = document.getElementById('alamat').value;
                let kodePos = document.getElementById('kode_pos').value;

                // Validasi alamat dan kode pos 
                if (!alamat.trim() || !kodePos.trim()) {
                    alert('Harap lengkapi alamat dan kode pos sebelum mengecek ongkir.');
                    return;
                }

                if (!origin || !originName || !destination || !weight || !courier) {
                    alert('Harap lengkapi semua kolom sebelum mengecek ongkir.');
                    return;
                }

                function formatRupiah(angka) {
                    return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                fetch('/cost', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            origin: origin,
                            destination: destination,
                            weight: weight,
                            courier: courier
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.rajaongkir.status.code === 200) {
                            let result = data.rajaongkir.results[0].costs;
                            let shippingResults = document.getElementById('shippingResults');
                            shippingResults.innerHTML = '';
                            result.forEach(cost => {
                                let row = document.createElement('tr');
                                let totalHarga = cost.cost[0].value + parseInt(document.getElementById('totalHargaProduk').value);
                                row.innerHTML = ` 
                                <td>${cost.service}</td> 
                                <td>${formatRupiah(cost.cost[0].value)}</td>
                                <td>${cost.cost[0].etd} hari</td> 
                                <td>${weight} Gram</td> 
                                <td>${formatRupiah(totalHarga)}</td> 
                                <td> 
                                    <form action="{{ route('order.update-ongkir') }}" method="post"> 
                                        @csrf 
                                        <input type="hidden" name="province" value="${document.getElementById('province').value}"> 
                                        <input type="hidden" name="city" value="${document.getElementById('city').value}"> 
                                        <input type="hidden" name="province_name" value="${document.getElementById('province_name').value}"> 
                                        <input type="hidden" name="city_name" value="${document.getElementById('city_name').value}"> 
                                        <input type="hidden" name="kurir" value="${courier}"> 
                                        <input type="hidden" name="alamat" value="${alamat}"> 
                                        <input type="hidden" name="pos" value="${kodePos}"> 
                                        <input type="hidden" name="layanan_ongkir" value="${cost.service}"> 
                                        <input type="hidden" name="biaya_ongkir" value="${cost.cost[0].value}"> 
                                        <input type="hidden" name="estimasi_ongkir" value="${cost.cost[0].etd}"> 
                                        <input type="hidden" name="total_berat" value="${weight}"> 
                                        <input type="hidden" name="city_origin" value="${origin}"> 
                                        <input type="hidden" name="city_origin_name" value="${originName}"> 
                                        <button type="submit" class="primary-btn">Pilih Pengiriman</button> 
                                    </form> 
                                </td> 
                            `;
                                shippingResults.appendChild(row);
                            });
                        } else {
                            console.error('Failed to fetch cost',
                                data.rajaongkir.status.description);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching cost:', error);
                    });
            });
        });
    </script>

    <!-- end template-->
@endsection
