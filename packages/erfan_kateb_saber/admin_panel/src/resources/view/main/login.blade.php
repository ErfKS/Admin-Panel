@extends('layout::body')
@section('title')
    Login
@endsection

@section('content')
    {{--<form action="{{route('admin_panel.doLogin')}}" method="post" class="mx-5">
        @csrf
        <label for="username">:نام کاربری</label>
        <input type="text" id="username" placeholder="username" name="username" value="{{old('username')}}" class="form-control my-1">
        @error('username')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        <label for="password" class="mt-5">:رمز</label>
        <input type="password" id="password" placeholder="password" name="password" class="form-control my-1">
        @error('password')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-primary my-1">Login</button>
    </form>--}}

    <div class="mt-5">
        @include('layout::addManualForm',[
                    'method'=>'post',
                    'csrf'=> true,
                    'action' => route('admin_panel.doLogin'),
                    'inputs'=>[
                        'username' => [
                            'required' => true,
                            'type' => 'text',
                            'name' => 'username',
                            'label' => 'username',
                            'placeholder' => 'username'
                        ],
                        'password' => [
                            'required' => true,
                            'type' => 'password',
                            'name' => 'password',
                            'label' => 'password',
                            'placeholder' => 'password'
                        ],
                    ],
                    'submit_button'=>[
                        'text' => 'login',
                        'color' => 'primary'
                    ]
                ])
    </div>
@endsection
