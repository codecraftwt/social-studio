@extends('layouts.app')

@section('content')
<style>
    .containerb {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    .form-group input, 
    .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .form-group input[type="number"] {
        -moz-appearance: textfield; /* Remove spin buttons in Firefox */
    }

    .form-group input[type="color"] {
        padding: 0;
        border: none;
        width: 40px;
    }

    .form-group .icon-preview, 
    .form-group .logo-preview {
        display: inline-block;
        width: 24px;
        height: 24px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }

    .form-group .hidden {
        display: none;
    }

    #header, #footer {
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        background-color: #007bff;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        /* border-radius: 8px; */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    #header .title, #footer .title {
        flex-grow: 1;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
    }

    #header .logo, #footer .logo {
        width: 50px;
        height: 50px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }

    #header, #footer .icon {
        font-size: 24px;
    }

    .button-containerb {
        text-align: center;
        margin-top: 20px;
    }

    .button-containerb button {
        padding: 10px 20px;
        font-size: 16px;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .button-containerb button:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        #header , #footer {
            flex-direction: column;
            align-items: flex-start;
        }

        #header  .title, #footer .title {
            font-size: 16px;
        }
    }
</style>

<div class="containerb">
    <h1>Customize Your Header or Footer</h1>

    <!-- Selection for Header or Footer -->
    <div class="form-group">
        <label for="customize-select">Select to Customize:</label>
        <select id="customize-select">
            <option value="header">Header</option>
            <option value="footer">Footer</option>
        </select>
    </div>

    <form id="customization-form">
        <!-- Header Customization Fields -->
        <div id="header-customization" class="customization-section">
            <div class="form-group">
                <label for="header-text">Header Text:</label>
                <input type="text" id="header-text" placeholder="Enter header text" required>
            </div>

            <div class="form-group">
                <label for="font-family">Font Family:</label>
                <select id="font-family">
                    <option value="Arial">Arial</option>
                    <option value="Courier New">Courier New</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Verdana">Verdana</option>
                </select>
            </div>

            <div class="form-group">
                <label for="font-size">Font Size:</label>
                <input type="number" id="font-size" value="18" min="03" max="72">
            </div>

            <div class="form-group">
                <label for="font-weight">Font Weight:</label>
                <select id="font-weight">
                    <option value="normal">Normal</option>
                    <option value="bold">Bold</option>
                    <option value="italic">Italic</option>
                </select>
            </div>

            <div class="form-group">
                <label for="text-align">Text Alignment:</label>
                <select id="text-align">
                    <option value="left">Left</option>
                    <option value="center">Center</option>
                    <option value="right">Right</option>
                </select>
            </div>

            <div class="form-group">
                <label for="background-color">Background Color:</label>
                <input type="color" id="background-color" value="#007bff">
            </div>

            <div class="form-group">
                <label for="text-color">Text Color:</label>
                <input type="color" id="text-color" value="#ffffff">
            </div>

            <div class="form-group">
                <label for="icon">Icon:</label>
                <select id="icon">
                    <option value="">None</option>
                    <option value="🌟">Star</option>
                    <option value="🚀">Rocket</option>
                    <option value="🏆">Trophy</option>
                </select>
                <div class="icon-preview" id="icon-preview"></div>
            </div>

            <div class="form-group">
                <label for="logo">Upload Logo (Image):</label>
                <input type="file" id="logo" accept="image/*">
            </div>

            <div class="form-group">
                <label for="logo-position">Logo Position:</label>
                <select id="logo-position">
                    <option value="left">Left</option>
                    <option value="center">Center</option>
                    <option value="right">Right</option>
                </select>
            </div>

            <div class="form-group">
                <label for="header-width">Header Width (px):</label>
                <input type="number" id="header-width" value="800" min="100">
            </div>

            <div class="form-group">
                <label for="header-height">Header Height (px):</label>
                <input type="number" id="header-height" value="100" min="50">
            </div>

            <div class="button-containerb">
                <button type="button" id="generate-header">Generate Header</button>
                <button type="button" id="save-header" style="display:none;">Save Header</button>
            </div>
        </div>

        <!-- Footer Customization Fields -->
        <div id="footer-customization" class="customization-section hidden">
            <div class="form-group">
                <label for="footer-text">Footer Text:</label>
                <input type="text" id="footer-text" placeholder="Enter footer text" required>
            </div>

            <div class="form-group">
                <label for="font-family">Font Family:</label>
                <select id="font-family">
                    <option value="Arial">Arial</option>
                    <option value="Courier New">Courier New</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Verdana">Verdana</option>
                </select>
            </div>

            <div class="form-group">
                <label for="font-size">Font Size:</label>
                <input type="number" id="font-size" value="18" min="03" max="72">
            </div>

            <div class="form-group">
                <label for="font-weight">Font Weight:</label>
                <select id="font-weight">
                    <option value="normal">Normal</option>
                    <option value="bold">Bold</option>
                    <option value="italic">Italic</option>
                </select>
            </div>

            <div class="form-group">
                <label for="text-align">Text Alignment:</label>
                <select id="text-align">
                    <option value="left">Left</option>
                    <option value="center">Center</option>
                    <option value="right">Right</option>
                </select>
            </div>

            <div class="form-group">
                <label for="footer-background-color">Background Color:</label>
                <input type="color" id="footer-background-color" value="#007bff">
            </div>

            <div class="form-group">
                <label for="footer-text-color">Text Color:</label>
                <input type="color" id="footer-text-color" value="#ffffff">
            </div>

            <div class="form-group">
                <label for="footer-icon">Icon:</label>
                <select id="footer-icon">
                    <option value="">None</option>
                    <option value="🌟">Star</option>
                    <option value="🚀">Rocket</option>
                    <option value="🏆">Trophy</option>
                </select>
                <div class="icon-preview" id="footer-icon-preview"></div>
            </div>

            <div class="form-group">
                <label for="footer-logo">Upload Logo (Image):</label>
                <input type="file" id="footer-logo" accept="image/*">
            </div>

            <div class="form-group">
                <label for="footer-logo-position">Logo Position:</label>
                <select id="footer-logo-position">
                    <option value="left">Left</option>
                    <option value="center">Center</option>
                    <option value="right">Right</option>
                </select>
            </div>

            <div class="form-group">
                <label for="footer-width">Footer Width (px):</label>
                <input type="number" id="footer-width" value="800" min="100">
            </div>

            <div class="form-group">
                <label for="footer-height">Footer Height (px):</label>
                <input type="number" id="footer-height" value="100" min="50">
            </div>

            <div class="button-containerb">
                <button type="button" id="generate-footer">Generate Footer</button>
                <button type="button" id="save-footer" style="display:none;">Save Footer</button>
            </div>
        </div>
    </form>

    <div class="header" id="header" style="display:none;">
        <div class="icon" id="header-icon">🌟</div>
        <div class="title" id="header-title">My Awesome Header</div>
        <div class="logo" id="header-logo"></div>
    </div>

    <div class="footer" id="footer" style="display:none;">
        <div class="icon" id="footer-icon">🌟</div>
        <div class="title" id="footer-title">My Awesome Footer</div>
        <div class="logo" id="footer-logo"></div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // Handle selection between header and footer
    document.getElementById('customize-select').addEventListener('change', function() {
        var value = this.value;
        if (value === 'header') {
            document.getElementById('header-customization').style.display = 'block';
            document.getElementById('footer-customization').style.display = 'none';
            document.getElementById('header').style.display = 'flex';
            document.getElementById('footer').style.display = 'none';
        } else {
            document.getElementById('header-customization').style.display = 'none';
            document.getElementById('footer-customization').style.display = 'block';
            document.getElementById('header').style.display = 'none';
            document.getElementById('footer').style.display = 'flex';
        }
    });

    // Generate header
    document.getElementById('generate-header').addEventListener('click', function() {
        var headerText = document.getElementById('header-text').value;
        var backgroundColor = document.getElementById('background-color').value;
        var textColor = document.getElementById('text-color').value;
        var fontFamily = document.getElementById('font-family').value;
        var fontSize = document.getElementById('font-size').value;
        var fontWeight = document.getElementById('font-weight').value;
        var textAlign = document.getElementById('text-align').value;
        var logoPosition = document.getElementById('logo-position').value;
        var headerWidth = document.getElementById('header-width').value;
        var headerHeight = document.getElementById('header-height').value;

        document.getElementById('header-title').textContent = headerText;
        document.getElementById('header').style.backgroundColor = backgroundColor;
        document.getElementById('header').style.color = textColor;
        document.getElementById('header-title').style.fontFamily = fontFamily;
        document.getElementById('header-title').style.fontSize = fontSize + 'px';
        document.getElementById('header-title').style.fontWeight = fontWeight;
        document.getElementById('header-title').style.textAlign = textAlign;

        document.getElementById('header').style.width = headerWidth + 'px';
        document.getElementById('header').style.height = headerHeight + 'px';

        if (logoPosition === 'left') {
            document.getElementById('header-logo').style.float = 'left';
        } else if (logoPosition === 'right') {
            document.getElementById('header-logo').style.float = 'right';
        } else {
            document.getElementById('header-logo').style.float = 'none';
        }

        html2canvas(document.getElementById('header')).then(function(canvas) {
            var imageData = canvas.toDataURL('image/png');

            var saveButton = document.getElementById('save-header');
            saveButton.style.display = 'inline-block';
            saveButton.onclick = function() {
                fetch('{{ route("header.save") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        image: imageData,
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text); });
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Header saved successfully!');
                })
                .catch(error => {
                    console.error('Error saving header:', error);
                });

            };
        }).catch(function(error) {
            console.error('Error generating canvas:', error);
        });
    });

    // Generate footer
    document.getElementById('generate-footer').addEventListener('click', function() {
        var footerText = document.getElementById('footer-text').value;
        var backgroundColor = document.getElementById('footer-background-color').value;
        var textColor = document.getElementById('footer-text-color').value;
        var icon = document.getElementById('footer-icon').value;
        var logoPosition = document.getElementById('footer-logo-position').value;
        var footerWidth = document.getElementById('footer-width').value;
        var footerHeight = document.getElementById('footer-height').value;

        document.getElementById('footer-title').textContent = footerText;
        document.getElementById('footer').style.backgroundColor = backgroundColor;
        document.getElementById('footer').style.color = textColor;
        document.getElementById('footer-icon').textContent = icon;

        document.getElementById('footer').style.width = footerWidth + 'px';
        document.getElementById('footer').style.height = footerHeight + 'px';

        if (logoPosition === 'left') {
            document.getElementById('footer-logo').style.float = 'left';
        } else if (logoPosition === 'right') {
            document.getElementById('footer-logo').style.float = 'right';
        } else {
            document.getElementById('footer-logo').style.float = 'none';
        }

        html2canvas(document.getElementById('footer')).then(function(canvas) {
            var imageData = canvas.toDataURL('image/png');

            var saveButton = document.getElementById('save-footer');
            saveButton.style.display = 'inline-block';
            saveButton.onclick = function() {
                fetch('{{ route("header.saveFooter") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        image: imageData,
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text); });
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Footer saved successfully!');
                })
                .catch(error => {
                    console.error('Error saving footer:', error);
                });

            };
        }).catch(function(error) {
            console.error('Error generating canvas:', error);
        });
    });
</script>
@endsection
