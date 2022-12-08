@extends('layouts.admin')

@section('contenido_con_mapa')

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
            <div class="col-md-6">
              <label for="">Nombre Completo: </label>
              <p>{{$repartidor->nombre}} {{$repartidor->paterno}} {{$repartidor->materno}}</p>
            </div>
            <div class="col-md-6">
              <label for="">Telefono: </label>
              <p>{{$repartidor->telefono}}</p>
            </div>
        </div>
      </div>
    </div>
  </div>
	<div class="col-md-6">
	  <div class="card">
              <div class="card-header">
                <h3 class="card-title">Repartidor:</h3>
                
                <a href="{{url('/admin/entrega/asignar')}}" class="btn btn-warning float-right">volver</a>
              </div>             

              <div class="container">
                <br>
                <div class="row">
                    <div class="col-md-12">
                      <form action="" method="get">
                        <div class="row">
                          <div class="col-md-8">
                            
                            <input type="date" class="form-control" name="fecha" value="{{ $f }}">
                            
                          </div>
                          <div class="col-md-4">
                            <input type="submit" value="buscar por fecha" class="btn btn-primary">
                          </div>
                        </div> 
                      </form>
                    </div>

                </div>              
              </div>             

              <div class="card-body">

                <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>   
                  <th>COD. PEDIDO</th>               
                  <th>NOMBRE</th>
                  <th>DIRECCION</th>                  
                  <th>ESTADO</th>
                  <!--th>ACCIONES</th-->
                </tr>
                </thead>
                <tbody>
                  @foreach($entregas as $ent)
                  
                <tr>
                  <td>{{$ent->pedido->cod_pedido}}</td>
                  <td>{{$ent->pedido->sucursal->tienda->nombre}}</td>
                  <td>{{$ent->pedido->sucursal->direccion}}</td>                  
                  <td>
                    @if($ent->estado == 0)
                    <span class="badge badge-danger">Pendiente</span>
                    @elseif($ent->estado == 1)
                    <span class="badge badge-warning">Proceso</span>
                    @else
                    <span class="badge badge-success">Entregado</span>
                    @endif
                  </td>
                  <!--td>
                    <a href="{{route('ver_ruta_asignada', $ent->id)}}" class="btn btn-success">ver más detalles</a>
                  </td-->
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
      </div>

	</div>
	<div class="col-md-6">
			<div class="card">
          <div class="card-header">
            	<h3 class="card-title">Notificaciónes Ubicaciones:</h3>
          </div>
          <div class="card-body">
              <div class="row">
                <div class="col-md-12 col-md-offset-2">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>FECHA:</th>
                                <th>LATITUD:</th>
                                <th>LONGITUD:</th>
                                <th>MENSAJE</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($mensajes as $men)
                            <tr>
                                <td><strong>{{ $men->created_at }}</strong></td>
                                <td>{{ $men->latitud }}</td>
                                <td>{{ $men->longitud }}</td>
                                <td>{{ $men->mensaje }}</td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
							<!--message :messages="messages" :id="{{ $repartidor->id }}"></message-->
          </div>
      </div>
	</div>

  <div class="col-md-12">
			<div class="card">
          <div class="card-header">
            	<h3 class="card-title">Notificaciónes Ubicaciones:</h3>
          </div>
          <div class="card-body">
            <div id="map" style="width:100%;height:600px;background:#787;"></div>
          </div>
      </div>
	</div>

</div>
      
@endsection

@section('js')
<script>
var entregas = @json($entregas);

var coordenadas = [];
for (let i = 0; i < entregas.length; i++) {
  coordenadas.push([parseFloat(parseFloat(entregas[i].pedido.sucursal.longitud).toFixed(5)), parseFloat(parseFloat(entregas[i].pedido.sucursal.latitud).toFixed(5))]);
}
//coordenadas.push([parseFloat(parseFloat(entregas[0].pedido.sucursal.longitud).toFixed(5)), parseFloat(parseFloat(entregas[0].pedido.sucursal.latitud).toFixed(5))]);
//coordenadas.push(coordenadas[0]);
//if(coordenadas[0].lenght > 0){
  iniciarMapa(coordenadas);
//}

function iniciarMapa(coordenadas) {
    mapboxgl.accessToken = 'pk.eyJ1IjoiY2h1cmEiLCJhIjoiY2s4aXcyN3d5MDFhYjNscHFrNXRwZjJncSJ9.0pxS7oAdvDckGKhoUjTiMg';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11',
        center: coordenadas[0],
        zoom: 12
    });

    map.addControl(new mapboxgl.NavigationControl()); //habilita los controles + , - 

    map.on('load', function () {
        map.addSource('maine', {
            'type': 'geojson',
            'data': {
                'type': 'Feature',
                'geometry': {
                    'type': 'Polygon',
                    'coordinates': [coordenadas]
                }
            }
        });
        map.addLayer({
            'id': 'maine',
            'type': 'fill',
            'source': 'maine',
            'layout': {},
            'paint': {
                'fill-color': '#fc8200',
                'fill-opacity': 0.8
            }
        });
    });

}
/*var map;
var infoWindow;
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 14,
    center: {lat: -16.507786, lng: -68.127063},
    //mapTypeId: 'terrain'
  });

  // Define the LatLng coordinates for the polygon.
  var triangleCoords = coordenadas;

  // Construct the polygon.
  var bermudaTriangle = new google.maps.Polygon({
    paths: triangleCoords,
    strokeColor: '#FF0000',
    strokeOpacity: 0.8,
    strokeWeight: 3,
    fillColor: '#FF0000',
    fillOpacity: 0.35
  });
  bermudaTriangle.setMap(map);

  // Add a listener for the click event.
  bermudaTriangle.addListener('click', showArrays);

  infoWindow = new google.maps.InfoWindow;
}

*/
/** @this {google.maps.Polygon} */
/*
function showArrays(event) {
  // Since this polygon has only one path, we can call getPath() to return the
  // MVCArray of LatLngs.
  var vertices = this.getPath();

  var contentString = '<b>Bermuda Triangle polygon</b><br>' +
      'Clicked location: <br>' + event.latLng.lat() + ',' + event.latLng.lng() +
      '<br>';

  // Iterate over the vertices.
  for (var i =0; i < vertices.getLength(); i++) {
    var xy = vertices.getAt(i);
    contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' +
        xy.lng();
  }

  // Replace the info window's content and position.
  infoWindow.setContent(contentString);
  infoWindow.setPosition(event.latLng);

  infoWindow.open(map);
}*/
</script>
<!--script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCssDur9_mhqQThoCuEYDcjq2EmVCCQYXw&callback=initMap">
</script-->

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

