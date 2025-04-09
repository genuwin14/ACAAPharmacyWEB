<?= $this->extend('admin/layout') ?> 

<?= $this->section('content') ?>
    <!-- Custom CSS (Linked Separately) -->
    <!-- <link href="<?= base_url('assets/css/customer.css') ?>" rel="stylesheet"> -->
    <link href="http://192.168.1.6/Pharmacy/ACAAPharmacy/assets/css/index.css" rel="stylesheet">
    <style>
        
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <div class="container mt-5">
        <div class="mt-4">
            <div class="card table-card">
                <div class="card-header">
                    <h5 style="font-weight: bold;">Customer List</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= esc($user['id']) ?></td>
                                        <td><?= esc($user['name']) ?></td>
                                        <td><?= esc($user['username']) ?></td>
                                        <td><?= esc($user['created_at']) ?></td>
                                        <td><?= esc($user['updated_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Required for dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->endSection() ?>
