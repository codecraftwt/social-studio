@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6 col-lg-4 mb-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <h1 class="text-center mb-4">Welcome back. Please login to your account</h1>
                    <div class="input-box">
                        <span class="icon"><i class='bx bx-user'></i></span>
                        <input type="email" name="email" required autocomplete="email" class="@error('email') is-invalid @enderror">
                        <label>Email Address</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class='bx bx-lock'></i></span>
                        <input type="password" name="password" required autocomplete="current-password" class="@error('password') is-invalid @enderror">
                        <label>Password</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-log ">{{ __('Login') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
