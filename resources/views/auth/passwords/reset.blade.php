@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-4 mb-5">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <h1 class="text-center mb-4">Reset Your Password</h1>
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="input-box">
                    <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    <label for="email">Email Address</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    <label for="password">Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                    <label for="password-confirm">Confirm Password</label>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-log">{{ __('Reset Password') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
