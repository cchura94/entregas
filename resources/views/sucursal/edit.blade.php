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
	</script>
@endsection

@section('contenido_con_mapa')

<div class="row">
	<div class="col-md-12"> 
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">Modificar Sucursal</h3>
                <a href="{{route('tienda.index')}}" class="btn btn-primary float-right">volver</a>
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
            	<form action="{{route('sucursal.update', $sucursal->id)}}" method="POST" data-parsley-validate>
					@csrf
					@Method('PUT')
                    <div class="row">
						<div class="col-md-6">
						<label for="">NOMBRE</label>
						<div class="input-group">
							<span class="input-group-addon"></span>
							<input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" placeholder="Nombre" value="{{ $sucursal->nombre }}" required>
						</div>
					</div>
					<div class="col-md-6">
						<label for="">DESCRIPCION</label>
				        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Descripcion..." cols="10" rows="1">{{ $sucursal->descripcion }}</textarea>
					</div>
                    <div class="col-md-6">
						<label for="">DIRECCIÓN</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" placeholder="direccion" value="{{ $sucursal->direccion }}">
			            </div>
					</div>
                    <div class="col-md-6">
						<label for="">TELEFONO CONTACTO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('telefono_c') is-invalid @enderror" name="telefono_c" placeholder="telefono_c" value="{{ $sucursal->telefono_c }}" required pattern="[0-9]{5,12}">
			            </div>
					</div>
                    <div class="col-md-6">
						<label for="">NOMBRE CONTACTO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('nombre_c') is-invalid @enderror" name="nombre_c" placeholder="nombre_c" value="{{ $sucursal->nombre_c }}" required>
			            </div>
					</div>
                    
					<div class="col-md-3">
						<label for="">LATITUD</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('latitud') is-invalid @enderror" name="latitud" id="lat" placeholder="latitud" value="{{ $sucursal->latitud }}">
			            </div>
					</div>
                    <div class="col-md-3">
						<label for="">LONGITUD</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('longitud') is-invalid @enderror" name="longitud" id="lng" placeholder="longitud" value="{{ $sucursal->longitud }}">
			            </div>
					</div>
					<div class="col-md-12">
					<label for="">Mapa (Seleccione la ubicación de la Sucursal)</label>
					<div id="map" style="width: 100%;height: 200px;"></div>
				</div>
					<div class="col-md-12">
						<br>
						<input type="submit" value="Modificar" class="btn btn-primary float-right">
					</div>
                    </div>
				</form>
            </div>
    	</div>
	</div>
</div>

@endsection