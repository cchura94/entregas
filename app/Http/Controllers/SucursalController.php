<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Sucursal;

class SucursalController extends Controller
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
        //
        Auth::user()->autorizarRol(['admin']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        Auth::user()->autorizarRol(['admin']);
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
        $sucursal = Sucursal::find($id);
        return view('sucursal.show', compact('sucursal'));
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
        $sucursal = Sucursal::find($id);
        return view('sucursal.edit', compact('sucursal'));
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

        $request->validate([
            'nombre' => 'required|max:250',
            'direccion' => 'required|max:200',
            'longitud' => 'required|max:25',
            'latitud' => 'required|max:25',
            'nombre_c' => 'required|max:200',
            'telefono_c' => 'required|max:15',
        ]);

        $sucursal = Sucursal::find($id);
        $sucursal->nombre = $request->nombre;
        $sucursal->descripcion = $request->descripcion;
        $sucursal->direccion = $request->direccion;
        $sucursal->latitud = $request->latitud;
        $sucursal->longitud = $request->longitud;
        $sucursal->nombre_c = $request->nombre_c;
        $sucursal->telefono_c = $request->telefono_c;
        $sucursal->save();

        return redirect('/admin/tienda');
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
        $sucursal = Sucursal::find($id);
        $sucursal->delete();

        return redirect()->back();

    }
}
