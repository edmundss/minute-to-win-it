@extends('layouts.auth')

@section('content')
        <p class="login-box-msg">Hello! Kas Tu esi?</p>
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
          {!! csrf_field() !!}
          <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
            <input type="email" class="form-control" placeholder="Epasta adrese" name="email">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif

          </div>
          <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
            <input type="password" class="form-control" placeholder="Parole" name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember"> Atcerēties mani
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Ienākt</button>
            </div><!-- /.col -->
          </div>
        </form>

        <a href="{{ url('/password/reset') }}">Aizmirsu paroli</a><br>
        <a href="{{ url('/register') }}" class="text-center">Reģistrēties</a>
@stop