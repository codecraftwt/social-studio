@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        #canvas {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            width: 100%;
            max-width: 750px;
            height: auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .controls {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 750px;
        }
        .controls label {
            margin-right: 10px;
        }
        .slider-label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .footer-buttons {
            margin-top: 20px;
        }
        .cust-img-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0 auto; /* Center horizontally */
        }
    </style>

<div class="cust-img-container">
    <h1>Custom Post Editor</h1>

    <canvas id="canvas" width="750" height="904"></canvas>

    <div class="controls">
        <div class="footer-buttons d-flex justify-content-between">
            <button class="btn btn-success btn-log" id="download">Download Image</button>
            <button class="btn btn-secondary btn-log" id="zoomIn">Zoom In <i class="bi bi-plus-circle-fill"></i></button>
            <button class="btn btn-secondary btn-log" id="zoomOut">Zoom Out <i class="bi bi-dash-circle-fill"></i></button>
            <button class="btn btn-secondary btn-log" id="refresh" onclick="location.reload();">Refresh <i class="bi bi-arrow-clockwise"></i></button>
        </div>

        <div class="mt-3">
            <label>
                Add Watermark :- <input type="checkbox" id="watermarkToggle" checked>
            </label>
            <div>
                <span class="slider-label">Watermark Rotation</span>
                <input type="range" id="watermarkRotation" min="-360" max="360" value="0" class="w-100">
            </div>
            <div>
                <span class="slider-label">Watermark Opacity</span>
                <input type="range" id="watermarkOpacity" min="0" max="1" step="0.1" value="0.3" class="w-100">
            </div>
            <div>
                <span class="slider-label">Watermark Vertical Position</span>
                <input type="range" id="watermarkYPosition" min="0" max="894" step="1" value="447" class="w-100">
            </div>
        </div>
    </div>
