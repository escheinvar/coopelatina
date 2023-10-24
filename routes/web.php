<?php

use App\Http\Controllers\admon\PagoPedidosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\admon\UsuariosController;
use App\Http\Controllers\coop_prepedidosController;
use App\Http\Controllers\coop_homeController;
use App\Http\Controllers\admon\RecuperarPasswordController;
use App\Http\Controllers\admon\RecuperarPasswordController2;
use App\Http\Controllers\coop_calendarController;
use App\Http\Controllers\coop_misPedidos;

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

#######################################################################################
########################################################################## ZONA PÚBLICA
Route::get('/', function(){    return view('publico.somos');  });
Route::get('/nologin', function(){    return "Debes ser Cooperativista y <a href='/login'>autenticar</a> primero para poder entrar<br><a href='/'>A inicio</a> ";   });
Route::get('/noadmin', function(){    return "Debes ser Cooperativista ADMINISTRADOR y <a href='/login'>autenticar</a> primero para poder entrar<br><a href='/prepedido'>A PREPEDIDO</a> ";    });


#######################################################################################
###################################################################   SISTEMA DE ACCESO
Route::get ('/login', [LoginController::class, 'index'] )->name('login');    
Route::post('/login', [LoginController::class, 'store'] );

Route::post('/fin',[LogoutController::class, 'store'])->name('logout');
    
Route::get('/recupera_password', [RecuperarPasswordController::class, 'index'])->name('recuperaPassword');
Route::post('/recupera_password', [RecuperarPasswordController::class, 'store']);

Route::get('/recupera_pass/{tocken}/{usr}', [RecuperarPasswordController2::class, 'index'])->name('recuperaIngresaPassword');
Route::post('/recupera_pass/{tocken}/{usr}', [RecuperarPasswordController2::class, 'store']);


#######################################################################################
################################################################## SOLO ADMINISTRADORES

Route::middleware(['soloCoops','soloAdmins'])->group(function(){
    ################################ Administración de Usuarios
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios');
    Route::post('/usuarios', [UsuariosController::class, 'store']);

    ################################ Administración de Pago de prepedidos
    Route::get('/pagoprepedidos', [PagoPedidosController::class, 'index'])->name('pagoprepedidos');

});


#######################################################################################
################################################################## SOLO COOPERATIVISTAS

Route::middleware(['soloCoops'])->group(function(){
    ################################ Home
    Route::get('/inicio/{usr}',[coop_homeController::class,'index'])->middleware('EstatusDeEntrega')->name('home');
    Route::post('/inicio/{usr}',[coop_homeController::class,'store']);

    ################################ Prepedido de cooperativistas
    Route::get('/prepedido',[coop_prepedidosController::class,'index'])->name('prepedido');
    Route::post('/prepedido',[coop_prepedidosController::class,'store']);

    ################################ MisPedidos
    Route::get('/MisPedidos/{usr}',[coop_misPedidos::class,'index'])->name('MisPedidos');
#Route::post('/MisPedidos/{usr}',[coop_misPedidos::class,'store']);

    ################################ Ver Calendario 
    Route::get('/calendario',[coop_calendarController::class,'index'])->middleware('EstatusDeEntrega')->name('calendario');
    #Route::post('/calendario',[coop_calendarController::class,'store']);
});