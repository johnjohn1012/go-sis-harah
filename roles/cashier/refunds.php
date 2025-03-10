<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for refunds
$refunds = [
    [
        'refund_id' => 'REF001',
        'payment_id' => 'PAY001',
        'order_id' => 'ORD001',
        'customer_name' => 'John Doe',
        'refund_date' => '2024-03-07 15:30:00',
        'amount' => 250.00,
        'payment_method' => 'Cash',
        'status' => 'Completed',
        'reason' => 'Customer requested refund',
        'cashier' => 'Jane Smith'
    ],
    [
        'refund_id' => 'REF002',
        'payment_id' => 'PAY002',
        'order_id' => 'ORD002',
        'customer_name' => 'Jane Smith',
        'refund_date' => '2024-03-07 15:25:00',
        'amount' => 350.00,
        'payment_method' => 'Card',
        'status' => 'Processing',
        'reason' => 'Product quality issue',
        'cashier' => 'Jane Smith'
    ]
];

// Mock data for filters
$refund_statuses = ['All', 'Completed', 'Processing', 'Failed'];
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2>Refunds</h2>
            <p class="text-muted">Process and manage payment refunds</p>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="exportRefunds()">
                <i class="bi bi-download me-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Refunds</h6>
                    <h3 class="mb-0">$600.00</h3>
                    <small>Last 24 hours</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Completed</h6>
                    <h3 class="mb-0">$250.00</h3>
                    <small>Last 24 hours</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Processing</h6>
                    <h3 class="mb-0">$350.00</h3>
                    <small>Last 24 hours</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title">Failed</h6>
                    <h3 class="mb-0">$0.00</h3>
                    <small>Last 24 hours</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date Range</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="startDate">
                        <span class="input-group-text">to</span>
                        <input type="date" class="form-control" id="endDate">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="refundStatus">
                        <?php foreach ($refund_statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status); ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchInput" 
                           placeholder="Refund ID, Payment ID, Customer Name...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Refunds Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Refund ID</th>
                            <th>Payment ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Cashier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($refunds as $refund): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($refund['refund_id']); ?></td>
                                <td><?php echo htmlspecialchars($refund['payment_id']); ?></td>
                                <td><?php echo htmlspecialchars($refund['customer_name']); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($refund['refund_date'])); ?></td>
                                <td>$<?php echo number_format($refund['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($refund['payment_method']); ?></td>
                                <td><?php echo htmlspecialchars($refund['reason']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $refund['status'] === 'Completed' ? 'success' : 
                                            ($refund['status'] === 'Processing' ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo htmlspecialchars($refund['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($refund['cashier']); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="viewRefund('<?php echo $refund['refund_id']; ?>')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="reprintReceipt('<?php echo $refund['refund_id']; ?>')">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        <?php if ($refund['status'] === 'Processing'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="completeRefund('<?php echo $refund['refund_id']; ?>')">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Refund Details Modal -->
<div class="modal fade" id="refundDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Refund Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Refund Information</h6>
                        <p class="mb-1"><strong>Refund ID:</strong> <span id="modalRefundId"></span></p>
                        <p class="mb-1"><strong>Payment ID:</strong> <span id="modalPaymentId"></span></p>
                        <p class="mb-1"><strong>Date:</strong> <span id="modalRefundDate"></span></p>
                        <p class="mb-1"><strong>Status:</strong> <span id="modalRefundStatus"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p class="mb-1"><strong>Name:</strong> <span id="modalCustomerName"></span></p>
                        <p class="mb-1"><strong>Payment Method:</strong> <span id="modalPaymentMethod"></span></p>
                        <p class="mb-1"><strong>Reason:</strong> <span id="modalReason"></span></p>
                        <p class="mb-1"><strong>Cashier:</strong> <span id="modalCashier"></span></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="modalOrderItems">
                            <!-- Order items will be dynamically added here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td id="modalSubtotal"></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tax (10%):</strong></td>
                                <td id="modalTax"></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td id="modalTotal"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="reprintReceipt(currentRefundId)">
                    <i class="bi bi-printer me-2"></i>Reprint Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let refundDetailsModal;
let currentRefundId;

document.addEventListener('DOMContentLoaded', function() {
    refundDetailsModal = new bootstrap.Modal(document.getElementById('refundDetailsModal'));
    
    // Initialize form submission
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
});

function applyFilters() {
    // Here you would typically make an AJAX call to fetch filtered refunds
    alert('Applying filters...');
}

function viewRefund(refundId) {
    currentRefundId = refundId;
    // Here you would typically fetch refund details from the server
    const refund = <?php echo json_encode($refunds); ?>[0]; // Using mock data for demo
    
    document.getElementById('modalRefundId').textContent = refund.refund_id;
    document.getElementById('modalPaymentId').textContent = refund.payment_id;
    document.getElementById('modalRefundDate').textContent = new Date(refund.refund_date).toLocaleString();
    document.getElementById('modalRefundStatus').textContent = refund.status;
    document.getElementById('modalCustomerName').textContent = refund.customer_name;
    document.getElementById('modalPaymentMethod').textContent = refund.payment_method;
    document.getElementById('modalReason').textContent = refund.reason;
    document.getElementById('modalCashier').textContent = refund.cashier;
    
    // Here you would typically fetch order items from the server
    const orderItems = document.getElementById('modalOrderItems');
    orderItems.innerHTML = `
        <tr>
            <td>Product A</td>
            <td>1</td>
            <td>$75.00</td>
            <td>$75.00</td>
        </tr>
        <tr>
            <td>Product D</td>
            <td>1</td>
            <td>$75.00</td>
            <td>$75.00</td>
        </tr>
    `;
    
    const subtotal = 150.00;
    const tax = subtotal * 0.1;
    const total = subtotal + tax;
    
    document.getElementById('modalSubtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('modalTax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('modalTotal').textContent = `$${total.toFixed(2)}`;
    
    refundDetailsModal.show();
}

function reprintReceipt(refundId) {
    alert('Reprinting receipt for refund: ' + refundId);
    // Implement receipt reprinting functionality
}

function completeRefund(refundId) {
    if (confirm('Are you sure you want to complete this refund?')) {
        alert('Completing refund: ' + refundId);
        // Implement refund completion functionality
    }
}

function exportRefunds() {
    alert('Exporting refunds...');
    // Implement export functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 