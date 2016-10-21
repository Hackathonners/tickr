@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 auth-signin">
            <div class="auth-header text-center">
                <a class="logo" href="{{ url('/') }}">
                    <img alt="Brand" height="48" src="/images/logo.svg">
                </a>
                <h3>Sign in to Tickr</h3>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                @foreach($errors->all() as $error)
                {{ $error }}
                @endforeach
            </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-body">
                    <form role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="email" class="control-label">E-mail address</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-6">
                                    <label for="password" class="control-label">Password</label>
                                </div>
                                <div class="col-xs-6 text-right">
                                    <a class="small" href="{{ url('/password/reset') }}">
                                        Forgot password?
                                    </a>
                                </div>
                            </div>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-block btn-primary">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>
            <div class="text-center text-muted small">
                Built with &hearts; by <a href="//hackathonners.org">Hackathonners
            </div>
        </div>
    </div>
</div>
@endsection
