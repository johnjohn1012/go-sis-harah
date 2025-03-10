<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for purchase orders
$purchase_orders = [
    [
        'po_id' => 1,
        'po_number' => 'PO-2024-001',
        'supplier_name' => 'Baking Supplies Co.',
        'order_date' => '2024-03-20',
        'expected_delivery' => '2024-03-25',
        'status' => 'Pending Approval',
        'total_amount' => 15000.00,
        'items_count' => 3
    ],
    [
        'po_id' => 2,
        'po_number' => 'PO-2024-002',
        'supplier_name' => 'Dairy Products Inc.',
        'order_date' => '2024-03-19',
        'expected_delivery' => '2024-03-24',
        'status' => 'Partially Received',
        'total_amount' => 8000.00,
        'items_count' => 2
    ],
    [
        'po_id' => 3,
        'po_number' => 'PO-2024-003',
        'supplier_name' => 'Spices & More',
        'order_date' => '2024-03-18',
        'expected_delivery' => '2024-03-23',
        'status' => 'Approved',
        'total_amount' => 5000.00,
        'items_count' => 4
    ]
];

$status_badges = [
    'Draft' => 'secondary',
    'Pending Approval' => 'warning',
    'Approved' => 'success',
    'Partially Received' => 'info',
    'Completed' => 'primary',
    'Cancelled' => 'danger'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Purchase Orders</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPOModal">
                <i class="bi bi-plus-circle me-2"></i>Create Purchase Order
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Purchase Orders List</h5>
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
                                    <th>Expected Delivery</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($purchase_orders as $po): ?>
                                    <tr>
                                        <td><?php echo $po['po_number']; ?></td>
                                        <td><?php echo htmlspecialchars($po['supplier_name']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($po['order_date'])); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($po['expected_delivery'])); ?></td>
                                        <td><?php echo $po['items_count']; ?> items</td>
                                        <td>₱<?php echo number_format($po['total_amount'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$po['status']]; ?>">
                                                <?php echo $po['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" title="Receive Items">
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

<!-- Add Purchase Order Modal -->
<div class="modal fade" id="addPOModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Supplier</label>
                            <select class="form-select" required>
                                <option value="">Select Supplier</option>
                                <option value="1">Baking Supplies Co.</option>
                                <option value="2">Dairy Products Inc.</option>
                                <option value="3">Spices & More</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Expected Delivery Date</label>
                            <input type="date" class="form-control" required>
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="poItems">
                                <tr>
                                    <td>
                                        <select class="form-select" required>
                                            <option value="">Select Item</option>
                                            <option value="1">Flour</option>
                                            <option value="2">Sugar</option>
                                            <option value="3">Eggs</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control unit-price" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control total" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-item">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Grand Total:</strong></td>
                                    <td colspan="2">
                                        <input type="text" class="form-control grand-total" readonly>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <button type="button" class="btn btn-secondary btn-sm" id="addItem">
                            <i class="bi bi-plus-circle me-1"></i>Add Item
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Purchase Order</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 