@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-4 mb-5">
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                @csrf

                <h1 class="text-center mb-4">Register</h1>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-user'></i></span>
                    <input id="name" type="text" class="@error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                    <label for="name">Name</label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-envelope'></i></span>
                    <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    <label for="email">Email Address</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-lock'></i></span>
                    <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    <label for="password">Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-lock'></i></span>
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                    <label for="password-confirm">Confirm Password</label>
                </div>

                <div class="input-box">
                    <select id="plan" name="plan" required>
                        <option value="">Subscription Plan</option>
                        <option value="free" {{ old('plan') === 'free' ? 'selected' : '' }}>Free</option>
                        <option value="standard" {{ old('plan') === 'standard' ? 'selected' : '' }}>Standard Plan ($10)</option>
                        <option value="premium" {{ old('plan') === 'premium' ? 'selected' : '' }}>Premium Plan ($20)</option>
                    </select>
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-home'></i></span>
                    <input id="address" type="text" name="address" value="{{ old('address') }}" required>
                    <label for="address">Address</label>
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-map'></i></span>
                    <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code') }}" required>
                    <label for="postal_code">Postal Pin Code</label>
                </div>

                <div class="input-box">
                    <span class="icon"><i class="bi bi-geo-alt-fill"></i></span>
                    <input id="current_location" type="text" name="current_location" value="{{ old('current_location') }}" required>
                    <label for="current_location">Current Location</label>
                </div>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-phone'></i></span>
                    <input id="mobile" type="text" name="mobile" value="{{ old('mobile') }}" required>
                    <label for="mobile">Mobile No.</label>
                </div>

                <div class="input-box2">
                    <label for="profile_pic">Profile Pic</label>
                    <input id="profile_pic" type="file" name="profile_pic" accept="image/*" required>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-log btn-lg">{{ __('Register') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
