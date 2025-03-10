<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for back orders
$back_orders = [
    [
        'back_order_id' => 1,
        'po_number' => 'PO-2024-001',
        'supplier_name' => 'Baking Supplies Co.',
        'item_name' => 'Flour',
        'ordered_qty' => 50,
        'received_qty' => 30,
        'back_order_qty' => 20,
        'unit' => 'kg',
        'status' => 'Pending',
        'created_date' => '2024-03-20'
    ],
    [
        'back_order_id' => 2,
        'po_number' => 'PO-2024-002',
        'supplier_name' => 'Dairy Products Inc.',
        'item_name' => 'Eggs',
        'ordered_qty' => 200,
        'received_qty' => 150,
        'back_order_qty' => 50,
        'unit' => 'pieces',
        'status' => 'In Process',
        'created_date' => '2024-03-19'
    ],
    [
        'back_order_id' => 3,
        'po_number' => 'PO-2024-003',
        'supplier_name' => 'Spices & More',
        'item_name' => 'Vanilla Extract',
        'ordered_qty' => 10,
        'received_qty' => 0,
        'back_order_qty' => 10,
        'unit' => 'liters',
        'status' => 'Pending',
        'created_date' => '2024-03-18'
    ]
];

$status_badges = [
    'Pending' => 'warning',
    'In Process' => 'info',
    'Completed' => 'success',
    'Cancelled' => 'danger'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Back Orders</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBackOrderModal">
                <i class="bi bi-plus-circle me-2"></i>Create Back Order
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Back Orders List</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search back orders...">
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
                                    <th>Back Order ID</th>
                                    <th>PO Number</th>
                                    <th>Supplier</th>
                                    <th>Item</th>
                                    <th>Ordered Qty</th>
                                    <th>Received Qty</th>
                                    <th>Back Order Qty</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($back_orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['back_order_id']; ?></td>
                                        <td><?php echo $order['po_number']; ?></td>
                                        <td><?php echo htmlspecialchars($order['supplier_name']); ?></td>
                                        <td><?php echo htmlspecialchars($order['item_name']); ?></td>
                                        <td><?php echo $order['ordered_qty'] . ' ' . $order['unit']; ?></td>
                                        <td><?php echo $order['received_qty'] . ' ' . $order['unit']; ?></td>
                                        <td><?php echo $order['back_order_qty'] . ' ' . $order['unit']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$order['status']]; ?>">
                                                <?php echo $order['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($order['created_date'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" title="Receive Back Order">
                                                <i class="bi bi-box-seam"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
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

<!-- Add Back Order Modal -->
<div class="modal fade" id="addBackOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Back Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Purchase Order</label>
                        <select class="form-select" required>
                            <option value="">Select Purchase Order</option>
                            <option value="1">PO-2024-001 - Baking Supplies Co.</option>
                            <option value="2">PO-2024-002 - Dairy Products Inc.</option>
                            <option value="3">PO-2024-003 - Spices & More</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item</label>
                        <select class="form-select" required>
                            <option value="">Select Item</option>
                            <option value="1">Flour</option>
                            <option value="2">Eggs</option>
                            <option value="3">Vanilla Extract</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Back Order Quantity</label>
                        <input type="number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expected Delivery Date</label>
                        <input type="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Create Back Order</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 