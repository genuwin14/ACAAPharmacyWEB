<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
    <link href="<?= base_url('assets/css/orders.css') ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container mt-5">
        <h2>Orders</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Checkout ID</th>
                    <th>Cart ID</th>
                    <th>User ID</th>
                    <th>Status</th>
                    <th>Pickup Date</th>
                    <th>Total Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($checkouts as $checkout): ?>
                    <tr>
                        <td><?= $checkout['checkout_id'] ?></td>
                        <td><?= $checkout['cart_id'] ?></td>
                        <td><?= $checkout['user_id'] ?></td>
                        <td><?= $checkout['status'] ?></td>
                        <td><?= $checkout['pickup_date'] ?></td>
                        <td><?= $checkout['total_amount'] ?></td>
                        <td>
                            <!-- Add actions here, like view, edit, delete -->
                            <a href="#" class="btn btn-primary btn-sm">View</a>
                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?= $this->endSection() ?>
