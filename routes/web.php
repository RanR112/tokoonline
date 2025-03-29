<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RajaOngkirController;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    // return view('welcome'); 
    return redirect()->route('frontend.beranda');
});
Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])->name('backend.beranda')->middleware('auth');
Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login');
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('backend.login');
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');
// Route::resource('backend/user', UserController::class)->middleware('auth'); 
Route::resource('backend/user', UserController::class, ['as' => 'backend'])->middleware('auth');
Route::resource('user', UserController::class, ['as' => 'backend']);
Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend'])->middleware('auth');
Route::resource('backend/produk', ProdukController::class, ['as' => 'backend'])->middleware('auth');
// Route untuk menambahkan foto 
Route::post('foto-produk/store', [ProdukController::class, 'storeFoto'])->name('backend.foto_produk.store')->middleware('auth');
// Route untuk menghapus foto 
Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])->name('backend.foto_produk.destroy')->middleware('auth');
// Laporan user
Route::get('backend/laporan/formuser', [UserController::class, 'formUser'])->name('backend.laporan.formuser')->middleware('auth');
Route::post('backend/laporan/cetakuser', [UserController::class, 'cetakUser'])->name('backend.laporan.cetakuser')->middleware('auth');
// Route untuk laporan produk 
Route::get('backend/laporan/formproduk', [ProdukController::class, 'formProduk'])->name('backend.laporan.formproduk')->middleware('auth');
Route::post('backend/laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])->name('backend.laporan.cetakproduk')->middleware('auth');

// Route untuk Customer 
Route::resource('backend/customer', CustomerController::class, ['as' => 'backend'])->middleware('auth');

// Frontend
Route::get('beranda', [BerandaController::class, 'index'])->name('frontend.beranda');

Route::get('/produk/detail/{id}', [ProdukController::class, 'detail'])->name('frontend.produk.detail');
Route::get('/produk/kategori/{id}', [ProdukController::class, 'produkKategori'])->name('frontend.produk.produkkategori');
Route::get('/produk/all', [ProdukController::class, 'produkAll'])->name('frontend.produk.all');

//API Google 
Route::get('/auth/redirect', [CustomerController::class, 'redirect'])->name('auth.redirect');
Route::get('/auth/google/callback', [CustomerController::class, 'callback'])->name('auth.callback');
// Logout 
Route::post('/logout', [CustomerController::class, 'logout'])->name('customer.logout');
// Group route untuk customer 
Route::middleware('is.customer')->group(function () {
    // Route untuk menampilkan halaman akun customer 
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])->name('customer.akun');
    // Route untuk mengupdate data akun customer 
    Route::put('/customer/akun/{id}/update', [CustomerController::class, 'updateAkun'])->name('customer.akun.update');
    // Route untuk menambahkan produk ke keranjang 
    Route::post('add-to-cart/{id}', [OrderController::class, 'addToCart'])->name('order.addToCart');
    Route::get('cart', [OrderController::class, 'viewCart'])->name('order.cart');
    Route::post('cart/update/{id}', [OrderController::class, 'updateCart'])->name('order.updateCart'); 
    Route::post('remove/{id}', [OrderController::class, 'removeFromCart'])->name('order.remove'); 
    // Ongkir 
    Route::post('select-shipping', [OrderController::class, 'selectShipping'])->name('order.selectShipping'); 
    Route::get('provinces', [OrderController::class, 'getProvinces']); 
    Route::get('cities', [OrderController::class, 'getCities']); 
    Route::post('cost', [OrderController::class, 'getCost']); 
    Route::post('updateongkir', [OrderController::class, 'updateongkir'])->name('order.updateongkir'); 
    Route::get('selectpayment', [OrderController::class, 'selectpayment'])->name('order.selectpayment'); 
});

Route::get('/list-ongkir', function () {
    $response = Http::withHeaders([
        'key' => 'gvBeCGo320ea2ae603d56919YtPsZW2d'
    ])->get('https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=banjarnegara&limit=1000&offset=0'); //ganti 'province' atau 'city' 
    dd($response->json());
});

Route::get('/cek-ongkir', function () {
    return view('frontend.ongkir');
});

Route::get('/provinces', [RajaOngkirController::class, 'getProvinces']);
Route::get('/cities', [RajaOngkirController::class, 'getCities']);
Route::post('/cost', [RajaOngkirController::class, 'getCost']);
