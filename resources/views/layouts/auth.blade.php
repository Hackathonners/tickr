@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 auth-signin">
            <div class="auth-header text-center">
                <a class="logo" href="{{ url('/') }}">
                    <img alt="Brand" height="48" src="/images/logo.svg">
                </a>
                <h3>@yield('auth.title')</h3>
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

            @yield('auth.content')

            <div class="auth-footer text-center text-muted small">
                Built with &hearts; by <a href="//hackathonners.org">Hackathonners
            </div>
        </div>
    </div>
</div>
@endsection
