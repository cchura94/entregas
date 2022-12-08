<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
    //return view('welcome');
});
Route::get('admin', 'HomeController@index')->name('home');

Route::get('admin/tienda/{id}/sucursal', 'TiendaController@agregar_sucursal')->name('add_sucursal');
Route::post('admin/tienda/{id}/sucursal', 'TiendaController@add_sucursal')->name('add_suc');
Route::get('admin/tienda/mapa', 'TiendaController@mapa')->name('mapa');


Route::get('admin/entrega/asignar', 'EntregaController@index_entregas')->name('asignar_ruta');
Route::get('admin/entrega/asignar/{id}', 'EntregaController@asignar_rutas')->name('asignar_rutas');
Route::get('admin/entrega/pedidos_rutas_asignados/{id}', 'EntregaController@pedidos_rutas_asignados')->name('pedidos_rutas_asignados');

//ver ubicaciones 
Route::get('/admin/entrega/ver_ubicaciones/{id}', 'EntregaController@ver_ubicaciones')->name('ver_ubicaciones');
//Rutas asignada a repartidor
//Route::get('admin/repartidor/rutas/', 'RepartidorController@rutas_asignadas')->name('rutas_asignadas');
Route::get('/mis-entregas', 'RepartidorController@rutas_asignadas')->name('rutas_asignadas');

//Route::get('admin/repartidor/rutas/{id}', 'RepartidorController@ver_ruta_asignada')->name('ver_ruta_asignada');
Route::get('/mis-entregas/{id}', 'RepartidorController@ver_ruta_asignada')->name('ver_ruta_asignada');

//Route::put('admin/repartidor/rutas/{id}', 'RepartidorController@registrar_entrega_ruta')->name('registrar_entrega_ruta');
Route::put('/mis-entregas/{id}', 'RepartidorController@registrar_entrega_ruta')->name('registrar_entrega_ruta');

//Registro de usuarios
Route::post('admin/registro', 'UsuarioController@create_usuario')->name('create_usuario');


//enviar ubicación
//Route::post()
//Route::get('/', 'MessageController@index');
Route::get('/notificacion', 'NotificacionController@fetch')->middleware('auth');
Route::post('/notificacion', 'NotificacionController@sentMessage')->middleware('auth');

Route::get('/send_ubicacion', 'RepartidorController@enviar_ubicacion')->name('enviar_ubicacion');

Route::get('/admin/pedido/buscador','PedidoController@buscador')->name('busqueda_pedido');

//PDF
Route::get('/admin/tienda/reporte', 'TiendaController@reporte_tiendas')->name('reporte_tiendas');
Route::get('/admin/tienda/{id}/reporte_sucursales', 'TiendaController@reporte_sucursales')->name('reporte_sucursales');

Route::get('/admin/entrega/reporte', 'ReporteController@reporte_entrega')->name('reporte_entrega');

Route::get('/admin/repartidor/reporte', 'RepartidorController@reporte_repartidores')->name('reporte_repartidores');
Route::get('/admin/pedido/reporte_pedidos', 'PedidoController@reporte_pedidos')->name('reporte_pedidos');
//EXCEL

Route::get('/admin/tienda/exportar_excel', 'TiendaController@exportar_excel')->name('exportar_tiendas_excel');
Route::get('/admin/tienda/{id}/exportar_sucursales_excel', 'TiendaController@exportar_sucursales_excel')->name('exportar_sucursales_excel');

//cancelar pedido

Route::get('/admin/pedido/{id}/cancelar_pedido', 'PedidoController@cancelar_pedido')->name('cancelar_pedido');

Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::resource('tienda', 'TiendaController');
    Route::resource('sucursal', 'SucursalController');
    Route::resource('repartidor', 'RepartidorController');
    Route::resource('pedido', 'PedidoController');
    Route::resource('entrega', 'EntregaController');
    
});

Auth::routes(['register' => false]);

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');

