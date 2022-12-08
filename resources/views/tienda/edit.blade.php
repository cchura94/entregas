@extends('layouts.admin')

@section('contenido')

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">Modificar Cliente</h3>
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
            	<form action="{{route('tienda.update', $tienda->id)}}" method="POST" data-parsley-validate>
					@csrf
					@Method('PUT')
					<div class="row">
					
					<div class="col-md-6">
						<label for="">NOMBRE</label>
				
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" placeholder="Nombre" value="{{ $tienda->nombre }}" required>
			            </div>
					</div>
					<div class="col-md-6">
						<label for="">LOGO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="file" class="form-control @error('logo') is-invalid @enderror" name="logo" placeholder="logo" value="{{ $tienda->logo }}">
			            </div>
					</div>
					<div class="col-md-12">
						<label for="">DESCRIPCION</label>
				        <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Descripcion...">{{ $tienda->descripcion }}</textarea>
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
