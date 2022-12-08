@extends('layouts.admin')

@section('script')

  <script>
	 mapboxgl.accessToken = 'pk.eyJ1IjoiY2h1cmEiLCJhIjoiY2s4aXcyN3d5MDFhYjNscHFrNXRwZjJncSJ9.0pxS7oAdvDckGKhoUjTiMg';
	 var map = new mapboxgl.Map({
	     container: 'map',
	     style: 'mapbox://styles/mapbox/streets-v11',
	     center: [-68.132495, -16.495534],
	     zoom: 11.15
	 });

	 map.addControl(new mapboxgl.NavigationControl());

	 map.on('load', function () {    
	     

		 var marker = new mapboxgl.Marker()
		 marker.setLngLat([-68.132495, -16.495534])
			.addTo(map);
		 map.on('click', function(e){
			
			marker.setLngLat([e.lngLat.lng, e.lngLat.lat])
			.addTo(map);
			document.getElementById("lat").value =  e.lngLat.lat;
        document.getElementById("lng").value = e.lngLat.lng;
		 })

	     // Change the cursor to a pointer when the mouse is over the places layer.
	     map.on('mouseenter', 'places', function () {
	         map.getCanvas().style.cursor = 'pointer';
	     });

	     // Change it back to a pointer when it leaves.
	     map.on('mouseleave', 'places', function () {
	         map.getCanvas().style.cursor = '';
	     });
	 });
   /*   var map;
      

var marker;          //variable del marcador
var coords = {};    //coordenadas obtenidas con la geolocalización

//Funcion principal
initMap = function () 
{

    //usamos la API para geolocalizar el usuario
        navigator.geolocation.getCurrentPosition(
          function (position){
            coords =  {
              lng: position.coords.longitude,
              lat: position.coords.latitude
            };
            setMapa(coords);  //pasamos las coordenadas al metodo para crear el mapa
          },function(error){console.log(error);});   
}

function setMapa (coords)
{   
      //Se crea una nueva instancia del objeto mapa
      var map = new google.maps.Map(document.getElementById('map'),
      {
        zoom: 13,
        center:new google.maps.LatLng(coords.lat,coords.lng),

      });

      //Creamos el marcador en el mapa con sus propiedades
      //para nuestro obetivo tenemos que poner el atributo draggable en true
      //position pondremos las mismas coordenas que obtuvimos en la geolocalización
      marker = new google.maps.Marker({
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,
        position: new google.maps.LatLng(coords.lat,coords.lng),

      });
      //agregamos un evento al marcador junto con la funcion callback al igual que el evento dragend que indica 
      //cuando el usuario a soltado el marcador
      marker.addListener('click', toggleBounce);
      
      marker.addListener( 'dragend', function (event)
      {
        //escribimos las coordenadas de la posicion actual del marcador dentro del input #coords

        document.getElementById("lat").value = this.getPosition().lat();
        document.getElementById("lng").value = this.getPosition().lng();
      });
}

//callback al hacer clic en el marcador lo que hace es quitar y poner la animacion BOUNCE
function toggleBounce() {
  if (marker.getAnimation() !== null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}
*/
// Carga de la libreria de google maps 
</script>
<!--script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCssDur9_mhqQThoCuEYDcjq2EmVCCQYXw&callback=initMap"
    async defer></script-->
@endsection

@section('contenido_con_mapa')

<div class="card card-warning">
    <div class="card-header">
		<h3 class="card-title">DATOS DEL ESTABLECIMIENTO</h3>
		<a href="{{route('tienda.index')}}" class="btn btn-warning float-right">volver</a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
		<div class="row">
			<div class="col-md-6">
				<label for="">NOMBRE</label>
				<div class="input-group">
					<span class="input-group-addon"></span>
					<input type="text" class="form-control" name="nombre" placeholder="Nombre" value="{{ $tienda->nombre }}" disabled="">
				</div>
			</div>
			<div class="col-md-6">
				<label for="">DESCRIPCION</label>
				<textarea name="descripcion" class="form-control" placeholder="Descripcion..." cols="30" rows="1" disabled="">{{ $tienda->descripcion }}</textarea>
			</div>
		</div>				
    </div>
</div>

<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">NUEVA SUCURSAL</h3>
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
        <form action="{{route('add_suc', $tienda->id)}}" method="POST" data-parsley-validate>
			@csrf
			<div class="row">
				<div class="col-md-6">
					<label for="">NOMBRE</label>
					<div class="input-group">
						<span class="input-group-addon"></span>
						<input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" placeholder="Nombre" value="{{ old('nombre') }}" required>
					</div>
				</div>
				<div class="col-md-6">
					<label for="">DIRECCION</label>
					<input type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" placeholder="direccion" value="{{ old('direccion') }}" required>
				</div>
				<div class="col-md-8">
					<label for="">DESCRIPCION</label>
					<textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Descripcion..." cols="30" rows="1">{{ old('descripcion') }}</textarea>
				</div>
				<div class="col-md-4">
					<label for="">TELEFONO CONTACTO</label>
					<div class="input-group">
						<span class="input-group-addon"></span>
						<input type="number" class="form-control @error('telefono_c') is-invalid @enderror" name="telefono_c" placeholder="Telefono Contacto" value="{{ old('telefono_c') }}" required pattern="[0-9]{5,12}">
					</div>
				</div>
				<div class="col-md-8">
					<label for="">NOMBRE CONTACTO</label>
					<div class="input-group">
						<span class="input-group-addon"></span>
						<input type="text" class="form-control @error('nombre_c') is-invalid @enderror" name="nombre_c" placeholder="Nombre contacto" value="{{ old('nombre_c') }}" required>
					</div>
				</div>
				
				<div class="col-md-2">
					<label for="">LATITUD</label>
					<div class="input-group">
						<span class="input-group-addon"></span>
						<input type="text" class="form-control @error('latitud') is-invalid @enderror" name="latitud" placeholder="Latitud" id="lat" value="{{ old('latitud') }}">
					</div>
				</div>
				<div class="col-md-2">
					<label for="">LONGITUD</label>
					<div class="input-group">
						<span class="input-group-addon"></span>
						<input type="text" class="form-control @error('longitud') is-invalid @enderror" name="longitud" placeholder="Longitud" id="lng" value="{{ old('longitud') }}">
					</div>
				</div>
				<div class="col-md-12">
					<label for="">Mapa (Seleccione la ubicación de la Sucursal)</label>
					<div id="map" style="width: 100%;height: 300px;"></div>
				</div>	
					<input type="hidden" value="{{ $tienda->id }}" name="tienda_id">
				<div class="col-md-12">
					<input type="submit" value="Guardar" class="btn btn-primary float-right">
				</div>
			</div>
		</form>
    </div>
</div>

@endsection

