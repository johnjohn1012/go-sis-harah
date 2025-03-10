<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for purchase history
$purchase_history = [
    [
        'po_id' => 'PO-2024-001',
        'supplier_name' => 'Baking Supplies Co.',
        'order_date' => '2024-03-20',
        'delivery_date' => '2024-03-22',
        'total_amount' => 2500.00,
        'status' => 'Completed',
        'items_count' => 5,
        'created_by' => 'John Doe'
    ],
    [
        'po_id' => 'PO-2024-002',
        'supplier_name' => 'Dairy Products Ltd.',
        'order_date' => '2024-03-18',
        'delivery_date' => '2024-03-19',
        'total_amount' => 1500.00,
        'status' => 'Completed',
        'items_count' => 3,
        'created_by' => 'John Doe'
    ],
    [
        'po_id' => 'PO-2024-003',
        'supplier_name' => 'Spices & Herbs Inc.',
        'order_date' => '2024-03-15',
        'delivery_date' => '2024-03-17',
        'total_amount' => 800.00,
        'status' => 'Completed',
        'items_count' => 4,
        'created_by' => 'John Doe'
    ]
];

$status_badges = [
    'Pending' => 'warning',
    'In Progress' => 'info',
    'Completed' => 'success',
    'Cancelled' => 'danger'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Purchase History</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="exportToExcel()">
                <i class="bi bi-file-earmark-excel me-2"></i>Export to Excel
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <div class="input-group">
                                <input type="date" class="form-control">
                                <span class="input-group-text">to</span>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Supplier</label>
                            <select class="form-select">
                                <option value="">All Suppliers</option>
                                <option value="1">Baking Supplies Co.</option>
                                <option value="2">Dairy Products Ltd.</option>
                                <option value="3">Spices & Herbs Inc.</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option value="">All Status</option>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase History Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Purchase Orders</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search orders...">
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
                                    <th>PO Number</th>
                                    <th>Supplier</th>
                                    <th>Order Date</th>
                                    <th>Delivery Date</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($purchase_history as $order): ?>
                                    <tr>
                                        <td><?php echo $order['po_id']; ?></td>
                                        <td><?php echo htmlspecialchars($order['supplier_name']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($order['order_date'])); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($order['delivery_date'])); ?></td>
                                        <td><?php echo $order['items_count']; ?> items</td>
                                        <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$order['status']]; ?>">
                                                <?php echo $order['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($order['created_by']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Print">
                                                <i class="bi bi-printer"></i>
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