<?= $this->extend('admin/layout') ?> 

<?= $this->section('content') ?>
    <!-- Custom CSS (Linked Separately) -->
    <!-- <link href="<?= base_url('assets/css/index.css') ?>" rel="stylesheet"> -->
    <link href="http://192.168.1.6/Pharmacy/ACAAPharmacy/assets/css/index.css" rel="stylesheet">
    <style>
        
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <div class="container mt-5">

        <!-- Card Section -->
        <div class="row">
            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title">TOTAL SALES</h5>
                        <p class="card-text">₱18,200.00</p>
                        <span class="text-success">+2.67% Increased vs last month</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title">TOTAL ORDERS</h5>
                        <p class="card-text">159</p>
                        <span class="text-success">+2.67% Increased vs last month</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5 class="card-title">INVENTORY</h5>
                        <p class="card-text"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="mt-4">
            <div class="card table-card">
                <div class="card-header">
                    <h5 style="font-weight: bold;">ORDERS</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped text-center"> <!-- Centers table content -->
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Pick Up Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#1</td>
                                <td>John Doe</td>
                                <td>March 25, 2025, 1:00 PM</td>
                                <td>₱500.00</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>
                                    <button class="btn btn-transparent">
                                        <i class="bi bi-gear-fill"></i>
                                    </button>
                                    <div class="btn-group">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Select
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> View</a></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-x-circle"></i> Cancel</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#2</td>
                                <td>Jane Smith</td>
                                <td>March 25, 2025, 1:00 PM</td>
                                <td>₱1,200.00</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>
                                    <button class="btn btn-transparent">
                                        <i class="bi bi-gear-fill"></i>
                                    </button>
                                    <div class="btn-group">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Select
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> View</a></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-x-circle"></i> Cancel</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <!-- More rows can be added dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS (Required for dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->endSection() ?>
