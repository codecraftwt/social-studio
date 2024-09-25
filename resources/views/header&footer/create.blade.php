@extends('layouts.app')

@section('content')

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

            <!-- <div class="form-group">
                <label for="fixed-header-width">Header Width (px):</label>
                <input type="number" id="fixed-header-width" value="800" min="100">
            </div>

            <div class="form-group">
                <label for="header-height">Header Height (px):</label>
                <input type="number" id="header-height" value="100" min="50">
            </div> -->

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
                <input type="number" id="font-size" value="" min="03" max="72">
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

            <!-- <div class="form-group">
                <label for="footer-height">Footer Height (px):</label>
                <input type="number" id="footer-height" value="100" min="50">
            </div>

            <div class="form-group">
                <label for="fixed-footer-width">Footer Width (px):</label>
                <input type="number" id="fixed-footer-width" value="800" min="100">
            </div> -->

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
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('header-customization').style.display = 'block';
        document.getElementById('footer-customization').style.display = 'none';
        document.getElementById('header').style.display = 'flex';
        document.getElementById('footer').style.display = 'none';
    });

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

    const FIXED_HEADER_WIDTH = 600; // Fixed width for header
    const FIXED_FOOTER_WIDTH = 600; 
    document.getElementById('generate-header').addEventListener('click', function() {
        var headerText = document.getElementById('header-text').value;
        var backgroundColor = document.getElementById('background-color').value;
        var textColor = document.getElementById('text-color').value;
        var fontFamily = document.getElementById('font-family').value;
        var fontSize = document.getElementById('font-size').value;
        var fontWeight = document.getElementById('font-weight').value;
        var textAlign = document.getElementById('text-align').value;
        var logoPosition = document.getElementById('logo-position').value;
        var fixedHeaderWidth = FIXED_HEADER_WIDTH;
        var headerHeight = 150;

        document.getElementById('header-title').textContent = headerText;
        document.getElementById('header').style.backgroundColor = backgroundColor;
        document.getElementById('header').style.color = textColor;
        document.getElementById('header-title').style.fontFamily = fontFamily;
        document.getElementById('header-title').style.fontSize = fontSize + 'px';
        document.getElementById('header-title').style.fontWeight = fontWeight;
        document.getElementById('header-title').style.textAlign = textAlign;

        document.getElementById('header').style.width = fixedHeaderWidth + 'px';
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
        var fontSize = document.getElementById('font-size').value;
        var logoPosition = document.getElementById('footer-logo-position').value;
        var fixedFooterWidth = FIXED_FOOTER_WIDTH;
        var footerHeight = 150;

        // Set footer text and styles
        document.getElementById('footer-title').textContent = footerText;
        document.getElementById('footer').style.backgroundColor = backgroundColor;
        document.getElementById('footer').style.color = textColor;
        document.getElementById('footer-icon').textContent = icon; // Ensure this is a valid element
        document.getElementById('footer-title').style.fontSize = fontSize + 'px';
        // Set dimensions
        document.getElementById('footer').style.width = fixedFooterWidth + 'px';
        document.getElementById('footer').style.height = footerHeight + 'px';

        // Handle logo positioning
        var footerLogo = document.getElementById('footer-logo');
        footerLogo.style.float = logoPosition === 'left' ? 'left' : (logoPosition === 'right' ? 'right' : 'none');

        // Use html2canvas to generate the footer image
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
