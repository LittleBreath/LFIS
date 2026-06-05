<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Lost Item - LFIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        <div class="navbar-nav ms-auto">
            <a class="nav-link active" href="report-lost.php">Report Lost</a>
            <a class="nav-link" href="report-found.php">Report Found</a>
            <a class="nav-link" href="browse-found.php">Browse Found</a>
            <a class="nav-link" href="admin/login.php">Admin</a>
        </div>
    </div>
</nav>

<!-- Page Title -->
<section class="bg-light py-5">
    <div class="container">
        <h1 class="display-5 fw-bold mb-2">
            <i class="bi bi-exclamation-circle text-danger"></i> Report Lost Item
        </h1>
        <p class="text-muted">
            Let us help you find your lost item. Fill out the form below with as much detail as possible.
        </p>
    </div>
</section>

<!-- Form -->
<section class="py-5">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="form-section">
    <h2 class="form-title">
        <i class="bi bi-pencil-square"></i> Lost Item Details
    </h2>

    <form id="reportLostForm" method="POST" action="process_lost_report.php">

        <!-- ITEM INFO -->
        <div class="mb-4">
            <h5 class="mb-3 text-secondary">
                <i class="bi bi-box"></i> Item Information
            </h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Item Name</label>
                    <input type="text" class="form-control" name="itemName" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label required">Category</label>
                    <select class="form-select" name="category" required>
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
                <label class="form-label required">Description</label>
                <textarea class="form-control" name="description" rows="4" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Date Lost</label>
                    <input type="date" class="form-control" name="dateLost" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Time Lost</label>
                    <input type="time" class="form-control" name="timeLost">
                </div>
            </div>
        </div>

        <!-- LOCATION -->
        <div class="mb-4">
            <h5 class="mb-3 text-secondary">
                <i class="bi bi-geo-alt"></i> Location Information
            </h5>

            <div class="mb-3">
                <label class="form-label required">Lost Location</label>
                <input type="text" class="form-control" name="lostLocation" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Building</label>
                <input type="text" class="form-control" name="building">
            </div>
        </div>

        <!-- CONTACT -->
        <div class="mb-4">
            <h5 class="mb-3 text-secondary">
                <i class="bi bi-telephone"></i> Contact Information
            </h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Full Name</label>
                    <input type="text" class="form-control" name="fullName" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label required">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Phone</label>
                    <input type="tel" class="form-control" name="phone" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Institution</label>
                    <input type="text" class="form-control" name="institution">
                </div>
            </div>
        </div>

        <!-- TERMS -->
        <div class="mb-4 form-check">
            <input class="form-check-input" type="checkbox" required>
            <label class="form-check-label">
                I agree to terms and conditions
            </label>
        </div>

        <button type="submit" class="btn btn-submit text-white">
            Submit Report
        </button>

    </form>
</div>

</div>
</div>
</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>