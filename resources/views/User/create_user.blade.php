@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add User</h2>

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="userForm">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-box">
                    <span class="icon"><i class='bx bx-user'></i></span>
                    <input type="text" name="name" id="name" required class=" @error('name') is-invalid @enderror" value="{{ old('name') }}">
                    <label>Name</label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box">
                    <span class="icon"><i class='bx bx-envelope'></i></span>
                    <input type="email" name="email" id="email" required class=" @error('email') is-invalid @enderror" value="{{ old('email') }}">
                    <label>Email</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-box">
                    <span class="icon"><i class='bx bx-lock'></i></span>
                    <input type="password" name="password" id="password" required class=" @error('password') is-invalid @enderror">
                    <label>Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box">
                    <span class="icon"><i class='bx bx-lock'></i></span>
                    <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                    <label for="password-confirm">Confirm Password</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-box">
                    <span class="icon"><i class='bx bx-home'></i></span>
                    <input type="text" name="address" id="address" required class=" @error('address') is-invalid @enderror" value="{{ old('address') }}">
                    <label>Address</label>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box">
                    <span class="icon"><i class='bx bx-map'></i></span>
                    <input type="text" name="postal_code" id="postal_code" required class=" @error('postal_code') is-invalid @enderror" value="{{ old('postal_code') }}">
                    <label>Postal Code</label>
                    @error('postal_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row mb-3">
           <div class="col-md-6">
                <div class="input-box">
                    <span class="icon"><i class="bi bi-geo-alt-fill"></i></span>
                    <input type="text" name="current_location" id="current_location" required class=" @error('current_location') is-invalid @enderror" value="{{ old('current_location') }}">
                    <label>Current Location</label>
                    @error('current_location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box">
                    <span class="icon"><i class='bx bx-phone'></i></span>
                    <input type="text" name="mobile" id="mobile" required class=" @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}">
                    <label>Mobile</label>
                    @error('mobile')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class=" row mb-3">
           <div class="col-md-6">
                <div class="input-box2">
                    <label for="profile_pic" class="form-label">Profile Picture</label>
                    <input type="file" class=" @error('profile_pic') is-invalid @enderror" id="profile_pic" name="profile_pic" accept="image/*">
                    @error('profile_pic')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-box">
                    <select class=" @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                        <option value="">Select a Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-large btn-log">Add User</button>
    </form>
</div>
@endsection
