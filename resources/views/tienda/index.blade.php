@extends('layouts.admin')

@section("titulo", "Lista de Clientes")

@section('contenido')
<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Lista de Clientes</h3>
                <a class="accion btn btn-success btn-xs float-right" href="{{ route('exportar_tiendas_excel') }}">Descargar Excel</a>
                <span class="delimitador  float-right">|</span>
                <a class="accion btn btn-primary btn-xs float-right" href="{{route('reporte_tiendas')}}" target="_blank">Descargar PDF</a>
              </div>
              <!-- /.card-header -->
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                        </ul>
                    </div>
				        @endif
              <a href="{{route('tienda.create')}}" class="btn btn-success">Nuevo Cliente</a>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>NOMBRE</th>
                  <th>DESCRIPCION</th>
                  <th>LOGO</th>
                  <th>AGREGAR SUCURSAL</th>
                  <th>ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                	@foreach($tiendas as $tienda)
                <tr>
                  <td>{{$tienda->nombre}}</td>
                  <td>{{$tienda->descripcion}}</td>
                  <td> <img src="{{asset('/logos/'.$tienda->logo)}}" width="80px" alt=""> </td>
                  <td>
                  <a href="{{route('add_sucursal', $tienda->id)}}" class="btn btn-outline-primary ">Agregar Sucursal</a>
                  <a href="{{route('tienda.show', $tienda->id)}}" class="btn btn-outline-success ">Ver Sucursales</a>
                  </td>
                  <td>
                  	<a href="{{route('tienda.edit', $tienda->id)}}" class="btn btn-warning btn-xs"> <i class="fa fa-edit"></i>editar</a>
                  	<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#Modal{{ $tienda->id }}">
                    <i class="fa fa-trash"></i> eliminar
                    </button>
                    
<!-- Modal para eliminar -->
<div class="modal fade" id="Modal{{ $tienda->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">¿ESTA SEGURO DE ELIMINAR EL CLIENTE?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      {{$tienda->nombre}} 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <form action="{{ route('tienda.destroy', $tienda->id) }}" method="post">
          @csrf
          @Method('DELETE')
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
        
      </div>
    </div>
  </div>
</div>
                  
                  </td>
                </tr>
                @endforeach
                
                </tbody>
              </table>
  
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

