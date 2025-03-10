<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for dashboard
$dashboard_stats = [
    'total_materials' => 150,
    'low_stock_materials' => 12,
    'out_of_stock_materials' => 3,
    'pending_orders' => 5
];

$low_stock_list = [
    [
        'material_name' => 'Flour',
        'category_name' => 'Baking Supplies',
        'current_stock' => 25,
        'reorder_level' => 30,
        'unit' => 'kg'
    ],
    [
        'material_name' => 'Sugar',
        'category_name' => 'Baking Supplies',
        'current_stock' => 15,
        'reorder_level' => 20,
        'unit' => 'kg'
    ],
    [
        'material_name' => 'Eggs',
        'category_name' => 'Dairy',
        'current_stock' => 100,
        'reorder_level' => 150,
        'unit' => 'pieces'
    ]
];

$recent_movements = [
    [
        'date_added' => '2024-03-20 14:30:00',
        'action_type' => 'Stock Update',
        'item_name' => 'Flour',
        'description' => 'Received 50kg of flour from supplier'
    ],
    [
        'date_added' => '2024-03-20 13:15:00',
        'action_type' => 'Stock Adjustment',
        'item_name' => 'Sugar',
        'description' => 'Adjusted stock due to quality check'
    ],
    [
        'date_added' => '2024-03-20 11:45:00',
        'action_type' => 'Purchase Order',
        'item_name' => 'Eggs',
        'description' => 'Created new purchase order for 200 pieces'
    ]
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Stock Clerk Dashboard</h2>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Raw Materials</h6>
                            <h2 class="mt-2 mb-0"><?php echo $dashboard_stats['total_materials']; ?></h2>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-box-seam" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Low Stock Materials</h6>
                            <h2 class="mt-2 mb-0"><?php echo $dashboard_stats['low_stock_materials']; ?></h2>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Out of Stock</h6>
                            <h2 class="mt-2 mb-0"><?php echo $dashboard_stats['out_of_stock_materials']; ?></h2>
                        </div>
                        <div class="text-danger">
                            <i class="bi bi-x-circle" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Pending Orders</h6>
                            <h2 class="mt-2 mb-0"><?php echo $dashboard_stats['pending_orders']; ?></h2>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-cart-check" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="raw_materials.php" class="btn btn-primary w-100">
                                <i class="bi bi-box-seam me-2"></i> Raw Materials
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="purchase_orders.php" class="btn btn-success w-100">
                                <i class="bi bi-cart-plus me-2"></i> Create Order
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="receive_orders.php" class="btn btn-warning w-100">
                                <i class="bi bi-box-seam me-2"></i> Receive Orders
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="low_stock_alerts.php" class="btn btn-danger w-100">
                                <i class="bi bi-exclamation-triangle me-2"></i> Stock Alerts
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="stock_adjustments.php" class="btn btn-info w-100">
                                <i class="bi bi-sliders me-2"></i> Stock Adjustments
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="product_recipes.php" class="btn btn-secondary w-100">
                                <i class="bi bi-book me-2"></i> Product Recipes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Low Stock Materials</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($low_stock_list as $material): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($material['material_name']); ?></td>
                                        <td><?php echo htmlspecialchars($material['category_name']); ?></td>
                                        <td><?php echo $material['current_stock']; ?></td>
                                        <td><?php echo $material['reorder_level']; ?></td>
                                        <td><?php echo $material['unit']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Stock Movements</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Item</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_movements as $movement): ?>
                                    <tr>
                                        <td><?php echo date('Y-m-d H:i', strtotime($movement['date_added'])); ?></td>
                                        <td><?php echo htmlspecialchars($movement['action_type']); ?></td>
                                        <td><?php echo htmlspecialchars($movement['item_name']); ?></td>
                                        <td><?php echo htmlspecialchars($movement['description']); ?></td>
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

<?php require_once '../../includes/footer.php'; ?> 