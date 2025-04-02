<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title>CodeIgniter 4 Admin</title>
    
    <!-- Bootstrap CSS (via CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome (via CDN) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/layout.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <h2>ACAA</h2>
        <ul>
            <li><a href="<?= base_url('index.php') ?>"><i class="fas fa-home"></i> HOME</a></li>
            <li><a href="#"><i class="fas fa-box"></i> ORDERS</a></li>
            <li><a href="<?= base_url('inventory') ?>"><i class="fas fa-cogs"></i> INVENTORY</a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i> REPORTS</a></li>
            <li><a href="#"><i class="fas fa-users"></i> CUSTOMER</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div id="main-content">
        <header class="d-flex justify-content-between align-items-center">
            <h5 class="m-0" style="font-weight: bold;">WELCOME!</h5>
            <div class="d-flex align-items-center">
                <input type="text" class="form-control me-3" placeholder="Search..." style="max-width: 250px; border-radius: 15px;">
                <i class="fas fa-bell me-3" style="font-size: 20px;"></i>
                <i class="fas fa-user-circle" style="font-size: 24px;"></i>
            </div>
        </header>
        
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
