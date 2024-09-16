<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Home</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Custom CSS -->
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: url('{{ asset('images/background.jpg') }}') no-repeat center center fixed;
            background-size: cover;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
        }
        .category-name {
            font-size: 1.1rem;
            font-weight: 500;
            color: #343a40;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            padding: 10px;
            border-radius: 0.375rem;
            width: 200px;
        }
        .category-name:hover {
            background-color: #e9ecef;
            color: #007bff;
        }
        .category-name.selected {
            background-color: #957474ad;
            color: #fff;
        }
        .img-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .img-gallery img {
            flex: 1 1 calc(33.333% - 10px);
            max-width: 100%;
            height: auto;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .list-unstyled {
            padding-left: 0;
        }
        .badge {
            font-size: 0.875rem;
            padding: 0.375em 0.75em;
            border-radius: 0.25rem;
            background-color: #28a745;
            color: #fff;
        }
        .mb-2 { 
            margin-bottom: 0.5rem;
        }
        .post-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .post-item {
            flex: 1 1 calc(50% - 15px);
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 10px;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
        }
        .post-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 0.375rem;
            margin-bottom: 10px;
        }
        .post-explanation {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-clamp: 3;
            margin-bottom: 10px;
        }
        .full-explanation {
            display: none;
        }
        .view-more {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }
        .view-less {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
        }
        .download-btn {
            cursor: pointer;
            color: #007bff;
            text-decoration: underline;
            display: inline-block;
            margin-top: 10px;
        }

        /* New CSS for ticket section */
        .ticket-section {
            background-color: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
        }
        .ticket-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }
        .ticket {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 20px;
            width: calc(33.333% - 20px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: background-color 0.3s, transform 0.3s;
        }
        .ticket:hover {
            background-color: #e9ecef;
            transform: scale(1.03);
        }
        .ticket-title {
            font-size: 1.25rem;
            margin-bottom: 10px;
        }
        .ticket-price {
            font-size: 1.5rem;
            color: #007bff;
            margin-bottom: 15px;
        }
        .ticket-description {
            font-size: 1rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <!-- Header -->
    @include('layouts.header')

    <!-- Ticket Section -->
    <div class="ticket-section">
        <div class="container">
            <div class="ticket-container">
                <div class="ticket">
                    <h3 class="ticket-title">Basic Plan</h3>
                    <p class="ticket-price">FREE</p>
                    <p class="ticket-description">Basic features for personal use. Limited access to premium content.</p>
                    <a href="#" class="btn btn-primary" id="select-free-plan">Select Plan</a>
                </div>
                <div class="ticket">
                    <h3 class="ticket-title">Standard Plan</h3>
                    <p class="ticket-price">$10/month</p>
                    <p class="ticket-description">Access to most features. Includes some premium content.</p>
                    <a href="#" class="btn btn-primary" id="select-standard-plan">Select Plan</a>
                </div>
                <div class="ticket">
                    <h3 class="ticket-title">Premium Plan</h3>
                    <p class="ticket-price">$20/month</p>
                    <p class="ticket-description">Full access to all features and premium content. Best value for your needs.</p>
                    <a href="#" class="btn btn-primary" id="select-premium-plan">Select Plan</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container mt-4">
            <div class="row">
                <!-- Categories Sidebar -->
                <div class="col-md-3 mb-3">
                    <div class="card shadow-sm border-light rounded h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Select Categories</h5>
                            <ul class="list-unstyled">
                                @foreach ($categories as $category)
                                    <li class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="category-name" data-id="{{ $category->id }}">{{ $category->name }}</span>
                                            @if ($category->isNew)
                                                <span class="badge bg-success text-white">New</span>
                                            @endif
                                        </div>
                                        <div class="category-description mt-2">
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Posts Section -->
                <div class="col-md-9 mb-3 overflow-scroll">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">View Posts</h5>
                            <div id="posts-content" class="post-container">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Gallery -->
            <div class="img-gallery mb-4">
                <img src="{{ asset('storage/images/images2.jpg') }}" alt="Image 1">
                <img src="{{ asset('storage/images/images3.jpg') }}" alt="Image 2">
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('layouts.footer')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JavaScript for AJAX -->
    <script>
        $(document).ready(function() {
            $('.category-name').click(function() {
                var categoryId = $(this).data('id');

                $('.category-name').removeClass('selected');
                $(this).addClass('selected');

                $.ajax({
                    url: '{{ route('posts.byCategory') }}',
                    method: 'GET',
                    data: {
                        category_id: categoryId
                    },
                    success: function(response) {
                        var posts = response.posts;
                        var headerPath = response.headerPath ? '{{ asset('storage') }}/' + response.headerPath : null;
                        var footerPath = response.footerPath ? '{{ asset('storage') }}/' + response.footerPath : null;

                        var content = '';
                        posts.forEach(function(post) {
                            var truncatedExplanation = post.post_explanation.substring(0, 100);
                            var fullExplanation = post.post_explanation;

                            content += '<div class="post-item">';
                            content += '<h5>' + post.post_title + '</h5>';
                            content += '<p>Posted at: ' + new Date(post.created_at).toLocaleString() + '</p>';
                            content += '<div class="post-content">';
                            content += '<img src="' + '{{ asset('storage') }}/' + post.post_image + '" alt="' + post.post_title + '">';
                            content += '<div class="post-explanation">' + truncatedExplanation + '...</div>';
                            content += '<div class="full-explanation">' + fullExplanation + '</div>';
                            content += '<p class="view-more">View More</p>';
                            content += '<a class="download-btn" href="#" data-image="' + '{{ asset('storage') }}/' + post.post_image + '" data-header="' + headerPath + '" data-footer="' + footerPath + '" data-post-id="' + post.id + '">Download Post</a>';
                            content += '</div>';
                            content += '</div>';
                        });
                        $('#posts-content').html(content);
                    },
                    error: function() {
                        alert('Failed to load posts.');
                    }
                });

                $(document).on('click', '.view-more', function() {
                    var explanation = $(this).siblings('.post-explanation');
                    var fullExplanation = $(this).siblings('.full-explanation');

                    explanation.hide();
                    fullExplanation.show();
                    $(this).text('View Less').removeClass('view-more').addClass('view-less');
                });

                $(document).on('click', '.view-less', function() {
                    var explanation = $(this).siblings('.post-explanation');
                    var fullExplanation = $(this).siblings('.full-explanation');

                    explanation.show();
                    fullExplanation.hide();
                    $(this).text('View More').removeClass('view-less').addClass('view-more');
                });
            });

            $(document).on('click', '.download-btn', function(e) {
                e.preventDefault();
                var imageUrl = $(this).data('image');
                var headerPath = $(this).data('header');
                var footerPath = $(this).data('footer');
                var postId = $(this).data('post-id');
                createAndDownloadImage(imageUrl, headerPath, footerPath, postId);
            });
        });

        var isLoggedIn = {!! json_encode(auth()->check()) !!};
        var userId = @json(auth()->check() ? auth()->user()->id : null);

        function createAndDownloadImage(imageUrl, headerPath, footerPath, postId) {
        if (!isLoggedIn) {
            alert('You need to be logged in to download this post.');
            window.location.href = '/login'; 
            return;
        }

        $.ajax({
            url: '{{ route('check.download.limit') }}', // Ensure this matches the route name
            method: 'POST',
            data: {
                user_id: userId, // Current user ID
                _token: '{{ csrf_token() }}' // CSRF token for security
            },
            success: function(response) {
                if (response.exceededLimit) {
                    alert('You have reached the download limit for your subscription. Please choose a subscription plan to download more posts.');
                    window.location.href = response.redirectUrl;
                    return;
                }

                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                var img = new Image();
                var header = new Image();
                var footer = new Image();

                var headerLoaded = false;
                var footerLoaded = false;

                img.crossOrigin = 'Anonymous';
                header.crossOrigin = 'Anonymous';
                footer.crossOrigin = 'Anonymous';

                img.src = imageUrl;

                img.onload = function() {
                    console.log('Main image loaded');
                    canvas.width = img.width;
                    canvas.height = img.height;

                    if (headerLoaded) {
                        canvas.width = Math.max(canvas.width, header.width);
                        canvas.height += 20;
                    }

                    if (footerLoaded) {
                        canvas.width = Math.max(canvas.width, footer.width);
                        canvas.height += 20;
                    }

                    if (headerLoaded) {
                        ctx.drawImage(header, 0, 0, canvas.width, 20);
                    }

                    ctx.drawImage(img, 0, headerLoaded ? 20 : 0);

                    if (footerLoaded) {
                        ctx.drawImage(footer, 0, canvas.height - 20, canvas.width, 20);
                    }

                    var link = document.createElement('a');
                    link.href = canvas.toDataURL('image/png');
                    link.download = 'post-image.png';
                    link.click();

                    $.ajax({
                        url: '{{ route('download.record') }}',
                        method: 'POST',
                        data: {
                            user_id: userId,
                            post_id: postId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log('Download record saved successfully.');
                        },
                        error: function() {
                            console.error('Failed to save download record.');
                        }
                    });
                };

                img.onerror = function() {
                    console.error('Failed to load main image');
                };

                if (headerPath) {
                    header.src = headerPath;

                    header.onload = function() {
                        console.log('Header loaded');
                        headerLoaded = true;

                        if (!footerLoaded) {
                            canvas.height += 20; 
                        }
                    };

                    header.onerror = function() {
                        console.error('Failed to load header image');
                    };
                }

                if (footerPath) {
                    footer.src = footerPath;

                    footer.onload = function() {
                        console.log('Footer loaded');
                        footerLoaded = true;
                        if (!headerLoaded) {
                            canvas.height += 20; 
                        }
                    };

                    footer.onerror = function() {
                        console.error('Failed to load footer image');
                    };
                }
            },
            error: function() {
                console.error('Failed to check download limit.');
            }
        });
    }


        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('select-free-plan').addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = '/register?plan=free'; // Redirect to registration form with plan type
            });

            document.getElementById('select-standard-plan').addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = '/register?plan=standard'; // Redirect to registration form with plan type
            });

            document.getElementById('select-premium-plan').addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = '/register?plan=premium'; // Redirect to registration form with plan type
            });
        });
    </script>
    <script src="https://js.stripe.com/v3/"></script>
