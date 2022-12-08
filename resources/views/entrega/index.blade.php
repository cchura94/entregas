@extends('layouts.admin')

@section('contenido')
           <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Asignación de Rutas - Repartidor</h3>
                <a class="accion btn btn-success btn-xs float-right" href="#" onclick="">Descargar Excel</a>
                <span class="delimitador  float-right">|</span>
                <a id="lnkPDF" class="accion btn btn-primary btn-xs float-right" href="#">Descargar PDF</a>
              </div>
              <!-- /.card-header -->
                <div class="card-body">

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>  
                  <th>NOMBRE</th>                
                  <th>APELLIDO PATERNO</th>
                  <th>APELLIDO MATERNO</th>
                  <th>CI</th>
                  <th>TELEFONO</th>
                  <th>RUTAS ASIGNADAS</th>
                  <th>ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                	@foreach($repartidores as $rep)
                <tr>
                  <td>{{$rep->nombre}}</td>
                  <td>{{$rep->paterno}}</td>
                  <td>{{$rep->materno}}</td>
                  <td>{{$rep->ci}}</td>
                  <td>{{$rep->telefono}}</td>
                  <td>
                    <a href="{{route('pedidos_rutas_asignados', $rep->id)}}" class="btn btn-warning btn-xs">ver pedidos asignados</a>
                  </td>
                  <td>
                  	<a href="{{route('asignar_rutas', $rep->id)}}" class="btn btn-success btn-xs">asignar rutas</a>
                  </td>
                </tr>
                @endforeach
                
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
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
</script>
@endsection

