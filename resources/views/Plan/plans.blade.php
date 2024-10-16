@extends('layouts.app')

@section('content')
<div class="container my-5 plans_cards">
    <h3 class="text-center mb-4">Plan Details</h3>

    <div class="alert alert-info text-center d-none">
        तसेच अपलोड केलेल्या पेमेंट, या सकाळी ११.०० ते संध्याकाळी ०४.०० दरम्यान Activate केल्या जातात. 
        या वेळेच्या नंतर पेमेंट केली असल्यास आपले खाते दुसऱ्या दिवशी सकाळी ११.०० ते संध्याकाळी ०४.०० मध्ये सुरु केले जातील.
    </div>

    <div class="plan-cards d-flex flex-wrap justify-content-center">

        <div class="card m-2">
            <div class="header">
                <h2 class="mb-5">Free Plan</h2>
            </div>
            <div class="circle">0</div>
            <div class="content">
                <p><strong>Price:</strong> ₹0</p>
                <p><strong>Free Pack</strong> - Download 5 posts</p>
                <p>1 Installment for 1 Month</p>
                <p>1 Month <a href="https://www.walstartechnologies.com/" class="styled-link">walstartechnologies.com</a></p>
                @if (isset($subscription) && $subscription)
                    <a href="#" class="buy-button btn btn-primary already-subscribed">SUBSCRIBED</a>
                @else
                    <a href="/plans/scannerForm?plan=free" class="buy-button btn btn-primary">GET STARTED</a>
                @endif
            </div>
        </div>

                <!-- Three Months Pack -->
        <div class="card m-2">
            <div class="header">
                <h2 class="mb-5">Three Months Pack</h2>
            </div>
            <div class="circle">499</div>
            <div class="content">
                <p><strong>Price:</strong> ₹499</p>
                <p><strong>Basic Pack</strong> - 3 Months</p>
                <p>1 Installment for 3 Months</p>
                <p>3 Months <a href="https://www.walstartechnologies.com/" class="styled-link">walstartechnologies.com</a></p>
                @if (isset($subscription) && $subscription)
                    <a href="#" class="buy-button btn btn-primary already-subscribed">BUY NOW</a>
                @else
                    <a href="/plans/scannerForm?plan=three_months" class="buy-button btn btn-primary">BUY NOW</a>
                @endif
            </div>
        </div>
        <!-- Six Months Pack -->
        <div class="card m-2 d-none">
            <div class="header">
                <h2 class="mb-5">Six Months Pack</h2>
            </div>
            <div class="circle">699</div>
            <div class="content">
                <p><strong>Price:</strong> ₹699</p>
                <p><strong>Gold Pack</strong> - 6 Months</p>
                <p>2 Installments for Year</p>
                <p>6 Months <a href="https://www.walstartechnologies.com/" class="styled-link">walstartechnologies.com</a></p>
                @if (isset($subscription) && $subscription)
                    <a href="#" class="buy-button btn btn-primary already-subscribed">BUY NOW</a>
                @else
                    <a href="/plans/scannerForm?plan=six_months" class="buy-button btn btn-primary">BUY NOW</a>
                @endif
            </div>
        </div>

        <!-- One Year Pack -->
        <div class="card m-2">
            <div class="header"> 
                <h2 class="mb-5">One Year Pack</h2> 
            </div>
            <div class="circle">999</div>
            <div class="content">
                <p><strong>Price:</strong> ₹999</p>
                <p><strong>Diamond Pack</strong> - 12 Months</p>
                <p>1 Installment for Year</p>
                <p>1 Year <a href="https://www.walstartechnologies.com/" class="styled-link">walstartechnologies.com</a></p>
                <!-- <button class="buy-button">BUY NOW</button> -->
                <!-- <a href="/plans/scannerForm?plan=one_year" class="buy-button btn btn-primary">BUY NOW</a> -->
                @if (isset($subscription) && $subscription)
                    <a href="#" class="buy-button btn btn-primary already-subscribed">BUY NOW</a>
                @else
                    <a href="/plans/scannerForm?plan=one_year" class="buy-button btn btn-primary">BUY NOW</a>
                @endif
            </div>
        </div>
    </div>
    <div class="mt-5 text-center">
        <h5>Welcome As Partner!</h5>
        <p>Take a Screenshot and Upload All details & Message Us for Activation</p>
        <!-- <a href="/plans/scannerForm" class="upload-button btn btn-primary">Make a Payment</a> -->
    </div>
</div>
<footer class="footer text-center mt-5 plans-footer">
    <p class="mb-0 text-white">© 2024 Walstar. All Rights Reserved.</p>
</footer>
<script>
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        const formData = new FormData(this);
        
        // Display loading message or spinner
        const uploadButton = document.querySelector('button[type="submit"]');
        uploadButton.innerHTML = 'Uploading...';
        uploadButton.disabled = true; // Disable button to prevent multiple clicks

        fetch('{{ route('upload.payment') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message); // Show success message
            uploadButton.innerHTML = 'Upload Payment'; // Reset button text
            uploadButton.disabled = false; // Re-enable button
            this.reset(); // Clear the form
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'An error occurred. Please try again.',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
            uploadButton.innerHTML = 'Upload Payment'; // Reset button text
            uploadButton.disabled = false; // Re-enable button
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        // Select all "already-subscribed" buttons
        const subscribedButtons = document.querySelectorAll('.already-subscribed');

        subscribedButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default anchor behavior

                Swal.fire({
                    icon: 'info',
                    title: 'Subscription Alert',
                    text: 'You have already subscribed, your plan is active.',
                    confirmButtonText: 'OK'
                });
            });
        });
    });
</script>

@endsection
