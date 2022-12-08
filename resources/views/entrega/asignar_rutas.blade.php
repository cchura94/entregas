@extends('layouts.admin')

@section('contenido')
<div class="row">
	<div class="col-md-5">
		<div class="card card-warning">
			<div class="card-header">
				<h3 class="card-title">ASIGNAR PEDIDOS</h3>
				<a href="{{route('entrega.index')}}" class="btn btn-info float-right">Volver</a>
			</div>
					<!-- /.card-header -->
			<div class="card-body">
							<div class="card-header">
								<div class="row">
									<div class="col-md-6">
										<label for="">NOMBRES: </label>
										<p>{{ $repartidor->nombre }}</p>
									</div>
									<div class="col-md-6">
										<label for="">APELLIDO PATERNO: </label>
										<p>{{ $repartidor->paterno }}</p>
									</div>
									<div class="col-md-12">
										<label for="">APELLIDO MATERNO: </label>
										<p>{{ $repartidor->materno }}</p>
									</div>

									<div class="col-md-6">
										<label for="">TELEFONO: </label>
										<p>{{ $repartidor->telefono }}</p>
									</div>
									<div class="col-md-6">
										<label for="">USUARIO: </label>
										<p>{{ $repartidor->user->email }}</p>
									</div>
								</div>
							</div>

			</div>
						
		</div>
	</div>
		
	<div class="col-md-7">
		<div class="card card-warning">
			<div class="card-header">
				<h3 class="card-title">Lista de Pedidos - Sucursales</h3>
				<a href="{{route('entrega.index')}}" class="btn btn-info float-right">Volver</a>
			</div>
					<!-- /.card-header -->
			<div class="card-body">	

						<form action="{{route('entrega.store')}}" method="post">
							@csrf

							<input type="hidden" value="{{ $repartidor->id }}" name="repartidor_id">
							<table class="table table-striped table-hover">
							<tr>
								<th>-</th>
								<th>COD. PEDIDO</th>
								<th>SUCURSAL</th>
								<th>NOMBRE</th>
								<th>ACCION</th>
							</tr>					
							
							@foreach($pedidos as $ped)
							@if($ped->sucursal && $ped->sucursal->tienda)
								<tr>
									<td><input type="checkbox" name="pedidos[]" value="{{$ped->id}}"></td>
									<td>{{ $ped->cod_pedido }}</td>
									<td>{{ $ped->sucursal->tienda->nombre }}</td>
									<td>{{ $ped->sucursal->nombre }}</td>
									<td><a href="{{ url('/admin/pedido') }}/{{$ped->id}}" class="btn btn-success btn-xs">ver pedido</a></td>								
								</tr>
							@endif
							@endforeach
							</table>
							
							<small>Seleccione varias opciones</small>
							<br>

							<input type="submit" value="ASIGNAR" class="btn btn-success">						
							
						</form>
					</div>
			</div>

		
</div>
		

		@endsection