@extends('layouts.auth')

@section('content')

                    <div class="register-box-body">
                    <p class="login-box-msg">Rēģistrēt jaunu lietotāju</p>
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}
                        <div class="form-group has-feedback {{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Kā Tevi godāt?">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="email" class="form-control" placeholder="ēPastiņš" name="email" value="{{ old('email') }}">
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
                        <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" placeholder="Vai neaizmisrsi paroli?" name="password_confirmation">
                            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-xs-7">
                              <div class="checkbox icheck">
                                <label>
                                  <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div> <a href="#">visam</a> piekrītu
                                </label>
                              </div>
                            </div><!-- /.col -->
                            <div class="col-xs-5">
                              <button type="submit" class="btn btn-primary btn-block btn-flat">Reģistrēties</button>
                            </div><!-- /.col -->
                    </div>
                    </form>
                    <a href="{{url('/login')}}" class="text-center">Ai, nē! Es tak jau reģistrēts!</a>
                  </div>
@endsection
