<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\Sucursal;
use App\Entrega;
use Auth;
use PDF;
use DateTime;
use DB;

class PedidoController extends Controller
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
        //return date('Y-m-d');
        Auth::user()->autorizarRol(['admin']);

        $estado = 5;        

        //Pedidos no entregados
        $entregas = Entrega::get('pedido_id');
        $pedidos_pendientes = Pedido::get();
        
        /*
        //Pedidos que han sido entregados
        $entregas = Entrega::get('pedido_id');
        $pedidos_entregados = Pedido::whereIn('id', $entregas)->get();*/

        //Logica cambiar el estado de los demorados
        //Logica de demorados
        $entregas = Entrega::get('pedido_id');
        //demorados que estan en proceso de entrega
        $demorados_enproceso = Pedido::whereIn('id', $entregas)->whereDate('fecha_limite', '<', date('Y-m-d'))->where('activo', 1)->get('id');
        if($demorados_enproceso){
            $entregas = Entrega::whereIn('pedido_id', $demorados_enproceso)->get();            
            foreach($entregas as $ent){
                $ent->estado = 3;
                $ent->save();
            }
        } 


        //$pedidos = Entrega::All();
        return view('pedido.index', compact('pedidos_pendientes', 'estado'));
        //return view('pedido.index', compact('pedidos', 'pedidos_entregados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::user()->autorizarRol(['admin']);

        $ultimo_pedido = Pedido::latest('id')->first();
        
        
        $sucursales = Sucursal::All();
        return view('pedido.create', compact('sucursales', 'ultimo_pedido'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        Auth::user()->autorizarRol(['admin']);
        $request->validate([
            'cod_pedido' => 'required|unique:pedidos|max:250',
            'fecha_limite' => 'required|max:200',
            'fecha_pedido' => 'required|max:200',
            'sucursal_id' => 'required|max:200',
        ]);

        $ped = new Pedido;
        $ped->cod_pedido = $request->cod_pedido;
        $ped->descripcion = $request->descripcion;
        $ped->fecha_pedido = $request->fecha_pedido;
        $ped->fecha_limite = $request->fecha_limite;
        $ped->sucursal_id = $request->sucursal_id;
        $ped->save();
        return redirect('/admin/pedido');        
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

        $sucursales = Sucursal::All();
        $pedido = Pedido::find($id);
        return view('pedido.show', compact('pedido'))->with('sucursales', $sucursales);
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

        $sucursales = Sucursal::All();
        $pedido = Pedido::find($id);
        return view('pedido.edit', compact('pedido'))->with('sucursales', $sucursales);
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
            'cod_pedido' => 'required|max:250',
            'fecha_limite' => 'required|max:200',
            'fecha_pedido' => 'required|max:200',
            'sucursal_id' => 'required|max:200',
        ]);

        $ped = Pedido::find($id);
        $ped->cod_pedido = $request->cod_pedido;
        $ped->descripcion = $request->descripcion;
        $ped->fecha_pedido = $request->fecha_pedido;
        $ped->fecha_limite = $request->fecha_limite;
        $ped->sucursal_id = $request->sucursal_id;
        $ped->save();
        return redirect('/admin/pedido');
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

        $pedido = Pedido::find($id);
        $pedido->delete();

        return redirect('/admin/pedido');
    }

    public function cancelar_pedido($id)
    {
        Auth::user()->autorizarRol(['admin']);

        $pedido = Pedido::find($id);
        $pedido->activo = 0;
        $pedido->save();
        
        $en_proceso = Entrega::where('pedido_id', $pedido->id)->first();
        if($en_proceso){
            $en_proceso->estado = 4;
            $en_proceso->save();
        }
        return redirect('/admin/pedido');
    }

    public function buscador(Request $request)      
    {
        //return $request;
        $pedidos = [];
        $pedidos_pendientes = [];
        $estado=$request->estado;
        if($estado==0)
        {   
            $entregas = Entrega::get('pedido_id');
            $pedidos_pendientes = Pedido::whereNotIn('id', $entregas)->where('activo', 1)->whereDate('fecha_limite', '>=', date('Y-m-d'))->get();
        }else if($estado==1){ //EN PROCESO DE ENTREGA (ya signado a un repartidor) 
            $ped_cancelados =  Pedido::where('activo', 0)->get('id');          
            $pedidos = Entrega::whereNotIn('pedido_id', $ped_cancelados)->where('estado',$estado)->get();
        }else if($estado==2){
            $pedidos = Entrega::where('estado',$estado)->get();           
            
        }else if($estado==3){ //DEMORADO
            //Logica de demorados
            $entregas = Entrega::get('pedido_id');
            //$pedidos_pendientes = Pedido::whereNotIn('id', $entregas)->whereDate('fecha_limite', '<', date('Y-m-d'))->where('activo', 1)->get();
            $pedidos_pendientes = Pedido::whereDate('fecha_limite', '<', date('Y-m-d'))->where('activo', 1)->get();
            
            //demorados que estan en proceso de entrega
            $demorados_enproceso = Pedido::whereIn('id', $entregas)->whereDate('fecha_limite', '<', date('Y-m-d'))->where('activo', 1)->get('id');
            if($demorados_enproceso){
                $entregas = Entrega::whereIn('pedido_id', $demorados_enproceso)->get();            
                foreach($entregas as $ent){
                    $ent->estado = 3;
                    $ent->save();
                }
            } 
        }else if($estado==4){ //CANCELADO
            //Logica de cancelados
            $pedidos_pendientes = Pedido::where('activo', 0)->get();
        }else if($estado==5){ //VER TODO
            if($request->fecha_pedido){
                $pedidos = []; 
                $entregas = Entrega::get('pedido_id');
                $pedidos_pendientes = Pedido::whereNotIn('id', $entregas)->orwhere('activo', 0)->where('fecha_pedido', $request->fecha_pedido)->get();                
                return view('pedido.index', compact('pedidos', 'pedidos_pendientes', 'estado'));

            }else{
                return redirect("/admin/pedido");
            }
            
        }      
        return view('pedido.index', compact('pedidos', 'pedidos_pendientes', 'estado'));
    
    }
    public function reporte_pedidos(Request $request)
    {
        Auth::user()->autorizarRol(['admin']);
        $pedido = Pedido::All();
        $entregas = Entrega::All();

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
        PDF::Cell(0, 15,'REPORTE PEDIDOS', 0,1,'C');		
        	
        PDF::Line(50,54,165,54);
        PDF::SetDrawColor(0,0,255);	
        PDF::Line(6, 30, 210, 30);
        PDF::Line(6, 85, 210, 85);
                
        
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
        PDF::Cell(0, 5,'Fecha Generado: '. (date('d-m-Y H:i:s')),0,1,'L');
        $pedidos = [];
        $pedidos_pendientes = [];
        $estado=$request->estado;
        if($estado==0){
            PDF::Cell(0, 10,'ESTADO: '.strtoupper('PENDIENTE'),0,1,'L');
            PDF::Cell(0, 5,'DESCRIPCION:'.strtoupper('EL PEDIDO AUN NO SE A ASIGNADO'), 0,1,'L');
        }
        elseif($estado==1){
            PDF::Cell(0, 10,'ESTADO: '.strtoupper('EN PROCESO'),0,1,'L');
            PDF::Cell(0, 5,'DESCRIPCION:'.strtoupper('EL PEDIDO SE A ASIGNADO Y ESTA EN PROCVESO DE ENTREGA'), 0,1,'L');
        }
        elseif($estado==2){
            PDF::Cell(0, 10,'ESTADO: '.strtoupper('ENTREGADO'),0,1,'L');
            PDF::Cell(0, 5,'DESCRIPCION:'.strtoupper('EL PEDIDO HA SIDO ENTREGADO SATISFACTORIAMENTE'), 0,1,'L');
        }
        elseif($estado==3){
            PDF::Cell(0, 10,'ESTADO: '.strtoupper('DEMORADO'),0,1,'L');
            PDF::Cell(0, 5,'DESCRIPCION:'.strtoupper('EL PEDIDO NO HA SIDO ENTREGADO Y HA PASADO DE SU FECHA LIMITE DE ENTREGA'), 0,1,'L');
        }
        elseif($estado==4){
            PDF::Cell(0, 10,'ESTADO: '.strtoupper('CANCELADO'),0,1,'L');
            PDF::Cell(0, 5,'DESCRIPCION:'.strtoupper('EL PEDIDO HA SIDO CANCELADO PARA SU ENTREGA'), 0,1,'L');
        }else{
            PDF::Cell(0, 10,'ESTADO: '.strtoupper('TODOS'),0,1,'L');
            PDF::Cell(0, 5,'DESCRIPCION:'.strtoupper('VISTA GENERAL DE TODOS LOS PEDIDOS'), 0,1,'L');
        }
        
        PDF::Cell(0, 5,'',0,1,'L');

        //return $request;
        
        if($estado==0)
        {   
            $entregas = Entrega::get('pedido_id');
            $pedidos_pendientes = Pedido::whereNotIn('id', $entregas)->where('activo', 1)->whereDate('fecha_limite', '>=', date('Y-m-d'))->get();
            $html='<table width="100%" border="1" style="padding:3px;">
                <thead>
                <tr>
                <th width="40px"><b>COD</b></th>
                <th width="121px">FECHA PEDIDO</th>
                <th width="121px">FECHA LIMITE</th>
                <th width="180px">SUCURSAL</th>
                <th width="70px">ESTADO</th>
                </tr>
                </thead>
                <tbody>';
            foreach($pedidos_pendientes as $ped){
                if($ped->sucursal && $ped->sucursal->tienda){ //SI existe la sucursal y no se elimino
                    $html.='<tr>
                    <td width="40px">'. $ped->cod_pedido .'</td>               
                    <td width="121px">'. $ped->fecha_pedido. '</td>
                    <td width="121px">'. $ped->fecha_limite. '</td>
                    <td width="180px">'. $ped->sucursal->nombre .' - '.$ped->sucursal->tienda->nombre. '</td>
                    <td width="70px">Pendiente</td>
                    </tr>';
                }
                
            }
        }else if($estado==1){
            $ped_cancelados =  Pedido::where('activo', 0)->get('id');          
            $pedidos = Entrega::whereNotIn('pedido_id', $ped_cancelados)->where('estado',$estado)->get();
            //$pedidos = Entrega::where('estado',$estado)->get();
            $html='<table width="100%" border="1" style="padding:3px;">
                <thead>
                <tr>
                <th width="40px"><b>COD.</b></th>
                <th width="121px">FECHA PEDIDO</th>
                <th width="121px">FECHA LIMITE</th>
                <th width="120px">SUCURSAL</th>
                <th width="80px">REPARTIDOR</th>
                <th width="70px">ESTADO</th>
                </tr>
                </thead>
                <tbody>';
            foreach($pedidos as $ped){
                if($ped->pedido->sucursal && $ped->pedido->sucursal->tienda){
                    $html.='<tr>
                    <td width="40px">'. $ped->pedido->cod_pedido.'</td>              
                    <td width="121px">'.$ped->pedido->fecha_pedido.'</td>
                    <td width="121px">'. $ped->pedido->fecha_limite. '</td>
                    <td width="120px">'. $ped->pedido->sucursal->nombre.' - '.$ped->pedido->sucursal->tienda->nombre .'</td>
                    <td width="80px">'. $ped->repartidor->nombre.' '.$ped->repartidor->paterno. '</td>
                    <td width="70px">En Proceso</td>
                    </tr>';
                }
            }
        }else if($estado==2){
            $pedidos = Entrega::where('estado',$estado)->get();           
            $html='<table width="100%" border="1" style="padding:3px;">
                <thead>
                <tr>
                <th width="40px"><b>COD.</b></th>
                <th width="121px">FECHA PEDIDO</th>
                <th width="121px">FECHA ENTREGA</th>
                <th width="108px">SUCURSAL</th>
                <th width="100px">REPARTIDOR</th>
                <th width="70px">ESTADO</th>
                </tr>
                </thead>
                <tbody>';
            foreach($pedidos as $ped){
                if($ped->pedido->sucursal && $ped->pedido->sucursal->tienda){
                    $html.='<tr>
                    <td width="40px">'. $ped->pedido->cod_pedido.'</td>              
                    <td width="125px">'.$ped->pedido->fecha_limite.'</td>
                    <td width="125px">'. $ped->fechahora. '</td>
                    <td width="128px">'. $ped->pedido->sucursal->nombre .' '.$ped->pedido->sucursal->tienda->nombre . '</td>
                    <td width="80px">'. $ped->repartidor->nombre.' '.$ped->repartidor->paterno. '</td>
                    <td width="70px">Entregado</td>
                    </tr>';
                }
            }
        }else if($estado==3){ //DEMORADO
            //Logica de demorados
            $entregas = Entrega::get('pedido_id');
            $pedidos_pendientes = Pedido::whereNotIn('id', $entregas)->whereDate('fecha_limite', '<', date('Y-m-d'))->where('activo', 1)->get();
            foreach($pedidos_pendientes as $ped){

                $html='<table width="100%" border="1" style="padding:3px;">
                <thead>
                <tr>
                <th width="40px"><b>COD</b></th>
                <th width="100px">DESCRIPCION</th>
                <th width="121px">FECHA PEDIDO</th>
                <th width="121px">FECHA LIMITE</th>
                <th width="110px">SUCURSAL</th>
                <th width="70px">ESTADO</th>
                </tr>
                </thead>
                <tbody>';
            foreach($pedidos_pendientes as $ped){
                if($ped->sucursal && $ped->sucursal->tienda){
                    $html.='<tr>
                    <td width="40px">'. $ped->cod_pedido .'</td>
                    <td width="100px">'. $ped->descripcion. '</td>                
                    <td width="121px">'. $ped->fecha_pedido. '</td>
                    <td width="120px">'. $ped->fecha_limite. '</td>
                    <td width="110px">'. $ped->sucursal->nombre.' '.$ped->sucursal->tienda->nombre. '</td>
                    <td width="70px">Demorado</td>
                    </tr>';
                }
            }
            }
        }else if($estado==4){ //CANCELADO
            //Logica de cancelados
            $pedidos_pendientes = Pedido::where('activo', 0)->get();
            
            $html='<table width="100%" border="1" style="padding:3px;">
                <thead>
                <tr>
                <th width="40px"><b>COD</b></th>
                <th width="80px">DESCRIPCION</th>
                <th width="121px">FECHA PEDIDO</th>
                <th width="120px">FECHA LIMITE</th>
                <th width="130px">SUCURSAL</th>
                <th width="70px">ESTADO</th>
                </tr>
                </thead>
                <tbody>';
            foreach($pedidos_pendientes as $ped){
                if($ped->sucursal && $ped->sucursal->tienda){
                    $html.='<tr>
                    <td width="40px">'. $ped->cod_pedido .'</td>
                    <td width="80px">'. $ped->descripcion. '</td>                
                    <td width="121px">'. $ped->fecha_pedido. '</td>
                    <td width="120px">'. $ped->fecha_limite. '</td>
                    <td width="130px">'. $ped->sucursal->nombre.' - '. $ped->sucursal->tienda->nombre . '</td>
                    <td width="70px">Cancelado</td>
                    </tr>';
                }
            }

        }else{
            //TODOS
            $pedidos_todo = Pedido::get();
            
            $html='<table width="100%" border="1" style="padding:3px;">
                <thead>
                <tr>
                <th width="40px"><b>COD</b></th>
                <th width="80px">DESCRIPCION</th>
                <th width="121px">FECHA PEDIDO</th>
                <th width="120px">FECHA LIMITE</th>
                <th width="130px">SUCURSAL</th>
                <!--th width="70px">ESTADO</th-->
                </tr>
                </thead>
                <tbody>';
            foreach($pedidos_todo as $ped){
                $estado = '';
                /*if($ped->activo == 0){
                    $estado = '<td width="70px">Cancelado</td>';                    
                }elseif($ped->fecha_limite < date('Y-m-d')){
                    $estado = '<td width="70px">Demorado</td>'; //verificar linea 403 logica demorados
                }*/
                if($ped->sucursal && $ped->sucursal->tienda){
                    $html.='<tr>
                    <td width="40px">'. $ped->cod_pedido .'</td>
                    <td width="80px">'. $ped->descripcion. '</td>                
                    <td width="121px">'. $ped->fecha_pedido. '</td>
                    <td width="120px">'. $ped->fecha_limite. '</td>
                    <td width="130px">'. $ped->sucursal->nombre.' - '. $ped->sucursal->tienda->nombre . '</td>'.
                    $estado
                    .'</tr>';
                }
            }

        }

        //PDF::SetXY(10, 80);
        //$pedidos = [];
        //$pedidos_pendientes = [];
        //$estado=$request->estado;         

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
        //PDF::Image('tiendas/'. $tienda->logo,180,55,25,25);

		//PDF::Text(20, 205, 'Codigo de verificación');

		PDF::Output('listado de clientes.pdf');
        //exit;
    }
}
