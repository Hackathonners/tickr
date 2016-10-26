@extends('layouts.auth')

@section('auth.title')
Sign in to Tickr
@endsection

@section('auth.content')
<div class="panel panel-default">
    <div class="panel-body">
        <form role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}

            <div class="form-group">
                <label for="email" class="control-label">E-mail address</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus tabindex="1">
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="password" class="control-label">Password</label>
                    </div>
                    <div class="col-xs-6 text-right">
                        <a class="small" href="{{ url('/password/reset') }}">Forgot password?</a>
                    </div>
                </div>
                <input id="password" type="password" class="form-control" name="password" required tabindex="2">
            </div>

            <button type="submit" class="btn btn-block btn-primary" tabindex="3">Sign in</button>
        </form>
    </div>
</div>
@endsection
