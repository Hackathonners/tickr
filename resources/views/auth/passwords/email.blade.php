@extends('layouts.auth')

@section('auth.title')
Reset your password
@endsection

@section('auth.content')
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
@endsection
