<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome1');
// });

Route::get('/', function () {
    return view('welcome');
});

Route::get('/file-upload', [FileUploadController::class, 'fileUpload']);
Route::post('/file-upload', [FileUploadController::class, 'prosesFileUpload']);

Route::get('login', [AuthController::class, 'index'])->name('login');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('proses_login', [AuthController::class, 'proses_login'])->name('proses_login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::post('proses_register', [AuthController::class, 'proses_register'])->name('proses_register');

//kita atur juga untuk middleware menggunakan group pada routing
//didalamnya terdapat group untuk mengecek kondisi login
//jika user yang login merupakan admin maka akan diarahkan ke AdminController
//jika user yang login merupakan manager maka akan diarahkan ke ManagerController

Route::group(['middleware' => 'auth'], function () {

    Route::group(['middleware' => ['cek_login:1']], function () {
        Route::resource('admin', AdminController::class);
    });
    Route::group(['middleware' => ['cek_login:2']], function () {
        Route::resource('manager', ManagerController::class);
    });
});

Route::get('/', [WelcomeController::class, 'index']);

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);          // halaman awal user
    Route::post('/list', [UserController::class, 'list']);      // data user dalam bentuk json untuk datatables
    Route::get('/create', [UserController::class, 'create']);   // halaman form tambah user
    Route::post('/', [UserController::class, 'store']);         // simpan data user baru
    Route::get('/{id}', [UserController::class, 'show']);       // detail user
    Route::get('/{id}/edit', [UserController::class, 'edit']);  // halaman form edit user
    Route::put('/{id}', [UserController::class, 'update']);     // simpan perubahan data user
    Route::delete('/{id}', [UserController::class, 'destroy']); // hapus data user
});

Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index']);          // halaman awal level
    Route::post('/list', [LevelController::class, 'list']);      // data level dalam bentuk json untuk datatables
    Route::get('/create', [LevelController::class, 'create']);   // halaman form tambah level
    Route::post('/', [LevelController::class, 'store']);         // simpan data level baru
    Route::get('/{id}', [LevelController::class, 'show']);       // detail level
    Route::get('/{id}/edit', [LevelController::class, 'edit']);  // halaman form edit level
    Route::put('/{id}', [LevelController::class, 'update']);     // simpan perubahan data level
    Route::delete('/{id}', [LevelController::class, 'destroy']); // hapus data level
});

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriController::class, 'index']);          // halaman awal kategori
    Route::post('/list', [KategoriController::class, 'list']);      // data kategori dalam bentuk json untuk datatables
    Route::get('/create', [KategoriController::class, 'create']);   // halaman form tambah kategori
    Route::post('/', [KategoriController::class, 'store']);         // simpan data kategori baru
    Route::get('/{id}', [KategoriController::class, 'show']);       // detail kategori
    Route::get('/{id}/edit', [KategoriController::class, 'edit']);  // halaman form edit kategori
    Route::put('/{id}', [KategoriController::class, 'update']);     // simpan perubahan data kategori
    Route::delete('/{id}', [KategoriController::class, 'destroy']); // hapus data kategori
});

Route::group(['prefix' => 'barang'], function () {
    Route::get('/', [BarangController::class, 'index']);          // halaman awal barang
    Route::post('/list', [BarangController::class, 'list']);      // data barang dalam bentuk json untuk datatables
    Route::get('/create', [BarangController::class, 'create']);   // halaman form tambah barang
    Route::post('/', [BarangController::class, 'store']);         // simpan data barang baru
    Route::get('/{id}', [BarangController::class, 'show']);       // detail barang
    Route::get('/{id}/edit', [BarangController::class, 'edit']);  // halaman form edit barang
    Route::put('/{id}', [BarangController::class, 'update']);     // simpan perubahan data barang
    Route::delete('/{id}', [BarangController::class, 'destroy']); // hapus data barang
});

Route::group(['prefix' => 'stok'], function () {
    Route::get('/', [StokController::class, 'index']);          // halaman awal stok
    Route::post('/list', [StokController::class, 'list']);      // data stok dalam bentuk json untuk datatables
    Route::get('/create', [StokController::class, 'create']);   // halaman form tambah stok
    Route::post('/', [StokController::class, 'store']);         // simpan data stok baru
    Route::get('/{id}', [StokController::class, 'show']);       // detail stok
    Route::get('/{id}/edit', [StokController::class, 'edit']);  // halaman form edit stok
    Route::put('/{id}', [StokController::class, 'update']);     // simpan perubahan data stok
    Route::delete('/{id}', [StokController::class, 'destroy']); // hapus data stok
});

Route::group(['prefix' => 'penjualan'], function () {
    Route::get('/', [PenjualanController::class, 'index']);          // halaman awal penjualan
    Route::post('/list', [PenjualanController::class, 'list']);      // data penjualan dalam bentuk json untuk datatables
    Route::get('/create', [PenjualanController::class, 'create']);   // halaman form tambah penjualan
    Route::post('/', [PenjualanController::class, 'store']);         // simpan data penjualan baru
    Route::get('/{id}', [PenjualanController::class, 'show']);       // detail penjualan
    Route::get('/{id}/edit', [PenjualanController::class, 'edit']);  // halaman form edit penjualan
    Route::put('/{id}', [PenjualanController::class, 'update']);     // simpan perubahan data penjualan
    Route::delete('/{id}', [PenjualanController::class, 'destroy']); // hapus data penjualan
    Route::get('/{id}/print', [PenjualanController::class, 'print'])->name('penjualan.print');
    
    
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*Route::get('/level', [LevelController::class, 'index']);
Route::get('/level/tambah', [LevelController::class, 'tambah']);
Route::post('/level/tambah_simpan', [LevelController::class, 'tambah_simpan']);*/

/*Route::get('/user', [UserController::class, 'index']);
Route::get('/user/tambah', [UserController::class, 'tambah']);
Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
Route::get('/user/ubah/{id}', [UserController::class, 'ubah']);
Route::put('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
Route::get('/user/hapus/{id}', [UserController::class, 'hapus']); */

/*Route::get('/kategori', [KategoriController::class, 'index']);
Route::post('/kategori', [KategoriController::class, 'store']);
Route::get('/kategori/create', [KategoriController::class, 'create']);
Route::get('/kategori/edit/{id}', [KategoriController::class, 'edit'])->name('kategori.edit');
Route::put('/kategori/edit_save/{id}', [KategoriController::class, 'edit_save']);
Route::delete('/kategori/delete/{id}', [KategoriController::class, 'delete']);*/

Route::resource('m_user', POSController::class);