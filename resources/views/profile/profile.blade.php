@extends('layouts.app')

@section('content')
<div class="container my-5 user-records">
    <h3 class="text-center mb-4">Your Profile</h3>

    <div class="alert alert-info text-center mb-4">
        Here are your profile and records.
    </div>

    <h5 class="text-center">Profile Information</h5>
    
    <!-- Button to open the edit profile modal -->
    <div class="text-center mb-3">
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            Edit Profile
        </button>
    </div>

    <div class="profile-details mb-4">
        <ul class="list-group">
            <li class="list-group-item"><strong>Name:</strong> {{ $user->name }}</li>
            <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
            <li class="list-group-item"><strong>Address:</strong> {{ $user->address }}</li>
            <li class="list-group-item"><strong>Postal Code:</strong> {{ $user->postal_code }}</li>
            <li class="list-group-item"><strong>Current Location:</strong> {{ $user->current_location }}</li>
            <li class="list-group-item"><strong>Mobile:</strong> {{ $user->mobile }}</li>
            <li class="list-group-item"><strong>Profile Picture:</strong> 
                @if($user->profile_pic)
                    <img src="{{ Storage::url($user->profile_pic) }}" alt="Profile Picture" style="width: 100px;">
                @else
                    No profile picture uploaded.
                @endif
            </li>
        </ul>
    </div>

    <!-- Modal for editing profile -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProfileForm" action="{{ route('profile.update', Auth::user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="text-center mb-3">
                            @if(Auth::user()->profile_pic)
                                <img src="{{ asset('storage/' . Auth::user()->profile_pic) }}" alt="Profile Picture" class="img-fluid" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                            @else
                                <img src="{{ asset('default_profile_pic.png') }}" alt="Default Profile Picture" class="img-fluid" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                            @endif
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bx-user'></i></span>
                            <input type="text" name="name" value="{{ Auth::user()->name }}" required autocomplete="name" class="@error('name') is-invalid @enderror">
                            <label>Name</label>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bx-envelope'></i></span>
                            <input type="email" name="email" value="{{ Auth::user()->email }}" required autocomplete="email" class="@error('email') is-invalid @enderror">
                            <label>Email</label>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bx-pin'></i></span>
                            <input type="text" name="postal_code" value="{{ Auth::user()->postal_code }}" required autocomplete="postal_code" class="@error('postal_code') is-invalid @enderror">
                            <label>Postal Code</label>
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bx-home'></i></span>
                            <input type="text" name="address" value="{{ Auth::user()->address }}" required autocomplete="address" class="@error('address') is-invalid @enderror">
                            <label>Address</label>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bx-home'></i></span>
                            <input type="text" name="current_location" value="{{ Auth::user()->current_location }}" required autocomplete="current_location" class="@error('current_location') is-invalid @enderror">
                            <label>Current Location</label>
                            @error('current_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- <div class="input-box">
                            <span class="icon"><i class='bx bx-phone'></i></span>
                            <input type="text" name="mobile" value="{{ Auth::user()->mobile }}" required autocomplete="mobile" class="@error('mobile') is-invalid @enderror">
                            <label>Mobile</label>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> -->

                        <div class="input-box">
                            <span class="icon"><i class='bx bx-phone'></i></span>
                            <input type="text" name="mobile" value="{{ Auth::user()->mobile }}" required autocomplete="mobile" class="@error('mobile') is-invalid @enderror" 
                                pattern="^\+91[0-9]{10}$" title="Please enter a valid Indian mobile number starting with +91 and followed by 10 digits.">
                            <label>Mobile</label>
                            @error('mobile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-box">
                            <span class="icon"><i class='bx bx-image'></i></span>
                            <input type="file" name="profile_pic" accept="image/*" class="@error('profile_pic') is-invalid @enderror">
                            <label>Profile Picture</label>
                            @error('profile_pic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-log">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer text-center mt-5">
        <p class="mb-0 text-white">© 2024 Walstar. All Rights Reserved.</p>
    </footer>
    <script>
        var editProfileModal = document.getElementById('editProfileModal');

        editProfileModal.addEventListener('hidden.bs.modal', function () {
            document.getElementById('editProfileForm').reset();
        });
    </script>
@endsection
