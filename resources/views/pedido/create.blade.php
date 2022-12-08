@extends('layouts.admin')

@section('contenido')
<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">REGISTRO DE PEDIDOS</h3>
                <a href="{{route('pedido.index')}}" class="btn btn-info float-right">Volver</a>
              </div>
	@if($sucursales)
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

				<form action="{{route('pedido.store')}}" method="POST" data-parsley-validate>
					@csrf
					<div class="row">					
					<div class="col-md-4">
						<label for="">CODIGO PEDIDO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
							<input type="number" class="form-control @error('cod_pedido') is-invalid @enderror" placeholder="cod pedido" value="00{{ (!$ultimo_pedido)?'1':($ultimo_pedido->id+1) }}" disabled>
							<input type="hidden" name="cod_pedido" value="00{{ (!$ultimo_pedido)?'1':($ultimo_pedido->id+1) }}">
			            </div>
					</div>
					<div class="col-md-4">
						<label for="">FECHA PEDIDO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="date" class="form-control @error('fecha_pedido') is-invalid @enderror" name="fecha_pedido" placeholder="fecha pedido" value="{{ date('Y-m-d') }}" required>
			            </div>
					</div>
					<div class="col-md-4">
						<label for="">FECHA LIMITE</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>

			                <input type="date" class="form-control @error('fecha_limite') is-invalid @enderror" name="fecha_limite" placeholder="fecha limite" required>
			            </div>
					</div>

					<div class="col-md-4">
						<label for="">SUCURSAL</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <select name="sucursal_id" id="" class="form-control @error('sucursal_id') is-invalid @enderror" required>
			                	@foreach($sucursales as $suc)
								@if($suc->tienda)
			                	<option value="{{$suc->id}}">{{$suc->tienda->nombre}} - {{$suc->nombre}} </option>
			                	@endif
								@endforeach
			                </select>
			            </div>
					</div>
					<div class="col-md-8">
						<label for="">DESCRIPCION</label>
				        <textarea name="descripcion" class="form-control" placeholder="Descripcion..." cols="30" rows="3"></textarea>
					</div>

					<div class="col-md-12">
						<br>
						<input type="submit" value="Guardar" class="btn btn-primary float-right">
					</div>
				</div>
				</form>
			</div>

			@else

			<h2>No existen sucursales en la base de datos</h2>

			@endif
		</div>

@endsection
