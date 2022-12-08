<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tienda;
use App\Sucursal;
use Auth;
use PDF;
use DateTime;

use App\Exports\TiendaExport;
use App\Exports\SucursalExport;
use Maatwebsite\Excel\Facades\Excel;

class TiendaController extends Controller
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
        $tiendas = Tienda::All(); //ORM Eloquent de laravel, select * from tiendas
        return view('tienda.index', compact('tiendas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->autorizarRol(['admin']);
        return view('tienda.create');
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
            'nombre' => 'required|min:3|max:100',
        ]);
        
        $nombre_imagen = '';
        if($file = $request->file('logo')){
            //obtenemos el nombre del archivo
             $nombre_imagen = $file->getClientOriginalName();
             $file->move('logos', $nombre_imagen); 
        }

        $tienda = new Tienda;
        $tienda->nombre = $request->nombre;
        $tienda->descripcion = $request->descripcion;
        $tienda->logo = $nombre_imagen;
        $tienda->user_id = Auth::user()->id;       

        $tienda->save();

        return redirect('/admin/tienda')->with("ok", "Nuevo Cliente Registrado");
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
        $tienda = Tienda::find($id);
        return view('tienda.show', compact('tienda'));
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
        $tienda = Tienda::find($id);
        return view('tienda.edit', compact('tienda'));
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
            'nombre' => 'required|min:3|max:100',
        ]);

        $tienda = Tienda::find($id);
        $tienda->nombre = $request->nombre;
        $tienda->descripcion = $request->descripcion;
        $tienda->logo = $request->logo;
        $tienda->user_id = Auth::user()->id;
        $tienda->save();

        return redirect('/admin/tienda')->with("ok", "Cliente Actualizado");;
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
        $tienda = Tienda::find($id);
        $tienda->sucursales->each->delete();
        $tienda->delete();

        return redirect('/admin/tienda')->with("ok", "Cliente Eliminado");;
    }


    public function agregar_sucursal($id)
    {
        Auth::user()->autorizarRol(['admin']);
        $tienda = Tienda::find($id);
        return view('tienda.add_sucursal', compact('tienda'));
    }

    public function add_sucursal(Request $request, $id)
    {
        Auth::user()->autorizarRol(['admin']);

        $request->validate([
            'nombre' => 'required|max:250',
            'direccion' => 'required|max:250',
            'longitud' => 'required|max:25',
            'latitud' => 'required|max:25',
            'nombre_c' => 'required|max:200',
            'telefono_c' => 'required|max:17',
        ]);

        $suc = new Sucursal;
        $suc->nombre = $request->nombre;
        $suc->descripcion = $request->descripcion;
        $suc->direccion = $request->direccion;
        $suc->latitud = $request->latitud;
        $suc->longitud = $request->longitud;
        $suc->telefono_c = $request->telefono_c;
        $suc->nombre_c = $request->nombre_c;
        $suc->tienda_id = $request->tienda_id;
        $suc->save();

        return redirect("admin/tienda/$id")->with("ok", "Sucursal Agregada");
    }

    public function mapa()
    {
        $tiendas = Tienda::All();

        $sucursales = Sucursal::All();
        foreach($sucursales as $suc){
            $suc->tienda;
        }

        return view('tienda.map', compact('sucursales', 'tiendas'));
    }

    public function reporte_tiendas(Request $request)
    {
        //Auth::user()->autorizarRol(['admin']);

        $tiendas = Tienda::All();

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
        PDF::Cell(0, 15,'REPORTE LISTADO DE CLIENTES',0,1,'C');		
        	
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
    <th width="100px"><b>COD.</b></th>
    <th width="200px"><b>NOMBRE</b></th>
    <th width="250px"><b>DESCRIPCION</b></th>
    </tr>
    </thead>
    <tbody>';
    foreach($tiendas as $t){

            $html.='<tr>
            <td width="100px">'. $t->id .'</td>
            <td width="200px">'. $t->nombre .'</td>
            <td width="250px">'. $t->descripcion .'</td>
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
		//PDF::write2DBarcode('SEGINCO', 'QRCODE,H', 180, 45, 30, 30, $style, 'N');

		//PDF::Text(20, 205, 'Codigo de verificación');

		PDF::Output('listado de clientes.pdf');
        //exit;
    }



    public function reporte_sucursales($id)
    {
        Auth::user()->autorizarRol(['admin']);
        $tienda = Tienda::find($id);

        //Header y footer logos

        
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

        //Datos

        

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
        PDF::Cell(0, 15,'INFORMACIÓN SUCURSALES DE '. strtoupper($tienda->nombre), 0,1,'C');		
        	
        PDF::Line(50,54,165,54);
        PDF::SetDrawColor(0,0,255);	
        PDF::Line(6, 30, 210, 30);
        PDF::Line(6, 80, 210, 80);
                
        PDF::lastPage();
        //PDF::Line(6,80,210,80);
            
        //PDF::Image('logo/logo.jpg',10,8,0,20, '', '', '', false, 300, '', false, false, 0);
        //PDF::Image('logos/'. $tienda->logo,180,45,30,30);

        PDF::SetX(10);//inicio posicion del contenido
		PDF::SetY(55);//inicio posicion del contenido
		//PDF::writeHTML($html, true, false, true, false, '');
		PDF::SetFont('courier', 'B', 12);
		//PDF::Cell(0, 5,'DATOS PERSONALES',0,1,'L');
        PDF::SetFont('courier', 'B', 10);
        PDF::SetTextColor(0,0,0);
        
		PDF::Cell(0, 10,'Gestion: '. (date('Y')),0,1,'L');
        //PDF::Cell(0, 5,'Fecha Actual: '. (date('d-m-Y H:i:s')),0,1,'L');
        
        PDF::Cell(0, 10,'NOMBRE: '.strtoupper($tienda->nombre),0,1,'L');
        PDF::Cell(0, 5,'DESCRIPCION: '.$tienda->descripcion, 0,1,'L');
        PDF::Cell(0, 5,'',0,1,'L');
        
        //PDF::SetXY(10, 80);
    $html='<table width="100%" border="1" style="padding:3px;">
    <thead>
    <tr>
    <th width="40px"><b>COD.</b></th>
    <th width="130px">NOMBRE</th>
    <th width="150px">DIRECCION</th>
    <th width="80px">TELEFONO</th>
    <th width="150px">NOM. CONTACTO</th>
    </tr>
    </thead>
    <tbody>';
    foreach($tienda->sucursales as $suc){

            $html.='<tr>
            <td width="40px">'. $suc->id .'</td>
            <td width="130px">'. $suc->nombre. '</td>
            <td width="150px">'. $suc->direccion. '</td>
            <td width="80px">'. $suc->telefono_c. '</td>
            <td width="150px">'. $suc->nombre_c. '</td>
            </tr>';            
    }
         

    $html.='</tbody></table>';


    PDF::writeHTML($html, true, false, true, false, '');
    //PDF::writeHTML('<img src="/logo/logo.jpg">', 100,100,100,100);

	    //PDF::Cell(0, 5,'La Paz: ' ,0,1,'L');
		//PDF::lastPage();
        //PDF::Output('my_file.pdf', 'D');
        
        

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
        //PDF::write2DBarcode('SEGINCO', 'QRCODE,H', 180, 45, 30, 30, $style, 'N');
        //PDF::Image('logos/'. $tienda->logo,180,45,30,30);

		//PDF::Text(20, 205, 'Codigo de verificación');

		PDF::Output('listado de clientes.pdf');
        //exit;
    }

    public function exportar_excel()
    {
        return Excel::download(new TiendaExport, 'tiendas.xlsx');
    }

    public function exportar_sucursales_excel($id)
    {
        $tienda = Tienda::find($id);
        return Excel::download(new SucursalExport($tienda), 'sucursales-'.$tienda->nombre.'.xlsx');
    }
}
