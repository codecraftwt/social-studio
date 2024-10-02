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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    @include('layouts.header')
    <div class="custom-home-page">
        <div class="image-section image-crop text-center mb-4">
            <img src="{{ asset('storage/images/social_media.jpg') }}" alt="Promotional Image" class="img-fluid">
            <div class="overlay"></div> <!-- Overlay div -->
            <a href="{{ route('register') }}" class="btn btn-primary center-button btn-large">Register Now</a>
        </div>
        <!-- Ticket Section -->
        <div class="ticket-section d-none">
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

        <div class="category-section">
            <div class="container">
                <div class="category-container">
                    @foreach ($categories as $category)
                        <div class="parent_category" data-id="{{ $category->id }}" role="button" aria-label="Select category: {{ $category->name }}">
                            <img src="{{ asset('storage/' . ($category->category_image ?? 'images/images2.jpg')) }}" alt="{{ $category->name }}" class="category-image" />
                            <h3 class="category-title">{{ $category->name }}</h3>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        <!-- Main Content -->
        <div class="content">
            <div class="container-fluid mt-4 mb-5">
                <div class="row h-100">
                    <!-- Categories Sidebar -->
                    <div class="col-md-3 mb-3">
                        <div class="card shadow-sm border-light rounded">
                            <div class="card-body">
                                <h5 class="card-title mb-4 text-center">Select Categories</h5>
                                <hr style="border-top: 2px solid #004c72; margin-bottom: 20px;">
                                <ul class="list-unstyled" id="subcategories-list">
                                    @foreach ($SubCategory as $subcategory)
                                        <li class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="category-name" data-id="{{ $subcategory->id }}">
                                                    {{ $subcategory->sub_category_name }}
                                                </span>
                                                @if ($subcategory->isNew)
                                                    <span class="badge bg-success text-white">New</span>
                                                @endif
                                            </div>
                                            <div class="category-description mt-2"></div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Posts Section -->
                    <div class="col-md-9 mb-3">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <span class="filter-option" data-type="poster_a">Poster</span>
                                    <span class="filter-option" data-type="one_page">One Page</span>
                                    <span class="filter-option" data-type="two_page">Two Page</span>
                                    <span class="filter-option" data-type="link_a">Link</span>
                                </div>
                                <div id="posts-content" class="post-container flex-grow-1 d-none overflow-auto">
                                    <!-- Posts will be loaded here -->
                                </div>
                                <div class="post-item poster_a d-none">
                                    <div id="poster-content" class="row"></div> <!-- For displaying post images -->
                                    <div class="post-item empty_a d-none">No content available</div>
                                </div>
                                <div class="post-item one_page d-none">
                                    <div id="one-page-content"></div> <!-- For displaying one page PDF -->
                                </div>
                                <div class="post-item two_page d-none">
                                    <div id="two-page-content"></div> <!-- For displaying two page PDF -->
                                </div>
                                <div class="post-item link_a d-none">
                                    <div id="link-content"></div> <!-- For displaying links -->
                                </div>
                                <div class="post-item empty_a d-none">No content available</div>
                            </div>
                            <div id="full-image-display" class="d-none">
                                <form action="" method="get">
                                    <button type="submit" class="btn btn-secondary">Back</button>
                                </form>
                                <h2 id="full-image-title"></h2>
                                <img id="full-image" src="" alt="Full Size" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm"> <!-- or modal-md for medium size -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Downloading Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img id="downloadedImage" src="" alt="Generated Image" class="img-fluid">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="downloadButton" class="btn btn-primary">Download</button>
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
            const filterOptions = document.querySelectorAll('.filter-option');
            const postItems = document.querySelectorAll('.post-item');

            filterOptions.forEach(option => {
                option.addEventListener('click', () => {
                    // Hide all post items first
                    postItems.forEach(item => {
                        item.classList.add('d-none');
                    });

                    // Remove active class from all options
                    filterOptions.forEach(opt => {
                        opt.classList.remove('active');
                    });

                    // Get the type from the clicked option
                    const type = option.getAttribute('data-type');

                    // Display the selected post item
                    const selectedItem = document.querySelector(`.post-item.${type}`);
                    if (selectedItem) {
                        selectedItem.classList.remove('d-none');
                    } else {
                        // If no content is available for the selected type
                        document.querySelector('.post-item.empty_a').classList.remove('d-none');
                    }

                    // Add active class to the clicked option
                    option.classList.add('active');
                });
            });

            // Default to displaying Poster content
            const defaultOption = document.querySelector('.filter-option[data-type="poster_a"]');
            if (defaultOption) {
                defaultOption.click();
            }

            // $('.category-name').click(function() {
            $('#subcategories-list').on('click', '.category-name', function() {
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
                        // Clear previous content
                        $('#poster-content').empty();
                        $('#one-page-content').empty();
                        $('#two-page-content').empty();
                        $('#link-content').empty();
                        var headerPath = response.headerPath ? '{{ asset('storage') }}/' + response.headerPath : null;
                        var footerPath = response.footerPath ? '{{ asset('storage') }}/' + response.footerPath : null;
                        var posts = response.posts;

                        posts.forEach(function(post) {
                            // Display Post Image
                            if (post.post_image) {
                                $('#poster-content').append(`
                                    <div class="col-md-4 mb-4"> <!-- Use Bootstrap's grid system -->
                                        <div class="card">
                                            <img src="{{ asset('storage') }}/${post.post_image}" alt="${post.post_title}" class="card-img-top poster-image" style="width: 100%; height: auto;">
                                            <div class="card-body">
                                                <h5 class="card-title">${post.post_title}</h5>
                                                <button type="button" class="btn btn-log btn-primary download-btn" 
                                                    data-image="{{ asset('storage') }}/${post.post_image}" 
                                                    data-header="${headerPath}" 
                                                    data-footer="${footerPath}" 
                                                    data-post-id="${post.id}">Download Post</button>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            }

                            // Display Post PDF
                            if (post.post_pdf) {
                                $('#one-page-content').append(`
                                    <div>
                                        <h5>${post.post_title} (One Page)</h5>
                                        <iframe src="{{ asset('storage') }}/${post.post_pdf}" style="width:100%; height:500px;" frameborder="0"></iframe>
                                    </div>
                                `);
                                
                                $('#two-page-content').append(`
                                    <div>
                                        <h5>${post.post_title} (Two Page)</h5>
                                        <iframe src="{{ asset('storage') }}/${post.post_pdf}" style="width:100%; height:500px;" frameborder="0"></iframe>
                                    </div>
                                `);
                            }

                            // Display Link
                            if (post.link) {
                                $('#link-content').append(`
                                    <div class="link-card mt-2">
                                        <h5>${post.post_title}</h5>
                                        <a href="${post.link}" target="_blank">${post.link}</a>
                                    </div>
                                `);
                            }
                        });

                        // Show the default view after loading posts
                        document.querySelector('.filter-option[data-type="poster_a"]').click();
                    },
                    error: function() {
                        alert('Failed to load posts.');
                    }
                });
            });
            $(document).on('click', '.poster-image', function() {
                var imgSrc = $(this).attr('src');
                var title = $(this).siblings('.card-body').find('.card-title').text();

                $('#posts-content').addClass('d-none');
                $('.post-item').hide();
                $('#full-image').attr('src', imgSrc);
                $('#full-image-display').removeClass('d-none');
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

                        var imageDataUrl = canvas.toDataURL('image/png');
                        document.getElementById('downloadedImage').src = imageDataUrl;

                        document.getElementById('downloadedImage').src = imageDataUrl;

                        // Set up download button
                        document.getElementById('downloadButton').onclick = function() {
                            var link = document.createElement('a');
                            link.href = imageDataUrl;
                            link.download = 'post-image.png';
                            link.click();
                        };


                        var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                        imageModal.show();

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
            const categoryElements = document.querySelectorAll('.parent_category');

            categoryElements.forEach(category => {
                category.addEventListener('click', function() {
                    const categoryId = this.getAttribute('data-id');

                    // Remove active class from all categories
                    categoryElements.forEach(cat => cat.classList.remove('active'));

                    // Add active class to the clicked category
                    this.classList.add('active');

                    fetch(`/subcategories/${categoryId}`)
                        .then(response => response.json())
                        .then(data => {
                            const subcategoriesList = document.getElementById('subcategories-list');
                            subcategoriesList.innerHTML = ''; // Clear previous subcategories

                            data.forEach(subcategory => {
                                const li = document.createElement('li');
                                li.classList.add('mb-3');
                                li.innerHTML = `
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="category-name" data-id="${subcategory.id}">
                                            ${subcategory.sub_category_name}
                                        </span>
                                        ${subcategory.isNew ? '<span class="badge bg-success text-white">New</span>' : ''}
                                    </div>
                                    <div class="category-description mt-2"></div>
                                `;
                                subcategoriesList.appendChild(li);
                            });
                        });
                });

                // Keyboard navigation support
                category.tabIndex = 0; // Make div focusable
                category.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        this.click(); // Trigger click event on Enter or Space
                    }
                });
            });
        });


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
