<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NotificacionSentEvent;
use App\Notificacion;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function fetch()
    {
        $user = Auth::user();
        if(Auth::user()->roles[0]->nombre == 'admin'){
            return Notificacion::with('user')->orderby('id', 'desc')->get()->take(6);
        }else{
            return Notificacion::where('user_id', $user->id)->with('user')->orderby('id', 'desc')->get()->take(12);

        }       
    }

    public function sentMessage(Request $request)
    {
        $user = Auth::user();

        /*$message = Notificacion::create([
            'mensaje' => $request->message,
            'user_id' => Auth::user()->id,
        ]);*/
        $message = new Notificacion;
        $message->mensaje = $request->mensaje;
        $message->user_id = Auth::user()->id;
        $message->latitud = $request->latitud;
        $message->longitud = $request->longitud;
        $message->save();

        broadcast(new NotificacionSentEvent($user, $message))->toOthers();
    }
}
