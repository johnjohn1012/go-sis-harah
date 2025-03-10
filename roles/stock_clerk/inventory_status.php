<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for raw materials inventory
$raw_materials = [
    [
        'item_id' => 1,
        'item_name' => 'Flour',
        'category' => 'Baking Supplies',
        'current_stock' => 25.5,
        'unit' => 'kg',
        'reorder_level' => 10,
        'last_purchase' => '2024-03-15',
        'status' => 'In Stock'
    ],
    [
        'item_id' => 2,
        'item_name' => 'Sugar',
        'category' => 'Baking Supplies',
        'current_stock' => 8.2,
        'unit' => 'kg',
        'reorder_level' => 15,
        'last_purchase' => '2024-03-18',
        'status' => 'Low Stock'
    ],
    [
        'item_id' => 3,
        'item_name' => 'Eggs',
        'category' => 'Dairy',
        'current_stock' => 120,
        'unit' => 'pieces',
        'reorder_level' => 100,
        'last_purchase' => '2024-03-20',
        'status' => 'In Stock'
    ]
];

// Mock data for finished products inventory
$finished_products = [
    [
        'product_id' => 1,
        'product_name' => 'Chocolate Cake',
        'category' => 'Cakes',
        'current_stock' => 15,
        'unit' => 'pieces',
        'reorder_level' => 20,
        'last_production' => '2024-03-20',
        'status' => 'Low Stock'
    ],
    [
        'product_id' => 2,
        'product_name' => 'Vanilla Cupcake',
        'category' => 'Cupcakes',
        'current_stock' => 50,
        'unit' => 'pieces',
        'reorder_level' => 30,
        'last_production' => '2024-03-19',
        'status' => 'In Stock'
    ],
    [
        'product_id' => 3,
        'product_name' => 'Bread Loaf',
        'category' => 'Bread',
        'current_stock' => 10,
        'unit' => 'pieces',
        'reorder_level' => 25,
        'last_production' => '2024-03-18',
        'status' => 'Low Stock'
    ]
];

$status_badges = [
    'In Stock' => 'success',
    'Low Stock' => 'warning',
    'Out of Stock' => 'danger'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Inventory Status</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="exportToExcel()">
                <i class="bi bi-file-earmark-excel me-2"></i>Export to Excel
            </button>
        </div>
    </div>

    <!-- Raw Materials Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Raw Materials Inventory</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search materials...">
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
                                    <th>Item ID</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Last Purchase</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($raw_materials as $material): ?>
                                    <tr>
                                        <td><?php echo $material['item_id']; ?></td>
                                        <td><?php echo htmlspecialchars($material['item_name']); ?></td>
                                        <td><?php echo htmlspecialchars($material['category']); ?></td>
                                        <td><?php echo $material['current_stock'] . ' ' . $material['unit']; ?></td>
                                        <td><?php echo $material['reorder_level'] . ' ' . $material['unit']; ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($material['last_purchase'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$material['status']]; ?>">
                                                <?php echo $material['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View History">
                                                <i class="bi bi-clock-history"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Create PO">
                                                <i class="bi bi-cart-plus"></i>
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

    <!-- Finished Products Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Finished Products Inventory</h5>
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
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Last Production</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($finished_products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['product_id']; ?></td>
                                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                                        <td><?php echo $product['current_stock'] . ' ' . $product['unit']; ?></td>
                                        <td><?php echo $product['reorder_level'] . ' ' . $product['unit']; ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($product['last_production'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$product['status']]; ?>">
                                                <?php echo $product['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View History">
                                                <i class="bi bi-clock-history"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" title="Schedule Production">
                                                <i class="bi bi-calendar-plus"></i>
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

<script>
function exportToExcel() {
    // Add Excel export functionality here
    alert('Exporting to Excel...');
}
</script>

<?php require_once '../../includes/footer.php'; ?> 