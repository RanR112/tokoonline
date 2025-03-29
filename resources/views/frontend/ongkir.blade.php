<!DOCTYPE html>
<html>

<head>
    <title>Cek Ongkir</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <form id="ongkirForm">
        <select name="province" id="province">
            <option value="">Pilih Provinsi</option>
        </select>
        <select name="city" id="city">
            <option value="">Pilih Kota</option>
        </select>
        <input type="number" name="weight" id="weight" placeholder="Berat (gram)">
        <select name="courier" id="courier">
            <option value="">Pilih Kurir</option>
            <option value="jne">JNE</option>
            <option value="tiki">TIKI</option>
            <option value="pos">POS Indonesia</option>
        </select>
        <button type="submit">Cek Ongkir</button>
    </form>

    <div id="result"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
                    fetch('/provinces')
                        .then(response => response.json())
                        .then(data => {
                            console.log('Provinces data:', data);
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

                    document.getElementById('province').addEventListener('change', function() {
                        let provinceId = this.value;
                        fetch(`/cities?province_id=${provinceId}`)
                            .then(response => response.json())
                            .then(data => {
                                console.log('Cities data:', data);
                                if (data.rajaongkir.status.code === 200) {
                                    let cities = data.rajaongkir.results;
                                    let citySelect = document.getElementById('city');
                                    citySelect.innerHTML = '';
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

                    document.getElementById('ongkirForm').addEventListener('submit',
                        function(event) {
                            event.preventDefault();
                            let origin = 501; // Kode kota asal 
                            let destination = document.getElementById('city').value;
                            let weight = document.getElementById('weight').value;
                            let courier = document.getElementById('courier').value;

                            fetch('/cost', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
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
                                    console.log('Cost data:', data);
                                    if (data.rajaongkir.status.code === 200) {
                                        let result = data.rajaongkir.results[0].costs;
                                        let resultDiv = document.getElementById('result');
                                        resultDiv.innerHTML = '';
                                        result.forEach(cost => {
                                            let div = document.createElement('div');
                                            div.textContent = `${cost.service} : ${cost.cost[0].value} Rupiah (${cost.cost[0].etd} hari)`;
                                            resultDiv.appendChild(div);
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
</body>

</html>
