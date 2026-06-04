<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LFIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
        }
        .card {
            transform: translateY(-5px);
            transition: 0.3s;
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
                    <a class="nav-link" href="report-found.php">Report Found</a>
                    <a class="nav-link" href="browse-found.php">Browse Found</a>
                    <a class="nav-link" href="admin/login.php">Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Herosection -->
     <section class="hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Lost and Found Items System</h1>
            <p class="lead mb-4">Lost something? We got you! Report lost items or browse found items here</p>
            <a href="report-lost.php" class="btn btn-light btn-lg me-2">
                <i class="bi bi-exclamation-circle"></i> I lost something
            </a>  
            <a href="browse-found.php" class="btn btn-outline-light btn-lg">
             <i class="bi bi-search"></i> Find my item
            </a>  
        </div>
     </section>

     <!-- how it works -->
      <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">How It Works</h2>
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow h-100 p-4">
                        <i class="bi bi-pencil-square feature-icon mb-3"></i>
                        <h3>1. Report</h3>
                        <p>Fill out a form with a details of your item, add a photo and contact number.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                   <div class="card border-0 shadow h-100 p-4">
                    <i class="bi bi-search feature-icon mb-3"></i>
                    <h3>2. Search</h3>
                    <p>Browse the list of found items. Use search to quickly locate your belongings.</p> 
                   </div>
                </div>
                    <div class="col-md-4 mb-4">
                   <div class="card border-0 shadow h-100 p-4">
                    <i class="bi bi-check-circle feature-icon mb-3"></i>
                    <h3>3. Reclaim</h3>
                    <p>Admin will verify and help you safely recoveryour item with proof of ownership.</p> 
                   </div>
                </div>
           </div>
        </div>
    </section>

    <!-- about -->
     <section class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="fw-bold mb-3">Why LFIS?</h4>
                    <p>Every day people lose items at schools, colleges, offices and public places. LFIS helps connect those who lost items with those who found them, quickly and securely.</p>
                    <ul class="list-unstyled">
                       <li><i class="bi bi-check-circle-fill text-success me-2"></i> Easy to use</li>
                       <li><i class="bi bi-check-circle-fill text-success me-2"></i> Fast notification</li>
                       <li><i class="bi bi-check-circle-fill text-success me-2"></i> Secure & reliable</li>
                    </ul>
                </div>
                <div class="col-md-6 text-center">
                    <i class="bi bi-briefcase-fill" style="font-size: 10rem; color: #667eea; opacity: 0.3"></i>
                </div>
            </div>
        </div>
     </section>

     <!-- footer -->
      <footer class="bg-dark text-white text-center py-4">
        <div class="container">
            <p class="mb-0">&copy; 2026 BintDjango. Lost and Found Items System. All Rights Reserved.</p>
        </div>
      </footer>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>