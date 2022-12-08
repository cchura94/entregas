@extends('layouts.admin')

@section('contenido')
<div class="row">
      <div class="col-md-12">
        <div class="card card-warning">
          <div class="card-header">
            <h3 class="card-title">Detalles Cliente</h3>
            <a href="{{route('tienda.index')}}" class="btn btn-primary float-right">volver</a>
          </div>
              <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
            <div class="col-sm-6">
              <label for="">NOMBRE</label>
              <div class="input-group">
                <span class="input-group-addon"></span>
                <input type="text" class="form-control" name="nombre" placeholder="Nombre" value="{{ $tienda->nombre }}" disabled="">
              </div>
            </div>
            <div class="col-sm-6">
              <label for="">LOGO</label>
              <div class="input-group">
                <span class="input-group-addon"></span>
                <input type="text" class="form-control" name="logo" placeholder="logo" value="{{ $tienda->logo }}" disabled="">
              </div>
            </div>
            <div class="col-md-12">
              <label for="">DESCRIPCION</label>
              <textarea name="descripcion" class="form-control" placeholder="Descripcion..." cols="30" rows="1" disabled="">{{ $tienda->descripcion }}</textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

          <div class="card card-warning">
            <div class="card-header">
              <h3 class="card-title">Lista Sucursal</h3>
                <a class="accion btn btn-success btn-xs float-right" href="{{route('exportar_sucursales_excel', $tienda->id)}}" onclick="">Descargar Excel</a>
                <span class="delimitador  float-right">|</span>
                
                <a class="accion btn btn-primary btn-xs float-right" href="{{route('reporte_sucursales', $tienda->id)}}" target="_blank">Descargar PDF</a>
                <span class="delimitador  float-right">|</span>
                <a href="{{route('add_sucursal', $tienda->id)}}" class="btn btn-outline-primary float-right">Agregar Sucursal</a>
                  
            </div>
            <!-- /.box-header -->
            <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            	<table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>NOMBRE</th>
                  <th>DIRECCION</th>
                  <th>LATITUD</th>
                  <th>LONGITUD</th>
                  <th>TELF. CONTACTO</th>
                  <th>NOM. CONTACTO</th>
                  <th>ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                	@foreach($tienda->sucursales as $suc)
                <tr>
                  <td>{{$suc->nombre}}</td>
                  <td>{{$suc->direccion}}</td>
                  <td>{{$suc->latitud}}</td>
                  <td>{{$suc->longitud}}</td>
                  <td>{{$suc->telefono_c}}</td>
                  <td>{{$suc->nombre_c}}</td>
                  <td>
                  	<a href="{{route('sucursal.show', $suc->id)}}" class="btn btn-success btn-xs">ver</a>
                  	<a href="{{route('sucursal.edit', $suc->id)}}" class="btn btn-warning btn-xs">editar</a>
                  
                  	<form action="{{route('sucursal.destroy', $suc->id)}}" method="post">
                  		@csrf
                  		@Method('DELETE')
                  		<input type="submit" value="eliminar" class="btn btn-danger btn-xs">
                  		
                  	</form>
                  </td>
                </tr>
                @endforeach
                </tbody>
              </table>
            </div>
        </div>

@endsection
