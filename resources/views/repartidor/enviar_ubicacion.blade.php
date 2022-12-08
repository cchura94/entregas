@extends('layouts.app')

@section('script')


@endsection

@section('content')    
    
    
    <h1 class="text-center">Enviar mi Ubicación Actual</h1>
    
    
    <sent-message v-on:messagesent="addMessage" :user="{{ Auth::user() }}"></sent-message>

    <message :messages="messages"></message>

@endsection
