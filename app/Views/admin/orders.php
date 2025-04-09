<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<link href="<?= base_url('assets/css/orders.css') ?>" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-5">
    <h2 class="mb-4">Pending Orders</h2>
    <div class="row g-4">
        <?php foreach ($groupedCheckouts as $userId => $checkoutGroup): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Buyer: <?= esc($checkoutGroup['user_name']) ?></h5>
                        <form action="<?= base_url('admin/set-pickup-date') ?>" method="post" class="d-flex flex-column gap-2">
                            <?= csrf_field(); ?> <!-- Include CSRF token here -->
                            <?php foreach ($checkoutGroup['items'] as $item): ?>
                                <div class="d-flex mb-3">
                                    <img src="<?= base_url('uploads/' . $item['product_image']) ?>" class="me-3 rounded" style="width: 60px; height: 60px; object-fit: cover;" alt="<?= esc($item['product_name']) ?>">
                                    <div>
                                        <p class="mb-0"><strong><?= esc($item['product_name']) ?></strong></p>
                                        <small>Qty: <?= esc($item['quantity']) ?> | ₱<?= number_format($item['product_price'], 2) ?></small><br>
                                        <small>Pickup: <?= esc($item['pickup_date']) ?></small><br>
                                        <small>Date Received: <?= esc($item['datetime_received']) ?></small>
                                    </div>
                                </div>
                                <!-- Move checkbox to the left of the text -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="checkout_ids[]" value="<?= esc($item['checkout_id']) ?>" id="checkout_<?= esc($item['checkout_id']) ?>" 
                                           <?= $item['datetime_received'] ? 'disabled' : '' ?>>
                                    <label class="form-check-label" for="checkout_<?= esc($item['checkout_id']) ?>">
                                        Set Pickup Date
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <input type="datetime-local" class="form-control form-control-sm" name="pickup_date" required
                                   <?= $checkoutGroup['items'][0]['datetime_received'] ? 'disabled' : '' ?>>
                            <button type="submit" class="btn btn-sm btn-success"
                                    <?= $checkoutGroup['items'][0]['datetime_received'] ? 'disabled' : '' ?>>
                                <i class="bi bi-calendar-check"></i> Set Pickup Date
                            </button>
                        </form>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center">
                        <form action="<?= base_url('admin/receive-product') ?>" method="post">
                            <?= csrf_field(); ?>
                            <?php foreach ($checkoutGroup['items'] as $item): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="checkout_ids[]" value="<?= esc($item['checkout_id']) ?>" id="receive_<?= esc($item['checkout_id']) ?>"
                                           <?= $item['datetime_received'] ? 'disabled' : '' ?>>
                                    <label class="form-check-label" for="receive_<?= esc($item['checkout_id']) ?>">
                                        <?= esc($item['product_name']) ?> (Mark as Received)
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-sm btn-secondary w-100"
                                    <?= $checkoutGroup['items'][0]['datetime_received'] ? 'disabled' : '' ?>>
                                <i class="bi bi-box-seam"></i> Product Received
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>
