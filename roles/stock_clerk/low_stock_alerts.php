<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for low stock alerts
$low_stock_items = [
    [
        'item_id' => 1,
        'item_name' => 'Sugar',
        'category' => 'Baking Supplies',
        'current_stock' => 8.2,
        'unit' => 'kg',
        'reorder_level' => 15,
        'days_until_empty' => 3,
        'last_purchase' => '2024-03-18',
        'supplier_name' => 'Baking Supplies Co.',
        'priority' => 'High'
    ],
    [
        'item_id' => 2,
        'item_name' => 'Chocolate Cake',
        'category' => 'Finished Products',
        'current_stock' => 15,
        'unit' => 'pieces',
        'reorder_level' => 20,
        'days_until_empty' => 5,
        'last_production' => '2024-03-20',
        'supplier_name' => 'Internal Production',
        'priority' => 'Medium'
    ],
    [
        'item_id' => 3,
        'item_name' => 'Bread Loaf',
        'category' => 'Finished Products',
        'current_stock' => 10,
        'unit' => 'pieces',
        'reorder_level' => 25,
        'days_until_empty' => 2,
        'last_production' => '2024-03-18',
        'supplier_name' => 'Internal Production',
        'priority' => 'High'
    ]
];

$priority_badges = [
    'High' => 'danger',
    'Medium' => 'warning',
    'Low' => 'info'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Low Stock Alerts</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="generatePurchaseOrders()">
                <i class="bi bi-cart-plus me-2"></i>Generate Purchase Orders
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Items Requiring Attention</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search items...">
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
                                    <th>Days Until Empty</th>
                                    <th>Last Action</th>
                                    <th>Supplier</th>
                                    <th>Priority</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($low_stock_items as $item): ?>
                                    <tr>
                                        <td><?php echo $item['item_id']; ?></td>
                                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                                        <td><?php echo $item['current_stock'] . ' ' . $item['unit']; ?></td>
                                        <td><?php echo $item['reorder_level'] . ' ' . $item['unit']; ?></td>
                                        <td><?php echo $item['days_until_empty']; ?> days</td>
                                        <td>
                                            <?php 
                                            if ($item['category'] === 'Finished Products') {
                                                echo date('Y-m-d', strtotime($item['last_production']));
                                            } else {
                                                echo date('Y-m-d', strtotime($item['last_purchase']));
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($item['supplier_name']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $priority_badges[$item['priority']]; ?>">
                                                <?php echo $item['priority']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($item['category'] === 'Finished Products'): ?>
                                                <button class="btn btn-sm btn-success" title="Schedule Production">
                                                    <i class="bi bi-calendar-plus"></i>
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-primary" title="Create Purchase Order">
                                                    <i class="bi bi-cart-plus"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-info" title="View History">
                                                <i class="bi bi-clock-history"></i>
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
function generatePurchaseOrders() {
    // Add functionality to generate purchase orders for selected items
    alert('Generating purchase orders...');
}
</script>

<?php require_once '../../includes/footer.php'; ?> 