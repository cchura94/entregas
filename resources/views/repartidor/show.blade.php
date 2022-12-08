@extends('layouts.admin')

@section('contenido')

<div class="row">
	<div class="col-xs-12">
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Ver Repartidor</h3>
                <a href="{{route('repartidor.index')}}" class="btn btn-primary float-right">volver</a>
              </div>
			<div class="card-body">
				<form action="#">
				<div class="row">
					<div class="col-md-4">
						<label for="">NOMBRES</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control" name="nombre" placeholder="Nombres" value="{{ $repartidor->nombre }}" disabled="">
			            </div>
					</div>
					<div class="col-md-4">
						<label for="">APELLIDO PATERNO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control" name="parterno" placeholder="Ap. Paterno" value="{{ $repartidor->paterno }}" disabled="">
			            </div>
					</div>
					<div class="col-md-4">
						<label for="">APELLIDO MATERNO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control" name="materno" placeholder="Ap. Materno" value="{{ $repartidor->materno }}" disabled="">
			            </div>
					</div>
					<div class="col-md-2">
						<label for="">CI</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control" name="ci" placeholder="CI" value="{{ $repartidor->ci }}" disabled="">
			            </div>
					</div>
					<div class="col-sm-1">
                      <!-- text input -->
                      <div class="form-group">
                        <label>EXPEDIDO:</label>
                        <select name="expedido" id="" class="form-control">
                          <option value="LP" @if (old('expedido') == "LP") {{ 'selected' }} @endif>LP</option>
                          <option value="CB" @if (old('expedido') == "CB") {{ 'selected' }} @endif>CB</option>
                          <option value="SC" @if (old('expedido') == "SC") {{ 'selected' }} @endif>SC</option>
                          <option value="PT" @if (old('expedido') == "PT") {{ 'selected' }} @endif>PT</option>
                          <option value="CH" @if (old('expedido') == "CH") {{ 'selected' }} @endif>CH</option>
                          <option value="TJ" @if (old('expedido') == "TJ") {{ 'selected' }} @endif>TJ</option>
                          <option value="BN" @if (old('expedido') == "BN") {{ 'selected' }} @endif>BN</option>
                          <option value="PN" @if (old('expedido') == "PN") {{ 'selected' }} @endif>PN</option>
                          <option value="OR" @if (old('expedido') == "OR") {{ 'selected' }} @endif>OR</option>
                          <option value="OTRO" @if (old('expedido') == "OTRO") {{ 'selected' }} @endif>OTRO</option>
                        </select>
                      </div>
                    </div>
					<div class="col-md-3">
						<label for="">TELEFONO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control" name="telefono" placeholder="Telefono" value="{{ $repartidor->telefono }}" disabled="">
			            </div>
					</div>
					<div class="col-md-6">
						<label for="">DIRECCION</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control" name="direccion" placeholder="Dirección" value="{{ $repartidor->direccion }}" disabled="">
			            </div>
					</div>	
					<div class="col-md-12 box-header">
							<h3 class="box-title">Cuenta de Usuario</h3>
						</div>				
					<div class="col-md-6">
						<label for="">USUARIO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>

			                <select name="user_id" id="" class="form-control" disabled="">
			                	@foreach($usuarios as $us)
			                	<option value="{{$us->id}}" {{ ($us->id === $repartidor->user_id)?'selected':'' }}>{{$us->email}}</option>
			                	@endforeach
			                </select>
			            </div>
					</div>
					<div class="col-md-12">
						<br>
						<a href="{{route('repartidor.index')}}" class="btn btn-primary float-right">volver</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>


@endsection