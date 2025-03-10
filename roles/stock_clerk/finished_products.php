<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for finished products
$finished_products = [
    [
        'product_id' => 1,
        'product_name' => 'Chocolate Cake',
        'category_name' => 'Cakes',
        'unit_price' => 25.00,
        'current_stock' => 15,
        'reorder_level' => 20,
        'status' => 'Active'
    ],
    [
        'product_id' => 2,
        'product_name' => 'Vanilla Cupcake',
        'category_name' => 'Cupcakes',
        'unit_price' => 5.00,
        'current_stock' => 50,
        'reorder_level' => 30,
        'status' => 'Active'
    ],
    [
        'product_id' => 3,
        'product_name' => 'Bread Loaf',
        'category_name' => 'Bread',
        'unit_price' => 15.00,
        'current_stock' => 10,
        'reorder_level' => 25,
        'status' => 'Active'
    ]
];

$categories = [
    ['category_id' => 1, 'category_name' => 'Cakes'],
    ['category_id' => 2, 'category_name' => 'Cupcakes'],
    ['category_id' => 3, 'category_name' => 'Bread']
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Finished Products Management</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-circle me-2"></i>Add New Product
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Finished Products List</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search products...">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Unit Price</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($finished_products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['product_id']; ?></td>
                                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                                        <td>₱<?php echo number_format($product['unit_price'], 2); ?></td>
                                        <td>
                                            <span class="<?php echo $product['current_stock'] <= $product['reorder_level'] ? 'text-danger' : ''; ?>">
                                                <?php echo $product['current_stock']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $product['reorder_level']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $product['status'] === 'Active' ? 'success' : 'danger'; ?>">
                                                <?php echo $product['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" title="View Recipe">
                                                <i class="bi bi-book"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>">
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit Price</label>
                        <input type="number" class="form-control" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Initial Stock</label>
                        <input type="number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reorder Level</label>
                        <input type="number" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Product</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 