<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entrega;
use App\Repartidor;
use App\Pedido;
use App\User;
use App\Notificacion;
use Auth;

class EntregaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entregas = Entrega::All();
        return view('entrega.index', compact('entregas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Auth::user()->autorizarRol(['admin']);
        //return $request;
        $request->validate([
            "pedidos" => "required"
        ]);
        for ($i = 0; $i < count($request->pedidos); $i++) {
            $entrega = new Entrega;
            $entrega->repartidor_id = $request->repartidor_id;
            $entrega->pedido_id = $request->pedidos[$i];
            $entrega->estado = 1;
            $entrega->save();
        }

        return redirect('/admin/entrega/asignar');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Auth::user()->autorizarRol(['admin']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Auth::user()->autorizarRol(['admin']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Auth::user()->autorizarRol(['admin']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Auth::user()->autorizarRol(['admin']);
    }

    public function index_entregas()
    {
        Auth::user()->autorizarRol(['admin']);

        $fecha_actual = date('Y-m-d');
        //return Entrega::where('created_at', $fecha_actual)->get();
        $repartidores = Repartidor::All();

        return view('entrega.index', compact('repartidores'));
    }

    public function asignar_rutas($repartidor_id)
    {
        Auth::user()->autorizarRol(['admin']);

        $usuarios = User::All();
        $repartidor = Repartidor::find($repartidor_id);
        //$pedidos = Pedido::get('id');
        $entregas = Entrega::get('pedido_id');
        $pedidos = Pedido::whereNotIn('id', $entregas)->where('activo', 1)->whereDate('fecha_limite', '>', date('Y-m-d'))->get();

        return view('entrega.asignar_rutas', compact('repartidor', 'usuarios', 'pedidos'));
    }

    public function pedidos_rutas_asignados(Request $request, $repartidor_id)
    {
        Auth::user()->autorizarRol(['admin']);
        if ($request->fecha) {
            $f = $request->fecha;
        } else {
            $f = date('Y-m-d');
        }

        $repartidor = Repartidor::find($repartidor_id);
        //return $repartidor->user;
        $mensajes = Notificacion::where('user_id', $repartidor->user->id)->orderby('id', 'desc')->get()->take(6);

        $entregas = Entrega::where('repartidor_id', $repartidor->id)->where('created_at', 'like', '%' . $f . '%')->get()->take(12);

        //return Repartidor::where('user_id', $user->id)->first();
        //$entregas = $repartidor->entregas;
        return view('entrega.pedidos_rutas_asignados', compact('repartidor', 'entregas', 'f', 'mensajes'));
    }
}
