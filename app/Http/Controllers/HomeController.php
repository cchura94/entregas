<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
                
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if($user->repartidor){
                return redirect('/mis-entregas');        
            }else{
                return view('home');
                //return redirect('/admin');
            }
        }else{
            return view('home');
            //return redirect('/admin');
            //$role->usuario_creacion = -1;
        }

        
    }
}
