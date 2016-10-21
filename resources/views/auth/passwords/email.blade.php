@extends('layouts.app')

<!-- Main Content -->
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 auth-signin">
            <div class="auth-header text-center">
                <a class="logo" href="{{ url('/') }}">
                    <img alt="Brand" height="48" src="/images/logo.svg">
                </a>
                <h3>Reset your password</h3>
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

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-body">
                    <form role="form" method="POST" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="email" class="control-label">E-mail address</label>
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            <p class="help-block small text-muted">Enter your e-mail address and we will send you a link to reset your password.</p>
                        </div>

                        <button type="submit" class="btn btn-block btn-primary">
                            Send password reset e-mail
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
