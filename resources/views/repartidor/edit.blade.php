@extends('layouts.admin')

@section('contenido')

<div class="row">
	<div class="col-xs-12">

	<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Modificar Repartidor</h3>
                <a href="{{route('repartidor.index')}}" class="btn btn-primary float-right">Volver</a>
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
				<form action="{{route('repartidor.update', $repartidor->id)}}" method="POST" data-parsley-validate>
				<div class="row">
					@csrf
					@Method('PUT')
					<div class="col-md-4">
						<label for="">NOMBRES</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" placeholder="Nombres" value="{{ $repartidor->nombre }}" required>
			            </div>
					</div>
					<div class="col-md-4">
						<label for="">APELLIDO PATERNO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('paterno') is-invalid @enderror" name="paterno" placeholder="Ap. Paterno" value="{{ $repartidor->paterno }}" required>
			            </div>
					</div>
					<div class="col-md-4">
						<label for="">APELLIDO MATERNO</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control" name="materno" placeholder="Ap. Materno" value="{{ $repartidor->materno }}">
			            </div>
					</div>
					<div class="col-md-2">
						<label for="">CI</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('ci') is-invalid @enderror" name="ci" placeholder="CI" value="{{ $repartidor->ci }}" required>
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
			                <input type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" placeholder="Telefono" value="{{ $repartidor->telefono }}" required>
			            </div>
					</div>
					<div class="col-md-6">
						<label for="">DIRECCION</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <input type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" placeholder="Dirección" value="{{ $repartidor->direccion }}" required>
			            </div>
					</div>
					<div class="col-md-12 box-header">
							<h3 class="box-title">Cuenta de Usuario</h3>
						</div>
					<div class="col-md-6">
						<label for="">USUARIO ACTUAL</label>
						<div class="input-group">
			                <span class="input-group-addon"></span>
			                <select name="user_id" id="" class="form-control @error('user_id') is-invalid @enderror" reqiured>
			                	@foreach($usuarios as $us)
			                	<option value="{{$us->id}}" {{ ($us->id === $repartidor->user_id)?'selected':'' }}>{{$us->email}}</option>
			                	@endforeach
			                </select>
			            </div>
					</div>
					<div class="col-md-5">
					<label for="">MARCAR PARA CAMBIAR DATOS DE ACCESO AL SISTEMA </label>
						 
						<input type="checkbox" name="cambiar_usuario" id="check" onclick="toggle(this)" class="form-control"></div>
					<div class="row" id="cambiar">
						<div class="col-md-6">
							<label for="">Modificar Correo (No es obligatorio cambiar el correo)</label>
							<input type="email" name="email" value="{{ $repartidor->user->email }}" class="form-control">
						</div>
						<div class="col-md-6">
							<label for="">Asignar Nueva Contraseña (No es obligatorio cambiar la contraseña)</label>
							<div class="input-group">
								<span class="input-group-addon"></span>
								<input type="password" name="password" class="form-control">
							</div>
						</div>
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

@section('script')
<script>
	if($("#check").checked){
		$("#cambiar").hide();
	}else{
		$("#cambiar").hide();
	}
	

	function toggle(obj) {		
		if(obj.checked){
			$("#cambiar").show();
		}else{
			$("#cambiar").hide();
		}
		
	}
</script>
@endsection