@extends('layouts.home')

@section('pageTitle', 'Login')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @if(Request::routeIs('login-for-upload'))
                <div class="col-md-8">
                    <h4>
                        In order to upload data you must have an account on Materials Commons. If you don't have an
                        account
                        then please <a href="{{route('register-for-upload')}}">register</a> to create one.
                    </h4>
                    <br>
                </div>
            @endif
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body inner-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-tilte mb-0">{{ __('Login') }}</h4>
                        </div>
                        <hr class="border border-primary border-1 opacity-75 mb-4">
                        <form method="POST" action="{{$routeToUse}}">
                            @csrf

                            <div class="mb-3 row">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-end">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email" value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <div class="password-toggle-container">
                                        <input id="password" type="password"
                                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                               name="password" required>
                                        <div class="input-group-append">
                                            <button class="password-toggle" type="button" id="togglePassword">
                                                <i class="fas fa-eye" id="eyeIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember"
                                               id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Login') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#togglePassword').on('click', function () {
                const passwordField = $('#password');
                const eyeIcon = $('#eyeIcon');

                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .password-toggle-container {
            position: relative;
        }

        .password-toggle-container input {
            padding-right: 40px;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #495057;
        }

        .password-toggle:focus {
            outline: none;
        }
    </style>
@endpush
