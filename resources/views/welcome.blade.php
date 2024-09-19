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
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    @include('layouts.header')

    <div class="custom-home-page">
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

        <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imagePreviewLabel">Preview Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="previewImage" src="" alt="Image Preview" class="img-fluid">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmDownload">Confirm Download</button>
                    </div>
                </div>
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
                        var headerPath = response.headerPath ? '{{ asset('storage') }}/' + response.headerPath : null;
                        var footerPath = response.footerPath ? '{{ asset('storage') }}/' + response.footerPath : null;
                        var posts = response.posts;

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
                            // content += '<div class="full-explanation">' + fullExplanation + '</div>';
                            // content += '<p class="view-more">View More</p>';
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
                url: '{{ route('check.download.limit') }}',
                method: 'POST',
                data: {
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
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

                    var headerLoaded = true;
                    var footerLoaded = true;

                    img.crossOrigin = 'Anonymous';
                    header.crossOrigin = 'Anonymous';
                    footer.crossOrigin = 'Anonymous';

                    img.src = imageUrl;

                    img.onload = function() {
                        console.log('Main image loaded');
                        var canvasHeight = img.height;
                        var canvasWidth = img.width;

                        if (headerLoaded) {
                            canvasHeight += header.height; // Add header height
                        }

                        if (footerLoaded) {
                            canvasHeight += footer.height; // Add footer height
                        }

                        // Set canvas dimensions
                        canvas.width = Math.max(canvasWidth, headerLoaded ? header.width : 0, footerLoaded ? footer.width : 0);
                        canvas.height = canvasHeight;

                        // Draw header if loaded
                        if (headerLoaded) {
                            ctx.drawImage(header, (canvas.width - header.width) / 2, 0); // Center header
                        }

                        // Draw main image
                        ctx.drawImage(img, (canvas.width - img.width) / 2, headerLoaded ? header.height : 0); // Center main image

                        // Draw footer if loaded
                        if (footerLoaded) {
                            ctx.drawImage(footer, (canvas.width - footer.width) / 2, canvas.height - footer.height); // Center footer
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

                            // Adjust canvas height
                            if (footerLoaded) {
                                canvas.height += footer.height; 
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

                            // Adjust canvas height
                            if (headerLoaded) {
                                canvas.height += header.height; 
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
