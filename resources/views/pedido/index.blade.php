@extends('layouts.admin')

   @section('contenido')

        <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Lista de Pedidos No entregados</h3>
                <a class="accion btn btn-success btn-xs float-right" href="#" onclick="">Descargar Excel</a>
                <span class="delimitador  float-right">|</span>
                <a id="lnkPDF" class="accion btn btn-info btn-xs float-right" target="_blank" href="{{ route('reporte_pedidos', 'estado='.$estado) }}">Descargar PDF</a>
                <!--a-- id="lnkPDF" class="accion btn btn-info btn-xs float-right" target="_blank" href="{{ route('reporte_pedidos') }}">Descargar PDF</!--a-->
              </div>
              <!-- /.card-header -->
          <div class="card-body">
          <form action="{{route('busqueda_pedido')}}" method="get">
          <div class="row">
          <div class="col-md-2">
            <a href="{{route('pedido.create')}}" class="btn btn-success">Nuevo Pedido</a>
          </div>
          <div class="col-md-1">
            <label>BUSCAR POR:</label>
          </div>
          
          <div class="col-md-2">
            <div class="form-group">
              <select name="estado" id="" class="form-control">
              
                <option value="5" {{($estado == 5)?'selected':''}}>Ver Todo</option>
                <option value="0" {{($estado == 0)?'selected':''}}>Pendientes</option>
                <option value="1" {{($estado == 1)?'selected':''}}>En Proceso</option>
                <option value="2" {{($estado == 2)?'selected':''}}>Entregado</option>
                <option value="3" {{($estado == 3)?'selected':''}}>Demorado</option>
                <option value="4" {{($estado == 4)?'selected':''}}>Cancelado</option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <input type="submit" value="Buscar" class=" btn btn-primary">
          </div>
          </form>
          <div class="col-md-4">  
          <form action="{{route('busqueda_pedido')}}" method="get" style="display:inline">
                
               <div class="row">
                <div class="col-md-6">
                <input type="hidden" name="estado" value="5">
                <input type="date" name="fecha_pedido" class="form-control"> 
                </div>
                <div class="col-md-6">
                <input type="submit" value="buscar por Fecha" class=" btn btn-info"> 
                </div>
               </div>
              
          </form>
          </div>
          
          </div>
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>                  
                <th>COD PEDIDO</th>
                <th>FECHA PEDIDO</th>
                <th>FECHA LIMITE</th>
                <th>(CLIENTE) - SUCURSAL</th>
                @if($estado==1 || $estado==2)
                <th>REPARTIDOR ENCARGADO</th>
                @endif
                <th>ESTADO</th>                
                <th>ACCIONES</th>
              </tr>
            </thead>
            <tbody>
        @if($pedidos_pendientes)
          @foreach($pedidos_pendientes as $ped)
          @if($ped->sucursal && $ped->sucursal->tienda)
            <tr class="{{($ped->fecha_limite == date('Y-m-d 00:00:00'))?'table-warning':''}}{{($ped->activo == 0)?'table-danger':''}}">
                <td>{{$ped->cod_pedido}}</td>
                <td>{{$ped->fecha_pedido}}</td>
                <td>{{$ped->fecha_limite}}</td>
                <td>{{$ped->sucursal->nombre}} - {{$ped->sucursal->tienda->nombre}}</td>
                <td>


                <!--
                @if($ped->entrega)
                  
                <a href="#" class="btn btn-{{($ped->entrega->estado == 2)?'success':'warning'}} btn-xs" >{{($ped->entrega->estado == 2)?'Entregado':'En Proceso'}}</a>
                
                @endif
-->
                <!-- Verificar entregado demorado -->
                @if($ped->activo == 1)
                    <a href="#" class="btn btn-{{($ped->fecha_limite < date('Y-m-d'))?'secondary':'info'}} btn-xs" >{{($ped->fecha_limite < date('Y-m-d'))?'Demorado':'Pendiente'}}</a></td>
                @else
                    <a href="#" class="btn btn-danger btn-xs" >Cancelado</a></td>
                @endif
                
                <td>
                  <a href="{{route('pedido.show', $ped->id)}}" class="btn btn-success btn-xs" ><i class="fa fa-eye"></i>Ver</a>
                  <a href="{{route('pedido.edit', $ped->id)}}" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i>Editar</a>
  @if($ped->entrega && $ped->entrega->estado != 2)   
    <!-- Button trigger modal --> 
  <!--button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#Modal{{$ped->id}}">
    Cancelar Pedido
  </button-->
  @endif
  @if($ped->activo == 1)
  
