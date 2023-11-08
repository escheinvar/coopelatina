<?php

use App\Http\Controllers\admon\ListasAbastoController;
use App\Http\Controllers\admon\PagoPedidosController;
use App\Http\Controllers\admon\AbastoController;
use App\Http\Controllers\admon\EntregaController;
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
use App\Http\Controllers\coop_productoresController;
use App\Http\Controllers\coop_trabajosController;

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
Route::get('/nologin', function(){    return "<br><br><div style='text-align:center;font-size:150%;'>Debes ser Cooperativista<br>y <a href='/login'>autenticar</a> primero para poder <a href='/'>entrar</a> <br>  &#9773;  </div>";   });
Route::get('/noadmin', function(){    return "<br><br><div style='text-align:center;font-size:150%;'>Debes ser Cooperativista ADMINISTRADOR<br> y <a href='/login'>autenticar</a> primero para poder entrar<br> &#9773 <br> (<a href='/prepedido'>Ir a pre-pedidos</a>) </div>  ";    });
Route::get('/notrabajohoy', function(){    return "<br><div style='text-align:center;font-size:150%;'><br>Hola!!<br> Hoy no te toca trabajar!! <br> &#128512;<br> Solo el equipo de trabajo de hoy puede acceder a esta sección.<br> &#9773; <br><a href='/trabajos'>Ver calendario</a> </div>";    });
Route::get('/productores',[coop_productoresController::class, 'index'])->name('productores');

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
    Route::post('/pagoprepedidos', [PagoPedidosController::class, 'store']);

    ################################ Administración de Listas de abasto
    Route::get('/listasabasto', [ListasAbastoController::class, 'index'])->name('listasabasto');
    Route::post('/listasabasto', [ListasAbastoController::class, 'store']);

});

#######################################################################################
########################################## SOLO ADMINISTRADORES Y ENCARGADOS DE ENTREGA
Route::middleware(['soloCoops','soloAdmins'])->group(function(){
    ################################ Administración de Usuarios
    Route::get('/abastecer', [AbastoController::class, 'index'])->name('abastecer');
    Route::post('/abastecer', [AbastoController::class, 'store']);
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
    
    ################################ Ver Calendario 
    Route::get('/calendario',[coop_calendarController::class,'index'])->middleware('EstatusDeEntrega')->name('calendario');
    
    ################################ Ver Trabajos
    Route::get('/trabajos',[coop_trabajosController::class,'index'])->name('trabajos');
    
    ################################ Entrega de Productos
    Route::get('/entrega',[EntregaController::class,'index'])->middleware('EnTrabajo')->name('entregas');
    Route::post('/entrega',[EntregaController::class,'store']);
});