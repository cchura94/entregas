@extends('layouts.admin')

@section('contenido')

<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">PEDIDO</h3>
                <a href="{{ route('pedido.index') }}" class="btn btn-warning float-right">volver</a>
              </div>
              <!-- /.card-header -->
                <div class="card-body">

				<form action="{{route('pedido.update', $pedido->id)}}" method="POST">
					<div class="row">
					
					<div class="col-md-4">
						<label for="">CODIGO PEDIDO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control" name="cod_pedido" placeholder="cod pedido" value="{{ $pedido->cod_pedido }}" disabled="">
			            </div>
					</div>
					<div class="col-md-4">
						<label for="">FECHA PEDIDO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control" disabled value="{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y') }}">
			                </div>
					</div>
					<div class="col-md-4">
						<label for="">FECHA LIMITE</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>

			                <input type="text" class="form-control" disabled value="{{ \Carbon\Carbon::parse($pedido->fecha_limite)->format('d/m/Y') }}">
			                </div>
					</div>

					<div class="col-md-4">
						<label for="">SUCURSAL</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <select name="sucursal_id" id="" class="form-control" disabled="">
			                	@foreach($sucursales as $suc)
			                	<option value="{{$suc->id}}" {{($suc->id === $pedido->sucursal_id)?'selected':''}}> {{$suc->tienda->nombre}} - {{$suc->nombre}} </option>
			                	@endforeach
			                </select>
			            </div>
					</div>
					<div class="col-md-8">
						<label for="">DESCRIPCION</label>
				        <textarea name="descripcion" class="form-control" placeholder="Descripcion..." cols="30" rows="3" disabled="">{{ $pedido->descripcion }}</textarea>
					</div>
				</form>
			</div>
		</div>

@endsection
