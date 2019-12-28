@extends('default')

@section('title')
    Home | Find the Force
@endsection

@section('contenido')
<section class="container d-flex flex-grow-1 justify-content-center align-items-center" id="contenido">
    <div class="row">
        <div class="col-md d-flex flex-column justify-content-center">
            <h1 class="glitch">Find<br>the Force</h1>
            <div class="botones d-flex">
                <a href="{{ route('register') }}" class="btn btn-outline-light ml-0">Personajes Favoritos</a>
            </div>
        </div>
        <div class="col-md">
            <div class="card card-long">
                <div class="card-body">
                    <h2>Películas</h2>
                    <ul>
                        @foreach ($r['results'] as $film)
                            @php
                                $url = explode('/', $film['url']);
                            @endphp
                            <li><a href="/pelicula/{{$url[5]}}">{{$film['title']}}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection