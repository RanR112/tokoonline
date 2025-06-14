<style>
    table {
        border-collapse: collapse;
        width: 100%;
        border: 1px solid #ccc;
    }

    table tr td {
        padding: 6px;
        font-weight: normal;
        border: 1px solid #ccc;
    }

    table th {
        border: 1px solid #ccc;
    }
</style>
<table>
    <tr>
        <td align="left">
            <img src="{{ asset('image/logo.png') }}" width="8%">
        </td>
    </tr>
    <tr>
        <td align="left">
            <h2>Detail Pesanan #{{ $order->id }}</h2>
            <strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}
        </td>
    </tr>
</table>
<p></p>

<table>
    <tr>
        <td align="left" style="border: hidden;">
            <h5>Pelanggan</h5>
            <address>
                Nama: {{ $order->customer->user->nama }}<br>
                Email: {{ $order->customer->email }}<br>
                Hp: {{ $order->customer->hp }}<br>
                Alamat: <br>{!! $order->alamat !!} <br>
                Kode Pos: {{ $order->pos }}
            </address>
        </td>
        <td align="right" style="border: hidden;">
            <h5>Ongkos Kirim</h5>
            <address>
                @if ($order->noresi)
                    No.Resi: {{ $order->noresi }} <br>
                @else
                    No. Resi &lt;&lt;dalam proses&gt;&gt; <br>
                @endif
                Kurir: {{ $order->kurir }}<br>
                Layanan: {{ $order->layanan_ongkir }}<br>
                Estimasi: {{ $order->estimasi_ongkir }} Hari<br>
                Berat: {{ $order->total_berat }} Gram<br>
            </address>
        </td>
    </tr>
</table>
<p></p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Produk</th>
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
                <td> {{ $loop->iteration }}</td>
                <td class="details">
                    <a>{{ $item->produk->nama_produk }} #{{ $item->produk->kategori->nama_kategori }}</a>
                    <ul>
                        <li><span>Berat: {{ $item->produk->berat }} Gram</span></li>
                        <li><span>Stok: {{ $item->produk->stok }} Gram</span></li>
                    </ul>
                </td>
                <td class="price text-center">Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                <td class="qty text-center">
                    <a> {{ $item->quantity }} </a>
                </td>
                <td class="total text-center">Rp. {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</td>

            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th class="empty" colspan="3"></th>
            <td>Subtotal</td>
            <td colspan="2">Rp. {{ number_format($totalHarga, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th class="empty" colspan="3"></th>
            <td>Ongkos Kirim</td>
            <td colspan="2">
                Rp. {{ number_format($order->biaya_ongkir, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <th class="empty" colspan="3"></th>
            <td><b>Total Bayar</b></td>
            <td colspan="2" class="total"> <b>Rp.
                    {{ number_format($totalHarga + $order->biaya_ongkir, 0, ',', '.') }}</b> </td>
        </tr>
    </tfoot>
</table>

<script>
    window.onload = function() {
        printStruk();
    }

    function printStruk() {
        window.print();
    }
</script>
