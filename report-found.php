<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Found Item - LFIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .form-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        .form-title {
            color: #667eea;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 40px;
            font-weight: 600;
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
        .image-preview {
            max-width: 200px;
            margin-top: 10px;
            border-radius: 5px;
        }
        .required::after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-search-heart"></i> LFIS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="report-lost.php">Report Lost</a>
                    <a class="nav-link active" href="report-found.php">Report Found</a>
                    <a class="nav-link" href="browse-found.php">Browse Found</a>
                    <a class="nav-link" href="admin/login.php">Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Title -->
    <section class="bg-light py-5">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">
                <i class="bi bi-hand-thumbs-up text-success"></i> Report Found Item
            </h1>
            <p class="text-muted">Help someone recover their lost item! Report what you found here.</p>
        </div>
    </section>

    <!-- Report Form -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="form-section">
                        <h2 class="form-title">
                            <i class="bi bi-pencil-square"></i> Found Item Details
                        </h2>

                        <form id="reportFoundForm" method="POST" action="process_found_report.php" enctype="multipart/form-data">
                            <!-- Item Information -->
                            <div class="mb-4">
                                <h5 class="mb-3 text-secondary">
                                    <i class="bi bi-box"></i> Item Information
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="itemName" class="form-label required">Item Name</label>
                                        <input type="text" class="form-control" id="itemName" name="itemName" placeholder="e.g., Silver Watch" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label required">Category</label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="">Select Category</option>
                                            <option value="Electronics">Electronics</option>
                                            <option value="Accessories">Accessories</option>
                                            <option value="Documents">Documents</option>
                                            <option value="Clothing">Clothing</option>
                                            <option value="Bags">Bags & Luggage</option>
                                            <option value="Keys">Keys</option>
                                            <option value="Jewelry">Jewelry</option>
                                            <option value="Books">Books & Stationery</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label required">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe the item in detail (color, size, brand, condition, identifying features, etc.)" required></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dateFound" class="form-label required">Date Found</label>
                                        <input type="date" class="form-control" id="dateFound" name="dateFound" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="timeFound" class="form-label">Time Found (if known)</label>
                                        <input type="time" class="form-control" id="timeFound" name="timeFound">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="itemCondition" class="form-label required">Item Condition</label>
                                    <select class="form-select" id="itemCondition" name="itemCondition" required>
                                        <option value="">Select Condition</option>
                                        <option value="Excellent">Excellent - Like New</option>
                                        <option value="Good">Good - Minor Wear</option>
                                        <option value="Fair">Fair - Some Damage</option>
                                        <option value="Poor">Poor - Significant Damage</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="mb-4">
                                <h5 class="mb-3 text-secondary">
                                    <i class="bi bi-geo-alt"></i> Location Information
                                </h5>

                                <div class="mb-3">
                                    <label for="foundLocation" class="form-label required">Where was it found?</label>
                                    <input type="text" class="form-control" id="foundLocation" name="foundLocation" placeholder="e.g., Main Campus - Library Near Study Desk" required>
                                </div>

                                <div class="mb-3">
                                    <label for="building" class="form-label">Building/Area</label>
                                    <input type="text" class="form-control" id="building" name="building" placeholder="e.g., Library, Cafeteria, Parking Lot">
                                </div>

                                <div class="mb-3">
                                    <label for="itemStorage" class="form-label required">Where are you storing it?</label>
                                    <input type="text" class="form-control" id="itemStorage" name="itemStorage" placeholder="e.g., Lost & Found Counter, My Locker" required>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="mb-4">
                                <h5 class="mb-3 text-secondary">
                                    <i class="bi bi-telephone"></i> Contact Information
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fullName" class="form-label required">Full Name</label>
                                        <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Your full name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label required">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="your.email@example.com" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label required">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="10-digit phone number" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="institution" class="form-label">Institution/Organization</label>
                                        <input type="text" class="form-control" id="institution" name="institution" placeholder="School/College/Office Name">
                                    </div>
                                </div>
                            </div>

                            <!-- Item Photo -->
                            <div class="mb-4">
                                <h5 class="mb-3 text-secondary">
                                    <i class="bi bi-image"></i> Item Photo
                                </h5>

                                <div class="mb-3">
                                    <label for="itemPhoto" class="form-label required">Upload Photo</label>
                                    <input type="file" class="form-control" id="itemPhoto" name="itemPhoto" accept="image/*" required>
                                    <small class="text-muted">Max file size: 5MB. Supported formats: JPG, PNG, GIF</small>
                                    <div id="imagePreview"></div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="mb-4">
                                <h5 class="mb-3 text-secondary">
                                    <i class="bi bi-info-circle"></i> Additional Information
                                </h5>

                                <div class="mb-3">
                                    <label for="additionalNotes" class="form-label">Additional Notes</label>
                                    <textarea class="form-control" id="additionalNotes" name="additionalNotes" rows="3" placeholder="Any other details that might help the owner identify the item..."></textarea>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="notifyMatches" name="notifyMatches" checked>
                                    <label class="form-check-label" for="notifyMatches">
                                        Notify me if someone reports a matching lost item
                                    </label>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agreeTerms" name="agreeTerms" required>
                                    <label class="form-check-label" for="agreeTerms">
                                        I confirm the accuracy of this report and agree to the <a href="#" class="text-decoration-none">terms and conditions</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-submit text-white">
                                    <i class="bi bi-send"></i> Submit Report
                                </button>
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Clear Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2026 BintDjango. Lost and Found Items System. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview functionality
        document.getElementById('itemPhoto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.className = 'image-preview';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });

        // Form submission
        document.getElementById('reportFoundForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic client-side validation
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^\d{10}$/;
            
            if (!phoneRegex.test(phone.replace(/\D/g, ''))) {
                alert('Please enter a valid 10-digit phone number');
                return;
            }

            // Check if photo is uploaded
            const photoInput = document.getElementById('itemPhoto');
            if (!photoInput.files || photoInput.files.length === 0) {
                alert('Please upload a photo of the found item');
                return;
            }

            // Show loading indicator
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-top"></i> Submitting...';

            // Submit form via AJAX
            const formData = new FormData(this);
            
            fetch('process_found_report.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    document.getElementById('reportFoundForm').reset();
                    document.getElementById('imagePreview').innerHTML = '';
                    // Redirect after success
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 2000);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error submitting form: ' + error);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // Set max date to today
        document.getElementById('dateFound').max = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>
