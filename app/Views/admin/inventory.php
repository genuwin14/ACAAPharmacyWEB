<?= $this->extend('admin/layout') ?> 

<?= $this->section('content') ?>
    <link href="<?= base_url('assets/css/inventory.css') ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <div class="container mt-5">
        <h3>Inventory Management</h3>

        <!-- Button to Open the Modal -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus"></i> Add New Product
        </button>

        <div class="mt-4">
            <div class="card table-card">
                <div class="card-header">
                    <h5 style="font-weight: bold;">Inventory Items</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped text-center align-middle">
                        <thead>
                            <tr>
                                <th>Item ID</th>
                                <th>Product Image</th>
                                <th>Product</th> <!-- Product Image + Name -->
                                <th>Details</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inventory as $item): ?>
                                <tr>
                                    <td>#<?= esc($item['id']) ?></td>
                                    <td>
                                        <img src="<?= base_url('uploads/' . $item['image']) ?>" 
                                            onerror="this.onerror=null; this.src='<?= base_url('uploads/default.png') ?>';"
                                            alt="Product Image"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                    </td>
                                    <td><?= esc($item['name']) ?></td>
                                    <td><?= esc($item['details']) ?></td>
                                    <td><?= esc($item['category']) ?></td>
                                    <td><?= esc($item['stock']) ?></td>
                                    <td>₱<?= esc($item['price']) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit-btn" 
                                                data-id="<?= $item['id'] ?>"
                                                data-name="<?= esc($item['name']) ?>"
                                                data-details="<?= esc($item['details']) ?>"
                                                data-category="<?= esc($item['category']) ?>"
                                                data-stock="<?= esc($item['stock']) ?>"
                                                data-price="<?= esc($item['price']) ?>"
                                                data-image="<?= base_url('public/uploads/' . $item['image']) ?>">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $item['id'] ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="productImage" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="productImage" name="image" accept="image/*" required>
                        </div>
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="productDetails" class="form-label">Details</label>
                            <textarea class="form-control" id="productDetails" name="details"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="productCategory" class="form-label">Category</label>
                            <input type="text" class="form-control" id="productCategory" name="category" required>
                        </div>
                        <div class="mb-3">
                            <label for="productStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="productStock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="productPrice" name="price" step="0.01" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProductForm">
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">
                        
                        <div class="mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" id="editName" name="name" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Details</label>
                            <input type="text" id="editDetails" name="details" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <input type="text" id="editCategory" name="category" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" id="editStock" name="stock" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" id="editPrice" name="price" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Product Image</label>
                            <input type="file" id="editImage" name="image" class="form-control">
                            <img id="previewImage" src="" class="img-thumbnail mt-2" width="100">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('addProductForm').addEventListener('submit', function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            // Include CSRF token if CSRF is enabled
            let csrfName = '<?= csrf_token() ?>';
            let csrfHash = '<?= csrf_hash() ?>';
            formData.append(csrfName, csrfHash);

            fetch('<?= base_url('inventory/add') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    alert('Product added successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => console.error('Fetch error:', error));
        });
    </script>

    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                let productId = this.getAttribute('data-id');

                if (confirm("Are you sure you want to delete this product?")) {
                    fetch('<?= base_url('inventory/delete') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded', 
                            'X-Requested-With': 'XMLHttpRequest' // Important for CI AJAX detection
                        },
                        body: new URLSearchParams({
                            'id': productId,
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Product deleted successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + (data.error || 'Failed to delete'));
                        }
                    })
                    .catch(error => console.error('Fetch error:', error));
                }
            });
        });
    </script>

    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function () {
                let productId = this.getAttribute('data-id');
                let productName = this.getAttribute('data-name');
                let productDetails = this.getAttribute('data-details');
                let productCategory = this.getAttribute('data-category');
                let productStock = this.getAttribute('data-stock');
                let productPrice = this.getAttribute('data-price');
                let productImage = this.getAttribute('data-image');

                // Fill modal fields
                document.getElementById('editId').value = productId;
                document.getElementById('editName').value = productName;
                document.getElementById('editDetails').value = productDetails;
                document.getElementById('editCategory').value = productCategory;
                document.getElementById('editStock').value = productStock;
                document.getElementById('editPrice').value = productPrice;
                document.getElementById('previewImage').src = productImage;

                // Show modal
                var editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            });
        });

        document.getElementById('editProductForm').addEventListener('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch('<?= base_url('inventory/edit') ?>', { // ✅ Make sure the endpoint is correct
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Product updated successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Update failed'));
                }
            })
            .catch(error => console.error('Fetch error:', error));
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->endSection() ?>