</div>
<footer class="footer text-center mt-5 plans-footer">
        <p class="mb-0 text-white">© 2024 Surreta. All Rights Reserved.</p>
    </footer>
    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        // const headerText = "तुमच्या डिझाइनसाठी अद्वितीय केंद्रित स्टाईल सेवा";
        const headerText = "{!! $user->name !!}";
        const headerFontSize = 34;
        const headerY = 50;

        // const watermarkText = "सर्वांत सोपे आणि जलद ऑनलाइन फॉर्म, ऑनलाइन फॉर्म सेंटरसाठी सुरक्षितता आणि सोपेपणा";
        const watermarkText = `{!! $user->name !!} ✆ {!! $user->mobile !!} ✉ {!! $user->email !!}`;
        const watermarkFontSize = 20;
        let watermarkY = canvas.height / 2;

        let watermarkRotation = 0;
        let watermarkOpacity = 0.3;

        let borderColor = '#daa8bd';
        const footerText_a = "{!! $user->name !!}";
        const footerMobile_a = "{!! $user->mobile !!}";
        const footerEmail_a = "{!! $user->email !!}";
        const footerAddress_a = "{!! $user->address !!}";
        const footerText = `✆  ${footerMobile_a} | | ✉ ${footerEmail_a}`;
        const footerTexts = [
            { text: footerText_a, x: canvas.width / 2, y: 820, fontSize: 20, rotation: 0 },
            { text: footerText , x: canvas.width / 2, y: 845, fontSize: 16, rotation: 0 },
            { text: footerAddress_a, x: canvas.width / 2, y: 870, fontSize: 16, rotation: 0 }
        ];

        let isDragging = false;
        let draggedTextIndex = -1;
        let offsetX, offsetY;

        const img = new Image();
        img.crossOrigin = "Anonymous";
        // img.src = 'http://127.0.0.1:8000/storage/post_images/6703d7864efe2.png';
        img.src = "{{ asset('storage') }}/{{ $post->post_image }}";
        
        img.onload = () => {
            getBorderColor(img);
            draw();
        };

        function getBorderColor(image) {
            const tempCanvas = document.createElement('canvas');
            const tempCtx = tempCanvas.getContext('2d');
            tempCanvas.width = image.width;
            tempCanvas.height = image.height;
            tempCtx.drawImage(image, 0, 0);
            const imageData = tempCtx.getImageData(0, 0, 1, 1).data;
            borderColor = `rgba(${imageData[0]}, ${imageData[1]}, ${imageData[2]}, 1)`;
        }
        function splitHeaderText(text) {
            const words = text.split(' ');
            const line1 = words.slice(0, 4).join(' '); // First 4 words
            const line2 = words.slice(4).join(' '); // Remaining words
            return { line1, line2 };
        }

        const { line1: headerTextLine1, line2: headerTextLine2 } = splitHeaderText(headerText);
        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            watermarkY = parseInt(document.getElementById('watermarkYPosition').value);

            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, headerY + headerFontSize + 20);
            ctx.strokeStyle = borderColor;
            ctx.lineWidth = 20;

            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(canvas.width, 0);
            ctx.stroke();

            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(0, headerY + headerFontSize + 20);
            ctx.stroke();

            ctx.beginPath();
            ctx.moveTo(canvas.width, 0);
            ctx.lineTo(canvas.width, headerY + headerFontSize + 20);
            ctx.stroke();

            // ctx.fillStyle = 'black';
            // ctx.font = `${headerFontSize}px Arial`;
            // ctx.textAlign = 'center';
            // ctx.fillText(headerText, canvas.width / 2, headerY + headerFontSize / 2);

            ctx.fillStyle = 'black';
            ctx.font = `${headerFontSize}px Arial`;
            ctx.textAlign = 'center';
            ctx.shadowColor = 'rgba(0, 0, 0, 1.5)'; // Shadow color
            ctx.shadowOffsetX = 2; // Horizontal shadow offset
            ctx.shadowOffsetY = 2; // Vertical shadow offset
            ctx.shadowBlur = 4; // Shadow blur
            ctx.fillText(headerTextLine1, canvas.width / 2, headerY + headerFontSize / 8);
            ctx.fillText(headerTextLine2, canvas.width / 2, headerY + headerFontSize + 10); // Adjust position for second line
            ctx.shadowColor = 'transparent'; // Reset shadow for other drawing

            ctx.drawImage(img, 0, headerY + headerFontSize + 20);

            if (document.getElementById('watermarkToggle').checked) {
                ctx.save();
                ctx.translate(canvas.width / 2, watermarkY);
                ctx.rotate(watermarkRotation * Math.PI / 180);

                // ctx.fillStyle = '#daa8bd';
                ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
                ctx.fillRect(-canvas.width / 2, -watermarkFontSize / 2 - 12, canvas.width, watermarkFontSize + 3);

                // ctx.fillStyle = `rgba(0, 0, 0, ${watermarkOpacity})`;
                ctx.fillStyle = `rgba(255, 255, 255, ${watermarkOpacity})`;
                ctx.font = `${watermarkFontSize}px Arial`;
                ctx.textAlign = 'center';
                ctx.fillText(watermarkText, 0, 0);
                ctx.restore();
            }

            footerTexts.forEach((textObj) => {
                ctx.save();
                ctx.translate(textObj.x, textObj.y);
                ctx.rotate(textObj.rotation);
                ctx.font = `${textObj.fontSize}px Arial`;
                ctx.fillStyle = 'black';
                ctx.textAlign = 'center';
                ctx.fillText(textObj.text, 0, 0);
                ctx.restore();
            });
        }

        // document.getElementById('download').onclick = () => {
        //     const link = document.createElement('a');
        //     link.download = 'edited_image.png';
        //     link.href = canvas.toDataURL('image/png');
        //     link.click();
        // };

        document.getElementById('download').onclick = async () => {
            const userId = "{{ auth()->id() }}"; // Get the authenticated user ID
            try {
                const response = await checkDownloadLimit(userId);
                // If the limit is not exceeded, proceed with the download
                const link = document.createElement('a');
                link.download = 'edited_image.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            } catch (error) {
                console.error(error); // Handle errors as needed
            }
        };

        document.getElementById('zoomIn').onclick = () => {
            footerTexts.forEach(textObj => {
                textObj.fontSize += 2;
            });
            draw();
        };

        document.getElementById('zoomOut').onclick = () => {
            footerTexts.forEach(textObj => {
                if (textObj.fontSize > 10) {
                    textObj.fontSize -= 2;
                }
            });
            draw();
        };

        document.getElementById('watermarkToggle').addEventListener('change', draw);
        document.getElementById('watermarkRotation').addEventListener('input', (e) => {
            watermarkRotation = parseInt(e.target.value);
            draw();
        });
        document.getElementById('watermarkOpacity').addEventListener('input', (e) => {
            watermarkOpacity = parseFloat(e.target.value);
            draw();
        });
        document.getElementById('watermarkYPosition').addEventListener('input', draw);

        canvas.addEventListener('mousedown', (e) => {
            const mouseX = e.offsetX;
            const mouseY = e.offsetY;

            footerTexts.forEach((textObj, index) => {
                const width = ctx.measureText(textObj.text).width;
                const height = textObj.fontSize;

                if (mouseX >= textObj.x - width / 2 && mouseX <= textObj.x + width / 2 &&
                    mouseY >= textObj.y - height / 2 && mouseY <= textObj.y + height / 2) {
                    isDragging = true;
                    draggedTextIndex = index;
                    offsetX = mouseX - textObj.x;
                    offsetY = mouseY - textObj.y;
                }
            });
        });

        // canvas.addEventListener('mousemove', (e) => {
        //     if (isDragging && draggedTextIndex !== -1) {
        //         const mouseX = e.offsetX;
        //         const mouseY = e.offsetY;

        //         footerTexts[draggedTextIndex].x = mouseX - offsetX;
        //         footerTexts[draggedTextIndex].y = mouseY - offsetY;
        //         draw();
        //     }
        // });

        canvas.addEventListener('mousemove', (e) => {
            const mouseX = e.offsetX;
            const mouseY = e.offsetY;
            let isHovering = false;

            footerTexts.forEach((textObj, index) => {
                const width = ctx.measureText(textObj.text).width;
                const height = textObj.fontSize;

                if (mouseX >= textObj.x - width / 2 && mouseX <= textObj.x + width / 2 &&
                    mouseY >= textObj.y - height / 2 && mouseY <= textObj.y + height / 2) {
                    isHovering = true;
                    canvas.style.cursor = 'pointer'; // Change cursor to pointer
                    if (isDragging && draggedTextIndex !== -1) {
                        // Update position if dragging
                        footerTexts[draggedTextIndex].x = mouseX - offsetX;
                        footerTexts[draggedTextIndex].y = mouseY - offsetY;
                        draw();
                    }
                }
            });

            // If not hovering over any text, reset cursor to default
            if (!isHovering) {
                canvas.style.cursor = 'default';
            }
        });

        canvas.addEventListener('mouseup', () => {
            isDragging = false;
            draggedTextIndex = -1;
        });

        canvas.addEventListener('wheel', (e) => {
            if (isDragging && draggedTextIndex !== -1) {
                const delta = e.deltaY < 0 ? 0.1 : -0.1;
                footerTexts[draggedTextIndex].rotation += delta;
                draw();
                e.preventDefault();
            }
        });

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
    </script>
@endsection