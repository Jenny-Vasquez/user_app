@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Bienvenido, {{ Auth::user()->name }} 
                </div>
                <div class="card-body">
                    <p>Aquí puedes administrar los usuarios de la plataforma.</p>
                    <p>Usa el menú de navegación para acceder a las diferentes opciones disponibles.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
