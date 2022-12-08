@extends('layouts.admin')

@section('contenido')

<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">EDITAR REGISTRO DE PEDIDOS</h3>
                <a href="{{route('pedido.index')}}" class="btn btn-info float-right">Volver</a>
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

				<form action="{{route('pedido.update', $pedido->id)}}" method="POST" data-parsley-validate>
					@csrf
					@Method('PUT')
					<div class="row">

					<div class="col-md-4">
						<label for="">CODIGO PEDIDO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('cod_pedido') is-invalid @enderror" name="cod_pedido" placeholder="cod pedido" value="{{ $pedido->cod_pedido }}">
			            </div>
					</div>
					<div class="col-md-4">
						<label for="">FECHA PEDIDO</label>
						<div class="input-group">
							<span class="input-group-addon"></span>
							<input type="text" class="form-control" disabled value="{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }}">
			                <input type="date" class="form-control @error('fecha_pedido') is-invalid @enderror" name="fecha_pedido" placeholder="fecha pedido" value="" required>
			            </div>
					</div>
					<div class="col-md-4">
						<label for="">FECHA LIMITE</label>
						<div class="input-group">
							<span class="input-group-addon"></span>
							
							<input type="text" class="form-control" disabled value="{{ \Carbon\Carbon::parse($pedido->fecha_limite)->format('d/m/Y') }}">
			                <input type="date" class="form-control @error('fecha_limite') is-invalid @enderror" name="fecha_limite" placeholder="fecha limite" value="" required>
			            </div>
					</div>

					<div class="col-md-4">
						<label for="">SUCURSAL</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <select name="sucursal_id" id="" class="form-control @error('sucursal_id') is-invalid @enderror" required>
			                	@foreach($sucursales as $suc)
								@if($suc && $suc->tienda)
			                	<option value="{{$suc->id}}" {{($suc->id === $pedido->sucursal_id)?'selected':''}}> {{$suc->tienda->nombre}} - {{$suc->nombre}} </option>
			                	@endif
								@endforeach
			                </select>
			            </div>
					</div>
					<div class="col-md-8">
						<label for="">DESCRIPCION</label>
				        <textarea name="descripcion" class="form-control" placeholder="Descripcion..." cols="30" rows="3">{{ $pedido->descripcion }}</textarea>
					</div>

					<div class="col-md-12">
						<br>
						<input type="submit" value="Modificar" class="btn btn-primary float-right">
					</div>
					</div>
				</form>
			</div>
		</div>


@endsection