<!-- Button trigger modal --> 
<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#Modal{{$ped->id}}">
  Cancelar Pedido
</button>
@endif

<!-- Modal -->
<div class="modal fade" id="Modal{{$ped->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cancelar Pedido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4>¿Esta seguro de desea cancelar el Pedido?</h4>
        <p>{{$ped->sucursal->nombre}} - {{$ped->sucursal->tienda->nombre}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <a href="{{route('cancelar_pedido', $ped->id)}}" class="btn btn-primary" >Cancelar Pedido</a>
              
      </div>
    </div>
  </div>
</div>
                
                
                </td>

                @endif
            </tr>
          @endforeach
             
        @else
          @foreach($pedidos as $ped)
          @if($ped->pedido->sucursal && $ped->pedido->sucursal->tienda)
             <tr class="{{($ped->fecha_limite == date('Y-m-d 00:00:00'))?'table-warning':''}}{{($ped->activo == 0)?'table-danger':''}}">
              <td>{{$ped->pedido->cod_pedido}}</td>
              <td>{{$ped->pedido->fecha_pedido}}</td>
              <td>{{$ped->pedido->fecha_limite}}</td>
              <td>{{$ped->pedido->sucursal->nombre}} - {{$ped->pedido->sucursal->tienda->nombre}}</td>
              @if($ped->pedido->entrega->estado==1 || $ped->pedido->entrega->estado==2)
              <td><a href="{{ url('/admin/repartidor') }}/{{$ped->pedido->entrega->repartidor->id}}" target="_blank">{{$ped->pedido->entrega->repartidor->nombre}} {{$ped->pedido->entrega->repartidor->paterno}}</a></td>
              @endif
              <td>
              @if($ped->pedido->entrega->estado==1)
                <a href="#" class="btn btn-warning btn-xs" >En Proceso</a>
                <label>{{($ped->pedido->fecha_limite < date('Y-m-d'))?'fecha limite excedido':''}}</label>
              </td>
                
                
              @elseif($ped->pedido->entrega->estado==2)
                <a href="#" class="btn btn-success btn-xs" >Entregado</a></td>
              @elseif($ped->pedido->entrega->estado==3)
                <a href="#" class="btn btn-secondary btn-xs" >Demorado</a></td>
              @elseif($ped->pedido->entrega->estado==4)
                <a href="#" class="btn btn-danger btn-xs" >Cancelado</a></td>
              @endif
              <td>
              <a href="{{route('pedido.show', $ped->pedido->id)}}" class="btn btn-success btn-xs" ><i class="fa fa-eye"></i>Ver</a>
              <a href="{{route('pedido.edit', $ped->pedido->id)}}" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i>Editar</a>
              
@if($ped->pedido->entrega->estado != 2)
              <!-- Button trigger modal -->
<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#Modal{{$ped->id}}">
  Cancelar Pedido
</button>
@endif

<!-- Modal -->
<div class="modal fade" id="Modal{{$ped->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cancelar Pedido</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4>¿Esta seguro de desea cancelar el Pedido?</h4>
        <p>{{$ped->pedido->sucursal->nombre}} - {{$ped->pedido->sucursal->tienda->nombre}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <a href="{{route('cancelar_pedido', $ped->pedido->id)}}" class="btn btn-primary" >Cancelar Pedido</a>
              
      </div>
    </div>
  </div>
</div>

            </td>
          </tr>
          @endif
          @endforeach        
        @endif
          
        </tfoot>
      </table>

        </div>
        <!-- /.box-body -->
      </div>
        </div>
      </div>
  @endsection
  @section('script')

<!--script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script-->
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<!-- AdminLTE App -->
<!--script src="{{ asset('dist/js/adminlte.min.js') }}"></script-->
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable({
        "language": {
            "sProcessing":    "Procesando...",
            "sLengthMenu":    "Mostrar _MENU_ registros",
            "sZeroRecords":   "No se encontraron resultados",
            "sEmptyTable":    "Ningún dato disponible en esta tabla",
            "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":   "",
            "sSearch":        "Buscar:",
            "sUrl":           "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":    "Último",
                "sNext":    "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
  });

  
</script>
@endsection

