<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for received orders
$received_orders = [
    [
        'receive_id' => 1,
        'po_number' => 'PO-2024-001',
        'supplier_name' => 'Baking Supplies Co.',
        'receive_date' => '2024-03-20 14:30:00',
        'items_received' => 3,
        'total_amount' => 15000.00,
        'status' => 'Completed',
        'received_by' => 'John Doe'
    ],
    [
        'receive_id' => 2,
        'po_number' => 'PO-2024-002',
        'supplier_name' => 'Dairy Products Inc.',
        'receive_date' => '2024-03-19 16:45:00',
        'items_received' => 2,
        'total_amount' => 8000.00,
        'status' => 'Partially Received',
        'received_by' => 'John Doe'
    ],
    [
        'receive_id' => 3,
        'po_number' => 'PO-2024-003',
        'supplier_name' => 'Spices & More',
        'receive_date' => '2024-03-18 09:15:00',
        'items_received' => 4,
        'total_amount' => 5000.00,
        'status' => 'Completed',
        'received_by' => 'John Doe'
    ]
];

$status_badges = [
    'Pending' => 'warning',
    'Partially Received' => 'info',
    'Completed' => 'success',
    'Cancelled' => 'danger'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Receive Orders</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#receiveOrderModal">
                <i class="bi bi-box-seam me-2"></i>Receive New Order
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Received Orders List</h5>
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
                                    <th>Receive ID</th>
                                    <th>PO Number</th>
                                    <th>Supplier</th>
                                    <th>Receive Date</th>
                                    <th>Items Received</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Received By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($received_orders as $receive): ?>
                                    <tr>
                                        <td><?php echo $receive['receive_id']; ?></td>
                                        <td><?php echo $receive['po_number']; ?></td>
                                        <td><?php echo htmlspecialchars($receive['supplier_name']); ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($receive['receive_date'])); ?></td>
                                        <td><?php echo $receive['items_received']; ?> items</td>
                                        <td>₱<?php echo number_format($receive['total_amount'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$receive['status']]; ?>">
                                                <?php echo $receive['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($receive['received_by']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
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

<!-- Receive Order Modal -->
<div class="modal fade" id="receiveOrderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Receive Purchase Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Purchase Order</label>
                            <select class="form-select" required>
                                <option value="">Select Purchase Order</option>
                                <option value="1">PO-2024-001 - Baking Supplies Co.</option>
                                <option value="2">PO-2024-002 - Dairy Products Inc.</option>
                                <option value="3">PO-2024-003 - Spices & More</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Receive Date</label>
                            <input type="datetime-local" class="form-control" required>
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Ordered Qty</th>
                                    <th>Received Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="receiveItems">
                                <tr>
                                    <td>Flour</td>
                                    <td>50 kg</td>
                                    <td>
                                        <input type="number" class="form-control received-qty" required>
                                    </td>
                                    <td>₱300.00</td>
                                    <td>
                                        <input type="text" class="form-control total" readonly>
                                    </td>
                                    <td>
                                        <select class="form-select status">
                                            <option value="Complete">Complete</option>
                                            <option value="Partial">Partial</option>
                                            <option value="Missing">Missing</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Grand Total:</strong></td>
                                    <td colspan="2">
                                        <input type="text" class="form-control grand-total" readonly>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Receipt</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 