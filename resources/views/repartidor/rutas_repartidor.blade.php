@extends('layouts.app')

@section('script')

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Mis Rutas para entrega</div>

                <div class="card-body">
                @if (session('ok'))
                    <div class="alert alert-success">
                        {{ session('ok') }}
                    </div>
                @endif

                  <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>                  
                  <th>NOMBRE</th>
                  <th>DIRECCION</th>
                  <th>COD. PEDIDO</th>
                  <th>ESTADO</th>
                  <th>ACCIONES</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($entregas as $ent)
                <tr>
                  <td>{{$ent->pedido->sucursal->tienda->nombre}}</td>
                  <td>{{$ent->pedido->sucursal->direccion}}</td>
                  <td>{{$ent->pedido->cod_pedido}}</td>
                  <td>
                    @if($ent->estado === 0)
                    <span class="badge badge-danger">Pendiente</span>
                    @elseif($ent->estado === 1)
                    <span class="badge badge-warning">En Proceso</span>
                    @elseif($ent->estado === 2)
                    <span class="badge badge-success">Entregado</span>
                    @elseif($ent->estado === 3)
                    <span class="badge badge-secondary">Demorado</span>
                    @else
                    <span class="badge badge-danger">Cancelado</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{route('ver_ruta_asignada', $ent->id)}}" class="btn btn-success">ver más detalles</a>
                  </td>
                </tr>
                @endforeach
                
                </tfoot>
              </table>

           

                </div>
            </div>
        </div>
    </div>
</div>


@endsection