<script>
// document.addEventListener('DOMContentLoaded', function() {
//     document.getElementById('select-standard-plan').addEventListener('click', function(e) {
//         e.preventDefault();

//         fetch('/create-checkout-session', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
//             },
//             body: JSON.stringify({ plan: 'standard' })
//         })
//         .then(response => response.json())
//         .then(sessionId => {
//             const stripe = Stripe('pk_test_jVXQ1zu3vGdTLPUYZ9r1A2NR'); // Your Stripe publishable key
//             return stripe.redirectToCheckout({ sessionId: sessionId.id });
//         })
//         .catch(error => console.error('Error:', error));
//     });

//     document.getElementById('select-premium-plan').addEventListener('click', function(e) {
//         e.preventDefault();

//         fetch('/create-checkout-session', {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/json',
//                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
//             },
//             body: JSON.stringify({ plan: 'premium' })
//         })
//         .then(response => response.json())
//         .then(sessionId => {
//             const stripe = Stripe('pk_test_jVXQ1zu3vGdTLPUYZ9r1A2NR'); // Your Stripe publishable key
//             return stripe.redirectToCheckout({ sessionId: sessionId.id });
//         })
//         .catch(error => console.error('Error:', error));
//     });
// });
</script>


    <!-- Bootstrap JS Bundle (including Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
