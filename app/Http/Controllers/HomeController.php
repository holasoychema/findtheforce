<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Peticiones;
use App\Actor;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    protected $peticiones;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Peticiones $peticiones)
    {
        $this->middleware('auth');
        $this->peticiones = $peticiones;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    
        $r = $this->peticiones->peliculas();

        return view('home', compact('r'));
    }

    public function show($id) {

        $pelicula = $this->peticiones->pelicula($id);
            
        $actores = $this->peticiones->actoresDe($pelicula);

        $favoritos = $this->peticiones->favoritos($this->getFavoritos());

        $fav = [];
        foreach($favoritos as $favorito) {
            array_push($fav, $favorito['url']);
        }

        return view('pelicula', compact('pelicula', 'actores', 'favoritos', 'fav'));
    }

    public function favoritos() {
        $actores = $this->peticiones->favoritos($this->getFavoritos());

        $fav = [];
        foreach($actores as $favorito) {
            array_push($fav, $favorito['url']);
        }

        return view('favoritos', compact('actores', 'fav'));
    }

    public function getFavoritos() {
        $f = [];
        foreach(Auth::user()->favoritos as $favorito) {
            array_push($f, $favorito->api_id);
        }

        return $f;
    }

    public function addFav($id) {
        if(empty(Actor::where('api_id', $id)->get()[0])) {
            $actor = new Actor;
            $actor->api_id = $id;
            $actor->save();

            Auth::user()->favoritos()->attach($actor->id);
        } else {
            $actor = Actor::where('api_id', $id)->get()[0];
            if(in_array($actor->api_id, $this->getFavoritos())) {
                Auth::user()->favoritos()->detach($actor->id);
            } else {
                Auth::user()->favoritos()->attach($actor->id);
            }
        }
        return back();
    }
}
