<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repartidor;
use App\Entrega;
use App\User;
use App\Role;
use Auth;
use PDF;
use DateTime;

class RepartidorController extends Controller
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
        Auth::user()->autorizarRol(['admin']);

        $repartidores = Repartidor::All();
        return view('repartidor.index', compact('repartidores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->autorizarRol(['admin']);

        $usuarios = User::All();
        return view('repartidor.create', compact('usuarios'));
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

        $request->validate([
            'nombre' => 'required|max:250',
            'paterno' => 'required|max:200',
            'ci' => 'required|max:12',
            'direccion' => 'required|max:200',
            'telefono' => 'required|max:200',
            'email' => 'required|max:200',
            'password' => 'required|max:200',
        ]);

        //Creando cuenta de usuario
        $repartidor = Role::where('nombre', 'repartidor')->first();

        $user = new User;
        $user->name = $request->paterno;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $user->roles()->attach($repartidor);

        $rep = new Repartidor;
        $rep->nombre = $request->nombre;
        $rep->paterno = $request->paterno;
        $rep->materno = $request->materno;
        $rep->ci = $request->ci;
        $rep->direccion = $request->direccion;
        $rep->telefono = $request->telefono;
        $rep->user_id = $user->id;
        $rep->save();
        return redirect('/admin/repartidor');
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

        $usuarios = User::All();
        $repartidor = Repartidor::find($id);
        return view('repartidor.show', compact('repartidor'))->with('usuarios', $usuarios);
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

        $usuarios = User::All();
        $repartidor = Repartidor::find($id);
        return view('repartidor.edit', compact('repartidor'))->with('usuarios', $usuarios);
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
            'paterno' => 'required|max:200',
            'ci' => 'required|max:12',
            'direccion' => 'required|max:200',
            'telefono' => 'required|max:200',
            'user_id' => 'required|max:200',
        ]);

        $rep = Repartidor::find($id);
        $rep->nombre = $request->nombre;
        $rep->paterno = $request->paterno;
        $rep->materno = $request->materno;
        $rep->ci = $request->ci;
        $rep->direccion = $request->direccion;
        $rep->telefono = $request->telefono;
        $rep->user_id = $request->user_id;
        $rep->save();

        if($request->cambiar_usuario){
            $rep->user->email = $request->email;
            $rep->user->password = bcrypt($request->password);
            $rep->user->save();
        }
        return redirect('/admin/repartidor');
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

        $repartidor = Repartidor::find($id);
        $repartidor->delete();
        return redirect('/admin/repartidor');
    }

     public function rutas_asignadas()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if($user->repartidor){
                $repartidor = $user->repartidor;
                //return Repartidor::where('user_id', $user->id)->first();
                $entregas = $repartidor->entregas;
                return view('repartidor.rutas_repartidor', compact('entregas'));                
            }else{
                return redirect('/admin');
            }
        }else{
            return redirect('/admin');
            //$role->usuario_creacion = -1;
        }
    }

    public function ver_ruta_asignada($id)
    {
        $user = Auth::user();
        if($user->roles[0]->nombre == "admin"){
            return redirect('/admin/entrega/asignar');
        }else{
            $entrega = Entrega::where('repartidor_id', $user->repartidor->id)->where('id', $id)->first();
            if($entrega){
                return view('repartidor.ver_ruta', compact('entrega'));
            }else{
                return redirect('/admin');
            }
        }       
        
    }

    public function registrar_entrega_ruta(Request $request, $id)
    {
        $entrega = Entrega::find($id);
        $entrega->latitud_r = $request->latitud_r;
        $entrega->longitud_r = $request->longitud_r;
        $entrega->estado = $request->estado;
        $entrega->descripcion = $request->descripcion;
        $entrega->save();
        return redirect('/mis-entregas')->with('ok', 'Se ha registrado la Entrega');
    }

    public function enviar_ubicacion()
    {
        return view('repartidor.enviar_ubicacion');
    }

    //-----------REPORTES REPARTIDOR------------------//
    public function reporte_repartidores(Request $request)
    {
        Auth::user()->autorizarRol(['admin']);

        $repartidores = Repartidor::All();

        PDF::SetFontSubsetting(false);
		PDF::SetFontSize('10px');
		PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		PDF::AddPage('P', 'LETTER');
		PDF::SetX(10);//inicio posicion del contenido
		PDF::SetY(35);//inicio posicion del contenido

		PDF::SetFont('courier', 'B', 10);
		PDF::Cell(0, 1,'Sistema de Informacón y monitoreo para la Distribución y Entrega de Pedidos',0,1,'C');	
		PDF::Cell(0, 2,'"SEGINCO"',0,1,'C');	
        PDF::SetFont('courier', 'B', 15);
        //PDF::SetTextColor(0,0,255);
        PDF::Cell(0, 15,'REPORTE LISTADO DE REPARTIDORES',0,1,'C');		
        	
        PDF::Line(50,54,165,54);
        PDF::SetDrawColor(0,0,255);	
        PDF::Line(6,30,210,30);
        PDF::Line(6,72,210,72);
                
        
        //PDF::Line(6,80,210,80);
            
        //PDF::Image('logo/logo.jpg',10,8,0,20, '', '', '', false, 300, '', false, false, 0);
        

	    PDF::SetFontSubsetting(false);
		PDF::SetFontSize('10px');
		PDF::SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		//PDF::AddPage('P', 'LETTER');
		PDF::SetX(10);//inicio posicion del contenido
		PDF::SetY(55);//inicio posicion del contenido
		//PDF::writeHTML($html, true, false, true, false, '');
		PDF::SetFont('courier', 'B', 12);
		//PDF::Cell(0, 5,'DATOS PERSONALES',0,1,'L');
        PDF::SetFont('courier', 'B', 10);
        PDF::SetTextColor(0,0,0);
		PDF::Cell(0, 10,'Gestion: '. (date('Y')),0,1,'L');
		PDF::Cell(0, 5,'Fecha Actual: '. (date('d-m-Y H:i:s')),0,1,'L');
        
        PDF::SetXY(10, 80);
    $html='<table width="100%" border="1" style="padding:3px;">
    <thead>
    <tr>
    <th width="40px"><b>COD.</b></th>
    <th width="90px"><b>NOMBRE</b></th>
    <th width="90px"><b>AP. PATERNO</b></th>
    <th width="90px"><b>AP. MATERNO</b></th>
    <th width="60px"><b>C.I.</b></th>
    <th width="120px"><b>DIRECCION</b></th>
    <th width="70px"><b>TELEFONO</b></th>
    </tr>
    </thead>
    <tbody>';
    foreach($repartidores as $r){

            $html.='<tr>
            <td width="40px">'. $r->id .'</td>
            <td width="90px">'. $r->nombre .'</td>
            <td width="90px">'. $r->paterno .'</td>
            <td width="90px">'. $r->materno .'</td>
            <td width="60px">'. $r->ci .'</td>
            <td width="120px">'. $r->direccion .'</td>
            <td width="70px">'. $r->telefono .'</td>
            </tr>';
    }
         

    $html.='</tbody></table>';


    PDF::writeHTML($html, true, false, true, false, '');
    //PDF::writeHTML('<img src="/logo/logo.jpg">', 100,100,100,100);

	    //PDF::Cell(0, 5,'La Paz: ' ,0,1,'L');
		PDF::lastPage();
        //PDF::Output('my_file.pdf', 'D');
        
        PDF::setFooterCallback(function($pdf){

            PDF::Image('logo.jpg',10,8,50,20);
            PDF::Image('fondo.jpg',160,8,50,20);
			$pdf->SetY(-15);
			$pdf->SetFont('courier', 'I', 7);
		    /* establecemos el color del texto */
          	$pdf->SetTextColor(0,0,0);
            $pdf->SetX(10);
            $pdf->Cell(0, 10, ''.date('d-m-Y H:i:s').'',
                             0, false, 'L', 0, '', 0, false, 'T', 'M');

            $pdf->SetFont('courier', 'I', 10);
            $pdf->Cell(0, 10, 'Pag. '.$pdf->getAliasNumPage().
                             ' de '.
                             $pdf-> getAliasNbPages(),
                             0, false, 'R', 0, '', 0, false, 'T', 'M');

            $pdf->SetFont('courier', 'B', 6);
            $pdf->SetXY(10,262);
            //$pdf->Cell(0, 5, "[0, false, 'R', 0, '', 0, false, 'T', 'M');

            $pdf->SetDrawColor(0,0,255);
            /* dibujamos una linea roja delimitadora del pie de página */
          	$pdf->Line(10,266,205,266);

        });
        

		$style = array(
		    'border' => false,//borde 2
		    'vpadding' => 'auto',
		    'hpadding' => 'auto',
		    'fgcolor' => array(0,0,0),
		    'bgcolor' => false, //array(255,255,255)
		    'module_width' => 1, // width of a single module in points
		    'module_height' => 1 // height of a single module in points
		);
		PDF::SetFont('courier', 'B', 10);
		PDF::Ln();
		PDF::write2DBarcode('SEGINCO', 'QRCODE,H', 180, 45, 30, 30, $style, 'N');

		//PDF::Text(20, 205, 'Codigo de verificación');

		PDF::Output('listado de clientes.pdf');
        //exit;
    }
}
