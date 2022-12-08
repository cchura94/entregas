@extends('layouts.admin')

@section('title', 'Entregas')



@section('contenido')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">Bienvenido!</p>
                    
                    <h1 class="text-center">Notificaciónes Ubicacion</h1>
                    <message :messages="messages"></message>
    
                </div>
            </div>
        </div>
    </div>
@stop
