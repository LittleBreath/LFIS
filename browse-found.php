<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Found Items - LFIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .item-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }
        .item-image {
            height: 250px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        .category-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 0.75rem;
            padding: 5px 10px;
        }
        .date-found {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .btn-contact {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-contact:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
        .no-items {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        .no-items i {
            font-size: 4rem;
            opacity: 0.5;
            margin-bottom: 20px;
        }
        .search-box {
            position: relative;
        }
        .search-box input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .sort-option {
            font-size: 0.9rem;
        }
        .pagination-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
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
                    <a class="nav-link active" href="browse-found.php">Browse Found</a>
                    <a class="nav-link" href="admin/login.php">Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Title -->
    <section class="bg-light py-5">
        <div class="container">
            <h1 class="display-5 fw-bold mb-2">
                <i class="bi bi-search"></i> Browse Found Items
            </h1>
            <p class="text-muted">Search through items that have been found and reported to our system.</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Filters Sidebar -->
                <div class="col-lg-3 mb-4">
                    <div class="filter-section">
                        <h5 class="mb-4 fw-bold">
                            <i class="bi bi-funnel"></i> Filters
                        </h5>

                        <!-- Search -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Search Item</label>
                            <div class="search-box">
                                <input type="text" class="form-control" id="searchInput" placeholder="Item name, description...">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Category</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-all" value="all" checked>
                                <label class="form-check-label" for="cat-all">All Categories</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-electronics" value="Electronics">
                                <label class="form-check-label" for="cat-electronics">Electronics</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-accessories" value="Accessories">
                                <label class="form-check-label" for="cat-accessories">Accessories</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-documents" value="Documents">
                                <label class="form-check-label" for="cat-documents">Documents</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-clothing" value="Clothing">
                                <label class="form-check-label" for="cat-clothing">Clothing</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-bags" value="Bags">
                                <label class="form-check-label" for="cat-bags">Bags & Luggage</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-keys" value="Keys">
                                <label class="form-check-label" for="cat-keys">Keys</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-jewelry" value="Jewelry">
                                <label class="form-check-label" for="cat-jewelry">Jewelry</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cat-books" value="Books">
                                <label class="form-check-label" for="cat-books">Books & Stationery</label>
                            </div>
                        </div>

                        <!-- Condition Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Condition</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cond-all" value="all" checked>
                                <label class="form-check-label" for="cond-all">All Conditions</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cond-excellent" value="Excellent">
                                <label class="form-check-label" for="cond-excellent">Excellent</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cond-good" value="Good">
                                <label class="form-check-label" for="cond-good">Good</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cond-fair" value="Fair">
                                <label class="form-check-label" for="cond-fair">Fair</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="cond-poor" value="Poor">
                                <label class="form-check-label" for="cond-poor">Poor</label>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Date Found</label>
                            <div class="mb-2">
                                <label for="dateFrom" class="form-label">From</label>
                                <input type="date" class="form-control form-control-sm" id="dateFrom">
                            </div>
                            <div class="mb-2">
                                <label for="dateTo" class="form-label">To</label>
                                <input type="date" class="form-control form-control-sm" id="dateTo">
                            </div>
                        </div>

                        <button class="btn btn-outline-secondary w-100 mb-2">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                        </button>
                    </div>
                </div>

                <!-- Items Grid -->
                <div class="col-lg-9">
                    <!-- Sort & View Options -->
                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted">Showing <strong id="itemCount">0</strong> items</span>
                        </div>
                        <div>
                            <label for="sortBy" class="form-label sort-option me-2">Sort by:</label>
                            <select class="form-select form-select-sm d-inline-block w-auto" id="sortBy">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="name">Name (A-Z)</option>
                                <option value="condition">Best Condition</option>
                            </select>
                        </div>
                    </div>

                    <!-- Items Grid -->
                    <div class="row" id="itemsContainer"></div>

                    <!-- No Items Message -->
                    <div id="noItemsMessage" class="no-items" style="display: none;">
                        <i class="bi bi-inbox"></i>
                        <h4>No items found</h4>
                        <p>Try adjusting your search criteria or filters</p>
                    </div>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation" class="mt-5">
                        <ul class="pagination justify-content-center">
                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contact Item Finder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Fill in your details to contact the person who found this item.</p>
                    
                    <form id="contactForm">
                        <input type="hidden" id="foundReportId" name="foundReportId">
                        <div class="mb-3">
                            <label for="contactName" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="contactName" name="contactName" required>
                        </div>

                        <div class="mb-3">
                            <label for="contactEmail" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="contactEmail" required>
                        </div>

                        <div class="mb-3">
                            <label for="contactPhone" class="form-label">Your Phone</label>
                            <input type="tel" class="form-control" id="contactPhone" required>
                        </div>

                        <div class="mb-3">
                            <label for="contactMessage" class="form-label">Message</label>
                            <textarea class="form-control" id="contactMessage" name="contactMessage" rows="3" placeholder="Please describe how you lost this item and provide proof of ownership..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-contact w-100">
                            <i class="bi bi-send"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2026 BintDjango. Lost and Found Items System. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load found items from the backend
        let foundItems = [];
        let selectedFoundItem = null;

        function loadFoundItems() {
            fetch('api.php?action=found_reports')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        foundItems = data.data;
                        renderFoundItems(foundItems);
                    } else {
                        document.getElementById('itemsContainer').innerHTML = '<div class="col-12 text-danger">Failed to load items.</div>';
                    }
                })
                .catch(() => {
                    document.getElementById('itemsContainer').innerHTML = '<div class="col-12 text-danger">Failed to load items.</div>';
                });
        }

        function formatDate(value) {
            if (!value) return '';
            const date = new Date(value);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString(undefined, options);
        }

        function renderFoundItems(items) {
            const container = document.getElementById('itemsContainer');
            container.innerHTML = '';

            const filtered = applyFilters(items);
            document.getElementById('itemCount').innerText = filtered.length;
            document.getElementById('noItemsMessage').style.display = filtered.length === 0 ? 'block' : 'none';

            filtered.forEach(item => {
                const card = document.createElement('div');
                card.className = 'col-md-6 col-lg-4 mb-4';
                card.innerHTML = `
                    <div class="card item-card">
                        <div class="item-image">
                            ${item.photo ? `<img src="${item.photo}" alt="${item.item_name}">` : `<i class="bi bi-box"></i>`}
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">${item.item_name}</h5>
                                <span class="category-badge">${item.category}</span>
                            </div>
                            <p class="date-found"><i class="bi bi-calendar"></i> Found: ${formatDate(item.date_found)}</p>
                            <p class="card-text text-muted small">${item.description}</p>
                            <div class="mb-3">
                                <span class="badge bg-light text-dark">${item.condition}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0">
                            <button class="btn btn-contact btn-sm w-100 contact-button" data-report-id="${item.id}" data-item-name="${encodeURIComponent(item.item_name)}" data-bs-toggle="modal" data-bs-target="#contactModal">
                                <i class="bi bi-envelope"></i> Contact Finder
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });

            document.querySelectorAll('.contact-button').forEach(button => {
                button.addEventListener('click', function() {
                    const reportId = this.dataset.reportId;
                    const itemName = decodeURIComponent(this.dataset.itemName);
                    selectedFoundItem = reportId;
                    document.getElementById('foundReportId').value = reportId;
                    document.querySelector('#contactModal .modal-title').innerText = `Contact Finder for ${itemName}`;
                });
            });
        }

        function applyFilters(items) {
            const query = document.getElementById('searchInput').value.trim().toLowerCase();
            const selectedCategories = Array.from(document.querySelectorAll('input[id^="cat-"]'))
                .filter(input => input.checked && input.value !== 'all')
                .map(input => input.value);
            const selectedConditions = Array.from(document.querySelectorAll('input[id^="cond-"]'))
                .filter(input => input.checked && input.value !== 'all')
                .map(input => input.value);
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            const sortBy = document.getElementById('sortBy').value;

            let filtered = items.filter(item => {
                const matchesQuery = query === '' || item.item_name.toLowerCase().includes(query) || item.description.toLowerCase().includes(query) || item.location.toLowerCase().includes(query);
                const matchesCategory = selectedCategories.length === 0 || selectedCategories.includes(item.category);
                const matchesCondition = selectedConditions.length === 0 || selectedConditions.includes(item.condition);
                const itemDate = new Date(item.date_found);
                const afterFrom = !dateFrom || itemDate >= new Date(dateFrom);
                const beforeTo = !dateTo || itemDate <= new Date(dateTo);
                return matchesQuery && matchesCategory && matchesCondition && afterFrom && beforeTo;
            });

            if (sortBy === 'newest') {
                filtered.sort((a, b) => new Date(b.date_found) - new Date(a.date_found));
            } else if (sortBy === 'oldest') {
                filtered.sort((a, b) => new Date(a.date_found) - new Date(b.date_found));
            } else if (sortBy === 'name') {
                filtered.sort((a, b) => a.item_name.localeCompare(b.item_name));
            } else if (sortBy === 'condition') {
                const order = ['Excellent', 'Good', 'Fair', 'Poor'];
                filtered.sort((a, b) => order.indexOf(a.condition) - order.indexOf(b.condition));
            }

            return filtered;
        }

        document.getElementById('searchInput').addEventListener('input', () => renderFoundItems(foundItems));
        document.getElementById('sortBy').addEventListener('change', () => renderFoundItems(foundItems));
        document.getElementById('dateFrom').addEventListener('change', () => renderFoundItems(foundItems));
        document.getElementById('dateTo').addEventListener('change', () => renderFoundItems(foundItems));
        document.querySelectorAll('input[id^="cat-"]').forEach(input => input.addEventListener('change', () => renderFoundItems(foundItems)));
        document.querySelectorAll('input[id^="cond-"]').forEach(input => input.addEventListener('change', () => renderFoundItems(foundItems)));

        loadFoundItems();

        // Contact form submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading indicator
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-top"></i> Sending...';

            const formData = new FormData(this);
            
            fetch('process_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    bootstrap.Modal.getInstance(document.getElementById('contactModal')).hide();
                    document.getElementById('contactForm').reset();
                    document.getElementById('foundReportId').value = '';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error sending message: ' + error);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // Set max date to today for date filters
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('dateTo').max = today;

        // Sort functionality
        document.getElementById('sortBy').addEventListener('change', function() {
            console.log('Sorting by:', this.value);
        });

        // Category filter debug
        document.querySelectorAll('input[id^="cat-"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                console.log('Category filter changed');
            });
        });
    </script>
</body>
</html>
