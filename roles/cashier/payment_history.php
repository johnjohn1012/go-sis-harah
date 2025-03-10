<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for payment history
$payments = [
    [
        'payment_id' => 'PAY001',
        'order_id' => 'ORD001',
        'customer_name' => 'John Doe',
        'payment_date' => '2024-03-07 14:30:00',
        'amount' => 250.00,
        'payment_method' => 'Cash',
        'status' => 'Completed',
        'reference' => 'CASH-001',
        'cashier' => 'Jane Smith'
    ],
    [
        'payment_id' => 'PAY002',
        'order_id' => 'ORD002',
        'customer_name' => 'Jane Smith',
        'payment_date' => '2024-03-07 14:25:00',
        'amount' => 350.00,
        'payment_method' => 'Card',
        'status' => 'Completed',
        'reference' => 'CARD-001',
        'cashier' => 'Jane Smith'
    ],
    [
        'payment_id' => 'PAY003',
        'order_id' => 'ORD003',
        'customer_name' => 'Mike Johnson',
        'payment_date' => '2024-03-07 14:20:00',
        'amount' => 150.00,
        'payment_method' => 'Mobile',
        'status' => 'Failed',
        'reference' => 'MOB-001',
        'cashier' => 'Jane Smith'
    ]
];

// Mock data for filters
$payment_methods = ['All', 'Cash', 'Card', 'Mobile', 'QR Code'];
$payment_statuses = ['All', 'Completed', 'Failed', 'Refunded'];
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2>Payment History</h2>
            <p class="text-muted">View and manage payment records</p>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="exportPayments()">
                <i class="bi bi-download me-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Payments</h6>
                    <h3 class="mb-0">$750.00</h3>
                    <small>Last 24 hours</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Cash Payments</h6>
                    <h3 class="mb-0">$250.00</h3>
                    <small>Last 24 hours</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Card Payments</h6>
                    <h3 class="mb-0">$350.00</h3>
                    <small>Last 24 hours</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Mobile Payments</h6>
                    <h3 class="mb-0">$150.00</h3>
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
                    <label class="form-label">Payment Method</label>
                    <select class="form-select" id="paymentMethod">
                        <?php foreach ($payment_methods as $method): ?>
                            <option value="<?php echo htmlspecialchars($method); ?>">
                                <?php echo htmlspecialchars($method); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="paymentStatus">
                        <?php foreach ($payment_statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status); ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchInput" 
                           placeholder="Payment ID, Order ID, Customer Name...">
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

    <!-- Payments Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Status</th>
                            <th>Cashier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['payment_id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['customer_name']); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($payment['payment_date'])); ?></td>
                                <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                                <td><?php echo htmlspecialchars($payment['reference']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $payment['status'] === 'Completed' ? 'success' : 
                                            ($payment['status'] === 'Failed' ? 'danger' : 'warning'); 
                                    ?>">
                                        <?php echo htmlspecialchars($payment['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($payment['cashier']); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="viewPayment('<?php echo $payment['payment_id']; ?>')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="reprintReceipt('<?php echo $payment['payment_id']; ?>')">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        <?php if ($payment['status'] === 'Completed'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="processRefund('<?php echo $payment['payment_id']; ?>')">
                                                <i class="bi bi-arrow-counterclockwise"></i>
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

<!-- Payment Details Modal -->
<div class="modal fade" id="paymentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Payment Information</h6>
                        <p class="mb-1"><strong>Payment ID:</strong> <span id="modalPaymentId"></span></p>
                        <p class="mb-1"><strong>Order ID:</strong> <span id="modalOrderId"></span></p>
                        <p class="mb-1"><strong>Date:</strong> <span id="modalPaymentDate"></span></p>
                        <p class="mb-1"><strong>Status:</strong> <span id="modalPaymentStatus"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p class="mb-1"><strong>Name:</strong> <span id="modalCustomerName"></span></p>
                        <p class="mb-1"><strong>Payment Method:</strong> <span id="modalPaymentMethod"></span></p>
                        <p class="mb-1"><strong>Reference:</strong> <span id="modalReference"></span></p>
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
                <button type="button" class="btn btn-primary" onclick="reprintReceipt(currentPaymentId)">
                    <i class="bi bi-printer me-2"></i>Reprint Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let paymentDetailsModal;
let currentPaymentId;

document.addEventListener('DOMContentLoaded', function() {
    paymentDetailsModal = new bootstrap.Modal(document.getElementById('paymentDetailsModal'));
    
    // Initialize form submission
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
});

function applyFilters() {
    // Here you would typically make an AJAX call to fetch filtered payments
    alert('Applying filters...');
}

function viewPayment(paymentId) {
    currentPaymentId = paymentId;
    // Here you would typically fetch payment details from the server
    const payment = <?php echo json_encode($payments); ?>[0]; // Using mock data for demo
    
    document.getElementById('modalPaymentId').textContent = payment.payment_id;
    document.getElementById('modalOrderId').textContent = payment.order_id;
    document.getElementById('modalPaymentDate').textContent = new Date(payment.payment_date).toLocaleString();
    document.getElementById('modalPaymentStatus').textContent = payment.status;
    document.getElementById('modalCustomerName').textContent = payment.customer_name;
    document.getElementById('modalPaymentMethod').textContent = payment.payment_method;
    document.getElementById('modalReference').textContent = payment.reference;
    document.getElementById('modalCashier').textContent = payment.cashier;
    
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
    
    paymentDetailsModal.show();
}

function reprintReceipt(paymentId) {
    alert('Reprinting receipt for payment: ' + paymentId);
    // Implement receipt reprinting functionality
}

function processRefund(paymentId) {
    if (confirm('Are you sure you want to process a refund for this payment?')) {
        alert('Processing refund for payment: ' + paymentId);
        // Implement refund processing functionality
    }
}

function exportPayments() {
    alert('Exporting payments...');
    // Implement export functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 