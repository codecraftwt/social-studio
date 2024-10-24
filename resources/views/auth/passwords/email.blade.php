@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-4 mb-5">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <h1 class="text-center mb-4">Reset Password</h1>

                <div class="input-box">
                    <span class="icon"><i class='bx bx-envelope'></i></span>
                    <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <label>Email Address</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-log btn-lg">{{ __('Send Password Reset Link') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@if (session('status'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('status') }}',
        confirmButtonText: 'Okay'
    });
</script>
@endif
@endsection
