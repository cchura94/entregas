@extends('layouts.admin')

@section('contenido')

        <div class="card card-warning">

              <div class="card-header">
                <h3 class="card-title">Lista Repartidor</h3>
                <a class="accion btn btn-success btn-xs float-right" href="#" onclick="">Descargar Excel</a>
                <span class="delimitador  float-right">|</span>
                <a id="lnkPDF" class="accion btn btn-primary btn-xs float-right" href="{{route('reporte_repartidores')}}">Descargar PDF</a>
              </div>
            <!-- /.box-header -->
            <div class="card-body">
              <a href="{{route('repartidor.create')}}" class="btn btn-success">Nuevo Repartidor</a>
              <br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr> 
                    <th>NOMBRE</th>                 
                    <th>APELLIDO PATERNO</th>
                    <th>APELLIDO MATERNO</th>
                    <th>CI</th>
                    <th>DIRECCION</th>
                    <th>TELEFONO</th>
                    <th>CUENTA DE USUARIO</th>
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
                    <td>{{$rep->direccion}}</td>
                    <td>{{$rep->telefono}}</td>
                    <td>{{ $rep->user->email }}</td>
                    <td>
                      <a href="{{route('repartidor.show', $rep->id)}}" class="btn btn-success btn-xs">ver</a> 
                      <a href="{{route('repartidor.edit', $rep->id)}}" class="btn btn-warning btn-xs">editar</a>
                      <form action="{{route('repartidor.destroy', $rep->id)}}" method="post">
                        @csrf
                        @Method('DELETE')
                        <input type="submit" value="eliminar" class="btn btn-danger btn-xs">
                      </form>
                    </td>
                  </tr>
                  @endforeach
                  </tbody>
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

