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

/********************** USUARIOS *************************/
// header('Access-Control-Allow-Origin:  *');
// header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
// header('Access-Control-Allow-Headers: *');

Route::group(['middleware' => ['guestaw']], function () {

	Route::any('/', 'UserController@actionLogin');
	Route::any('/login', 'UserController@actionLogin');
	Route::any('/acceso', 'UserController@actionAcceso');

});

Route::get('/cerrarsession', 'UserController@actionCerrarSesion');

Route::group(['middleware' => ['authaw']], function () {

	Route::get('/bienvenido', 'UserController@actionBienvenido');

	Route::any('/gestion-de-usuarios/{idopcion}', 'UserController@actionListarUsuarios');
	Route::any('/agregar-usuario/{idopcion}', 'UserController@actionAgregarUsuario');
	Route::any('/modificar-usuario/{idopcion}/{idusuario}', 'UserController@actionModificarUsuario');
	Route::any('/ajax-activar-perfiles', 'UserController@actionAjaxActivarPerfiles');

	Route::any('/gestion-de-roles/{idopcion}', 'UserController@actionListarRoles');
	Route::any('/agregar-rol/{idopcion}', 'UserController@actionAgregarRol');
	Route::any('/modificar-rol/{idopcion}/{idrol}', 'UserController@actionModificarRol');

	Route::any('/gestion-de-permisos/{idopcion}', 'UserController@actionListarPermisos');
	Route::any('/ajax-listado-de-opciones', 'UserController@actionAjaxListarOpciones');
	Route::any('/ajax-activar-permisos', 'UserController@actionAjaxActivarPermisos');

	Route::any('/gestion-de-clientes/{idopcion}', 'ConfiguarionController@actionListarClientes');
	Route::any('/agregar-clientes/{idopcion}', 'ConfiguarionController@actionAgregarClientes');
	Route::any('/modificar-clientes/{idopcion}/{idcliente}', 'ConfiguarionController@actionModificarCliente');

	Route::any('/gestion-de-pre-cotizacion/{idopcion}', 'PreCotizacionController@actionListarPrecotizaciones');
	Route::any('/agregar-precotizacion/{idopcion}', 'PreCotizacionController@actionAgregarPrecotizacion');
	Route::any('/modificar-precotizacion/{idopcion}/{idprecotizacion}', 'PreCotizacionController@actionModificarPrecotizacion');
	Route::any('/subir-imagenes-precotizacion/{idopcion}/{idprecotizacion}', 'PreCotizacionController@actionSubirImagenesPrecotizacion');
	Route::any('/emitir-precotizacion/{idopcion}', 'PreCotizacionController@actionEmitirPrecotizacion');

	Route::any('/gestion-de-cotizacion/{idopcion}', 'CotizacionController@actionListarcotizaciones');
	Route::any('/ajax-modal-configuracion-cotizacion-detalle', 'CotizacionController@actionConfigurarDetalle');
	Route::any('/cotizar-cotizacion/{idopcion}/{idcotizacion}', 'CotizacionController@actionCotizarcotizacion');
	Route::any('/ajax-modal-modificar-configuracion-cotizacion-detalle', 'CotizacionController@actionAjaxModalModificarConfiguracionCotizacion');
	Route::any('/ajax-analizar-detalle-cotizacion', 'CotizacionController@actionAnalizarDetalleCotizacion');
	Route::any('/ajax-agregar-producto-analisis', 'CotizacionController@actionAgregarProductoAnalisis');
	Route::any('/ajax-actualizar-tabla-cotizacion', 'CotizacionController@actionActulizarTablaCotizacion');
	Route::any('/ajax-eliminar-tabla-cotizacion-analisis', 'CotizacionController@actionEliminarTablaCotizacionAnalisis');






	Route::any('/gestion-de-grupo-servicio/{idopcion}', 'ConfiguarionController@actionListarGrupoServicio');
	Route::any('/agregar-grupo-servicio/{idopcion}', 'ConfiguarionController@actionAgregarGrupoServicio');
	Route::any('/modificar-grupo-servicio/{idopcion}/{idcategoria}', 'ConfiguarionController@actionModificarGrupoServicio');


	Route::any('/gestion-de-unidad-medida/{idopcion}', 'ConfiguarionController@actionListarUnidadMedida');
	Route::any('/agregar-unidad-medida/{idopcion}', 'ConfiguarionController@actionAgregarUnidadMedida');
	Route::any('/modificar-unidad-medida/{idopcion}/{idcategoria}', 'ConfiguarionController@actionModificarUnidadMedida');


	Route::any('/ajax-elimnar-linea-cotizacion', 'CotizacionController@actionAjaxEliminarLineaCotizacion');





});
