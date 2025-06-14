@extends('frontend.v_layouts.app')
@section('content')
    <!-- template -->

@section('content')
    <div class="col-md-12">
        <div class="order-summary clearfix">
            <div class="section-title">
                <p>HISTORY</p>
                <h3 class="title">HISTORY PESANAN</h3>
            </div>
            <!-- msgSuccess -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <!-- end msgSuccess -->
            @if ($orders->count() > 0)
                <table class="shopping-cart-table table">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Tanggal</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td>Rp. {{ number_format($order->total_harga + $order->biaya_ongkir, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($order->status == 'Paid')
                                        Proses
                                    @else
                                        {{ $order->status }}
                                    @endif
                                </td>
                                <td>
                                    <button class="primary-btn" data-toggle="modal" data-target="#orderDetailModal{{ $order->id }}">Lihat Detail</button>
                                    <a href="{{ route('order.invoice', $order->id) }}" target="_blank">
                                        <button type="button" class="primary-btn">Invoice</button>
                                    </a>

                                    <!-- Modal -->
                                    <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="orderDetailModalLabel{{ $order->id }}" aria
                                        hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="orderDetailModalLabel{{ $order->id }}">
                                                        Detail Pesanan #{{ $order->id }}</h5>
                                                    <button type="button" class="close" data dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama Produk</th>
                                                                <th>Jumlah</th>
                                                                <th>Harga</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($order->orderItems as $item)
                                                                <tr>
                                                                    <td>{{ $item->produk->nama_produk }}</td>
                                                                    <td>{{ $item->quantity }}</td>
                                                                    <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}
                                                                    </td>
                                                                    <td>Rp.
                                                                        {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @else
                <p>Anda belum memiliki riwayat pesanan.</p>
            @endif
        </div>
    </div>
@endsection

<div class="col-md-12">
    <div class="order-summary clearfix">
        <div class="section-title">
            <h3 class="title">Order Review</h3>
        </div>
        @if ($orders->count() > 0)
            <table class="shopping-cart-table table">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->produk->nama_produk }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format($item->harga * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach

                </tbody>

            </table>
            <div class="pull-right">
                <button class="primary-btn">Place Order</button>
            </div>
        @else
            <p>Anda belum memiliki riwayat pesanan.</p>
        @endif
    </div>
</div>
<!-- end template-->
@endsection
