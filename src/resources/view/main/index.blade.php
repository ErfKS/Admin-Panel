@extends('layout::body')
@section('title')
    Main
@endsection

@section('content')
    @include('layout::nav',['navLinks'=>$navLinks])

    <h1 class="text-center">Menu</h1>
    <div class="text-center container">
        @foreach($buttons as $text => $property)
            <a href="{{$property['route']}}" class="btn {{$property['btn-color-class']}} text-center d-block my-5">{{$text}}</a>
        @endforeach
    </div>
@endsection
