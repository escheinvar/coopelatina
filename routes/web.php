<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\admon\UsuariosController;
use App\Http\Controllers\coop_prepedidosController;
use App\Http\Controllers\admon\RecuperarPasswordController;
use App\Http\Controllers\admon\RecuperarPasswordController2;

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
############################# Página pública
Route::get('/', function(){    return view('publico.somos');  });


############################## Sistema accesos
Route::get ('/login', [LoginController::class, 'index'] )->name('login');    
Route::post('/login', [LoginController::class, 'store'] );


################################ Administración
Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios');
Route::post('/usuarios', [UsuariosController::class, 'store']);

Route::get('/recupera_password', [RecuperarPasswordController::class, 'index'])->name('recuperaPassword');
Route::post('/recupera_password', [RecuperarPasswordController::class, 'store']);

Route::get('/{tocken}/{usr}', [RecuperarPasswordController2::class, 'index'])->name('recuperaIngresaPassword');
Route::post('/{tocken}/{usr}', [RecuperarPasswordController2::class, 'store']);

################################ Acceso Cooperativistas
Route::get('prepedido',[coop_prepedidosController::class,'index'])->name('prepedido');
Route::post('prepedido',[coop_prepedidosController::class,'store']);