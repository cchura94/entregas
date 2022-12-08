@extends('layouts.admin')

@section('contenido')

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
            	<form action="{{route('sucursal.show', $sucursal->id)}}" method="POST">
					@csrf
					@Method('PUT')
                    <div class="row">
					<div class="col-md-6">
						<label for="">NOMBRE</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" placeholder="Nombre" disabled="" value="{{ $sucursal->nombre }}">
			            </div>
					</div>
					<div class="col-md-6">
						<label for="">DESCRIPCION</label>
				        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Descripcion..." disabled="" cols="10" rows="1">{{ $sucursal->descripcion }}</textarea>
					</div>
                    <div class="col-md-6">
						<label for="">DIRECCIÓN</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" placeholder="direccion" disabled="" value="{{ $sucursal->direccion }}">
			            </div>
					</div>
                    <div class="col-md-3">
						<label for="">LATITUD</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('latitud') is-invalid @enderror" name="latitud" placeholder="latitud" disabled="" value="{{ $sucursal->latitud }}">
			            </div>
					</div>
                    <div class="col-md-3">
						<label for="">LONGITUD</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('longitud') is-invalid @enderror" name="longitud" placeholder="longitud" disabled="" value="{{ $sucursal->longitud }}">
			            </div>
					</div>
                    <div class="col-md-6">
						<label for="">NOMBRE CONTACTO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('nombre_c') is-invalid @enderror" name="nombre_c" placeholder="nombre_c" disabled="" value="{{ $sucursal->nombre_c }}">
			            </div>
					</div>
                    <div class="col-md-6">
						<label for="">TELEFONO CONTACTO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input  disabled="" type="text" class="form-control @error('telefono_c') is-invalid @enderror" name="telefono_c" placeholder="telefono_c" value="{{ $sucursal->telefono_c }}">
			            </div>
					</div>
                    </div>
				</form>
            </div>
    	</div>
	</div>
</div>

@endsection