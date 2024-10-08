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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Your custom CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    @include('layouts.header')
    @if(session('login_success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('login_success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        </script>
    @endif
    <div class="custom-home-page">
        <div class="image-section image-crop text-center mb-4">
            <img src="{{ asset('storage/images/social_media.jpg') }}" alt="Promotional Image" class="img-fluid">
            <div class="overlay"></div> <!-- Overlay div -->
            @if (!Auth::check())
                <a href="{{ route('register') }}" class="btn btn-primary center-button btn-large">Register Now</a>
            @endif
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
                <h2 class="category-section-header">Categories</h2>
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
                                <h5 class="card-title mb-4 text-center">Select Sub-Categories</h5>
                                <hr style="border-top: 2px solid #004c72; margin-bottom: 20px;">
                                <ul class="list-unstyled" id="subcategories-list">
                                    @foreach ($SubCategory as $subcategory)
                                        <li class="mb-3 {{ $loop->first ? 'selected' : '' }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="category-name {{ $loop->first ? 'selected' : '' }}" data-id="{{ $subcategory->id }}">
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
                                    <div class="empty_a">Select sub-category to view posts.</div>
                                </div>
                                <div class="post-item one_page d-none">
                                    <div id="one-page-content">
                                        <div id="pdf-container" class="container my-5">
                                            <div id="one-page-content" class="mb-4">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-item two_page d-none">
                                    <div id="two-page-content">
                                        <div id="pdf-container" class="container my-5">
                                            <div id="two-page-content" class="mb-4">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="post-item link_a d-none">
                                    <div id="link-content"></div> <!-- For displaying links -->
                                </div>
                                <div class="post-item empty_a d-none">No content available</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="fullImageModal" tabindex="-1" role="dialog" aria-labelledby="fullImageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"> <!-- or modal-md for medium size -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fullImageModalLabel">Downloading Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img id="full-image" src="" alt="full-image class="img-fluid">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg"> <!-- or modal-md for medium size -->
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
        function fetchUserDetails() {
            return fetch('/user-details', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token if necessary
                }
            })
            .then(userDetailsResponse => {
                if (!userDetailsResponse.ok) {
                    console.error('Failed to fetch user details');
                    return null; 
                }
                return userDetailsResponse.json();
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'You need to be logged in to download this post.',
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Go to Login'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/login';
                    }
                });
                return null;
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const firstCategory = document.querySelector('#subcategories-list .category-name.selected');
                if (firstCategory) {
                    firstCategory.click();
                }
            }, 10);
        });
        $(document).ready(function() {
            
            const filterOptions = document.querySelectorAll('.filter-option');
            const postItems = document.querySelectorAll('.post-item');

            filterOptions.forEach(option => {
                option.addEventListener('click', () => {
                    postItems.forEach(item => {
                        item.classList.add('d-none');
                    });

                    filterOptions.forEach(opt => {
                        opt.classList.remove('active');
                    });

                    const type = option.getAttribute('data-type');

                    const selectedItem = document.querySelector(`.post-item.${type}`);
                    if (selectedItem) {
                        selectedItem.classList.remove('d-none');
                    } else {
                        document.querySelector('.empty_a').classList.add('d-none');
                    }

                    option.classList.add('active');
                });
            });

            const defaultOption = document.querySelector('.filter-option[data-type="poster_a"]');
            if (defaultOption) {
                defaultOption.click();
            }

            // $('.category-name').click(function() {
            $('#subcategories-list').on('click', '.category-name', function() {
                var categoryId = $(this).data('id');
                document.querySelector('.empty_a').classList.add('d-none');
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
                                                <button type="button" class="btn btn-log btn-primary download-btn" 
                                                    data-image="{{ asset('storage') }}/${post.post_image}" 
                                                    data-header="${headerPath}" 
                                                    data-footer="${footerPath}" 
                                                    data-post-id="${post.id}"><i class="bi bi-printer-fill"></i></button>
                                                    <a href="{{ route('custom.image') }}?id=${post.id}" class="btn btn-log btn-primary" id="openCustomImage">
                                                        <i class="bi bi-sliders"></i>
                                                        Create Custom Image
                                                    </a>
                                            </div>
                                        </div>
                                    </div>
                                `);
                            }

                            if (post.post_pdf) {
                                $('#one-page-content').append(`
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">${post.post_title}</h5>
                                            <iframe src="{{ asset('storage') }}/${post.post_pdf}" style="width:100%; height:400px;" frameborder="0" class="mb-3"></iframe>
                                            <button class="btn btn-primary btn-log download-pdf-btn" 
                                                data-pdf="{{ asset('storage') }}/${post.post_pdf}">
                                                Download PDF
                                            </button>
                                        </div>
                                    </div>
                                `);
                                
                                $('#two-page-content').append(`
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">${post.post_title}</h5>
                                            <iframe src="{{ asset('storage') }}/${post.post_pdf}" style="width:100%; height:400px;" frameborder="0" class="mb-3"></iframe>
                                            <button class="btn btn-primary btn-log download-pdf-btn" 
                                                data-pdf="{{ asset('storage') }}/${post.post_pdf}">
                                                Download PDF
                                            </button>
                                        </div>
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

                        document.querySelector('.filter-option[data-type="poster_a"]').click();
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to load posts.',
                            icon: 'error',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Okay'
                        });
                    }
                });
            });
            $(document).on('click', '.poster-image', function() {
                var imgSrc = $(this).attr('src');
                var title = $(this).siblings('.card-body').find('.card-title').text();

                $('#full-image').attr('src', imgSrc);
                $('#fullImageModalLabel').text(title);
                $('#fullImageModal').modal('show'); 
            });
            $(document).on('click', '.download-btn', function(e) {
                e.preventDefault();
                var imageUrl = $(this).data('image');
                var headerPath = $(this).data('header');
                var footerPath = $(this).data('footer');
                var postId = $(this).data('post-id');
                createAndDownloadImage(imageUrl, postId);
            });
        });

        $(document).on('click', '.download-pdf-btn', async function(e) {
            e.preventDefault();
            var pdfUrl = $(this).data('pdf');

            if (!isLoggedIn) {
                    Swal.fire({
                    title: 'Error!',
                    text: 'You need to be logged in to download this pdf.',
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Go to Login'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/login';
                    }
                });
                return null;
            }

            const userDetails = await fetchUserDetails();
            if (!userDetails) {
                console.error('User details could not be fetched.');
                return;
            }

            const userName = userDetails.name;
            const userMobile = userDetails.mobile;
            const userEmail = userDetails.email;

            try {
                await checkDownloadLimit(userId);

                const existingPdfBytes = await fetch(pdfUrl).then(res => res.arrayBuffer());

                const pdfDoc = await PDFLib.PDFDocument.load(existingPdfBytes);
                const pages = pdfDoc.getPages();

                pages.forEach(page => {
                    const { width, height } = page.getSize();

                    page.drawText(userName, {
                        x: width / 2 - 150,
                        y: height / 2 + 40,
                        size: 25,
                        color: PDFLib.rgb(0.5, 0.5, 0.5), 
                        opacity: 0.5,
                        rotate: PDFLib.degrees(45),
                    });

                    page.drawText(`Phone: ${userMobile} | Email: ${userEmail}`, {
                        x: width / 2 - 150, 
                        y: height / 2, 
                        size: 20,
                        color: PDFLib.rgb(0.5, 0.5, 0.5), 
                        opacity: 0.5,
                        rotate: PDFLib.degrees(45),
                    });
                });

                const pdfBytes = await pdfDoc.save();

                const link = document.createElement('a');
                link.href = URL.createObjectURL(new Blob([pdfBytes], { type: 'application/pdf' }));
                link.download = pdfUrl.split('/').pop(); 

                document.body.appendChild(link);
                link.click();

                document.body.removeChild(link);

                $.ajax({
                    url: '{{ route('download.record') }}',
                    method: 'POST',
                    data: {
                        user_id: userId,
                        pdf_url: pdfUrl,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        console.log('Download record saved successfully.');
                    },
                    error: function() {
                        console.error('Failed to save download record.');
                    }
                });

            } catch (error) {
                console.error(error);
            }
        });


        function decodeHtml(html) {
            var txt = document.createElement("textarea");
            txt.innerHTML = html;
            return txt.value;
        }

        var isLoggedIn = {!! json_encode(auth()->check()) !!};
        var userId = @json(auth()->check() ? auth()->user()->id : null);
        async function createAndDownloadImage(imageUrl, postId) {

            if (!isLoggedIn) {
                Swal.fire({
                title: 'Error!',
                text: 'You need to be logged in to download this post.',
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Go to Login'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            });
            return null;
            }
            const userDetails = await fetchUserDetails();
            if (!userDetails) {
                console.error('User details could not be fetched.');
                return;
            }

            const userName = userDetails.name;
            const userMobile = userDetails.mobile;
            const userEmail = userDetails.email;
            const userAddress = userDetails.address;


            try {
                await checkDownloadLimit(userId); 

                var img = new Image();
                img.crossOrigin = 'Anonymous';
                img.src = imageUrl;

                img.onload = function() {
                    var canvas = document.createElement('canvas');
                    var ctx = canvas.getContext('2d');

                    canvas.width = img.width;
                    canvas.height = img.height; 

                    ctx.drawImage(img, 0, 0);

                    ctx.fillStyle = 'black';
                    ctx.font = '25px Arial';
                    ctx.textAlign = 'center';

                    const footerHeight = 100; 
                    const footerY = img.height - footerHeight + 20;

                    ctx.fillText(userName, canvas.width / 2, footerY); 

                    ctx.font = '16px Arial';

                    const usermobileandemail = '✆' + userMobile + ' | |  ✉ ' + userEmail; 
                    ctx.fillText(usermobileandemail, canvas.width / 2, footerY + 30); 

                    ctx.fillText(userAddress, canvas.width / 2, footerY + 50); 

                    var imageDataUrl = canvas.toDataURL('image/png');
                    document.getElementById('downloadedImage').src = imageDataUrl;

                    var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                    imageModal.show();

                    document.getElementById('downloadButton').onclick = function() {
                        var link = document.createElement('a');
                        link.href = imageDataUrl;
                        link.download = 'post-image.png';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    };

                    $.ajax({
                        url: '{{ route('download.record') }}',
                        method: 'POST',
                        data: {
                            user_id: userId,
                            post_id: postId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
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
            } catch (error) {
                console.error(error);
            }
        }

        async function checkDownloadLimit(userId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('check.download.limit') }}',
                    method: 'POST',
                    data: {
                        user_id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.exceededLimit) {
                            Swal.fire({
                                title: 'Limit Exceeded',
                                text: 'You have reached the download limit for your subscription. Please choose a subscription plan to download more posts.',
                                icon: 'warning',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Go to Subscription Plans'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = response.redirectUrl;
                                }
                                reject('Limit exceeded');
                            });
                        } else if (response.transactionLimita) {
                            Swal.fire({
                                title: 'Payment Pending',
                                text: 'Your payment is not approved yet. Please wait; we will check and update you.',
                                icon: 'info',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = response.redirectUrl;
                                reject('Payment not approved');
                            });
                        } else if (response.transactionLimitb) {
                            Swal.fire({
                                title: 'Subscription Incomplete',
                                text: 'You have not completed your subscription yet; please make a subscription and use our services.',
                                icon: 'info',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = response.redirectUrl;
                            });
                            reject('Subscription incomplete');
                        } else if (response.subscriptionOver) {
                            Swal.fire({
                                title: 'Subscription Expired',
                                text: 'Your subscription has expired; please make a subscription.',
                                icon: 'info',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = response.redirectUrl;
                            });
                            reject('Subscription expired');
                        } else {
                            resolve(response); // Resolve with response if all checks pass
                        }
                    },
                    error: function() {
                        console.error('Failed to check download limit.');
                        reject('AJAX error');
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const categoryElements = document.querySelectorAll('.parent_category');

            categoryElements.forEach(category => {
                category.addEventListener('click', function() {
                    // alert("abcd"); 
                    const categoryId = this.getAttribute('data-id');
                    categoryElements.forEach(cat => cat.classList.remove('active'));
                    this.classList.add('active');

                    fetch(`/subcategories/${categoryId}`)
                        .then(response => response.json())
                        .then(data => {
                            const subcategoriesList = document.getElementById('subcategories-list');
                            subcategoriesList.innerHTML = '';

                            data.forEach((subcategory, index) => {
                                const li = document.createElement('li');
                                li.classList.add('mb-3');
                                const isSelected = index === 0;
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
                            if (data.length > 0) {
                                const firstSubcategoryId = data[0].id; 
                                document.querySelector(`.category-name[data-id="${firstSubcategoryId}"]`).click(); // Simulate click
                            }
                        });
                });

                category.tabIndex = 0; 
                category.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        this.click(); 
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
