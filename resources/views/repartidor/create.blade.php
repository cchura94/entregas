@extends('layouts.admin')

@section('contenido')

<div class="row">
	<div class="col-xs-12">

			<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Registro Nuevo Repartidor</h3>
                <a href="{{route('repartidor.index')}}" class="btn btn-info float-right">Volver</a>
              </div>
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
			<!-- /.box-header -->
				<form action="{{route('repartidor.store')}}" method="POST" data-parsley-validate>
					@csrf
					<div class="row">
					<div class="col-md-4">
						<label for="">NOMBRES</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" placeholder="Nombres" value="{{ old('nombre') }}" required>
			            </div>
					</div>
					<div class="col-md-4">
						<label for="">APELLIDO PATERNO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('paterno') is-invalid @enderror" name="paterno" placeholder="Ap. Paterno" value="{{ old('paterno') }}" required>
			            </div>
					</div>
					<div class="col-md-4">
						<label for="">APELLIDO MATERNO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control" name="materno" placeholder="Ap. Materno" value="{{ old('materno') }}">
			            </div>
					</div>
					<div class="col-md-2">
						<label for="">CI</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="number" class="form-control @error('ci') is-invalid @enderror" name="ci" placeholder="CI" value="{{ old('ci') }}" required>
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
			                <input type="number" class="form-control" name="telefono" placeholder="Telefono" value="{{ old('telefono') }}" required>
			            </div>
					</div>
					<div class="col-md-6">
						<label for="">DIRECCION</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" placeholder="Dirección" value="{{ old('direccion') }}" required>
			            </div>
					</div>
					<!--div class="col-md-6">
						<label for="">USUARIO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <select name="user_id" id="" class="form-control">
			                	@foreach($usuarios as $us)
			                	<option value="{{$us->id}}">{{$us->email}}</option>
			                	@endforeach
			                </select>
			            </div>
					</div-->
						<div class="col-md-12 box-header">
							<h3 class="box-title">Cuenta de Usuario</h3>
						</div>
						<div class="col-md-6">
							<label for="">CORREO</label>
							<div class="input-group">
								<span class="input-group-addon"></span>
								<input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Ingrese su correo" value="{{ old('email') }}" required>
							</div>
						</div>
						<div class="col-md-6">
							<label for="">CONTRASEÑA</label>
							<div class="input-group">
								<span class="input-group-addon"></span>
								<input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Ingrese su Contraseña" required>
							</div>
						</div>
					<div class="col-md-12">
						<br>
						<input type="submit" value="Guardar" class="btn btn-primary float-right">
					</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


@endsection
