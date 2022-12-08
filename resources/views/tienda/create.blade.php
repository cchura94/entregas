@extends('layouts.admin') @section("titulo", "Nuevo Cliente")
@section('contenido')

<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Registro de Nuevo Cliente</h3>
        <a
            href="{{ route('sucursal.index') }}"
            class="btn btn-warning float-right"
            >Volver</a
        >
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
        <form
            action="{{ route('tienda.store') }}"
            data-parsley-validate
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <label for="">NOMBRE</label>
                    <div class="input-group">
                        <input
                            type="text"
                            class="form-control @error('nombre') is-invalid @enderror"
                            name="nombre"
                            placeholder="Nombre"
                            required
                        />
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="">LOGO</label>
                    <div class="input-group">
                        <span class="input-group-addon"></span>
                        <input
                            type="file"
                            class="form-control @error('logo') is-invalid @enderror"
                            name="logo"
                            placeholder="logo"
                        />
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="">DESCRIPCION</label>
                    <textarea
                        name="descripcion"
                        class="form-control @error('descripcion') is-invalid @enderror"
                        placeholder="Descripcion..."
                    ></textarea>
                </div>
                <div class="col-md-12">
                    <br />
                    <input
                        type="submit"
                        value="Guardar"
                        class="btn btn-primary float-right"
                    />
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
