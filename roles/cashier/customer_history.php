<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for customer
$customer = [
    'customer_id' => 'CUST001',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john.doe@email.com',
    'phone' => '+1234567890',
    'registration_date' => '2024-01-15',
    'total_orders' => 12,
    'total_spent' => 1250.00,
    'status' => 'Active',
    'last_visit' => '2024-03-07 14:30:00'
];

// Mock data for transactions
$transactions = [
    [
        'transaction_id' => 'TRX001',
        'order_id' => 'ORD001',
        'date' => '2024-03-07 14:30:00',
        'type' => 'Purchase',
        'amount' => 250.00,
        'payment_method' => 'Cash',
        'status' => 'Completed',
        'items' => [
            ['name' => 'Product A', 'quantity' => 1, 'price' => 75.00],
            ['name' => 'Product D', 'quantity' => 1, 'price' => 75.00]
        ]
    ],
    [
        'transaction_id' => 'TRX002',
        'order_id' => 'ORD002',
        'date' => '2024-03-06 15:45:00',
        'type' => 'Purchase',
        'amount' => 350.00,
        'payment_method' => 'Card',
        'status' => 'Completed',
        'items' => [
            ['name' => 'Product B', 'quantity' => 2, 'price' => 100.00],
            ['name' => 'Product C', 'quantity' => 1, 'price' => 75.00]
        ]
    ],
    [
        'transaction_id' => 'TRX003',
        'order_id' => 'ORD003',
        'date' => '2024-03-05 10:15:00',
        'type' => 'Refund',
        'amount' => -75.00,
        'payment_method' => 'Cash',
        'status' => 'Completed',
        'items' => [
            ['name' => 'Product A', 'quantity' => 1, 'price' => 75.00]
        ]
    ]
];

// Mock data for filters
$transaction_types = ['All', 'Purchase', 'Refund', 'Payment'];
$payment_methods = ['All', 'Cash', 'Card', 'Mobile', 'QR Code'];
$transaction_statuses = ['All', 'Completed', 'Pending', 'Failed'];
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2>Customer History</h2>
            <p class="text-muted">Transaction history for <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></p>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='customer_list.php'">
                <i class="bi bi-arrow-left me-2"></i>Back to Customer List
            </button>
        </div>
    </div>

    <!-- Customer Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Orders</h6>
                    <h3 class="mb-0"><?php echo $customer['total_orders']; ?></h3>
                    <small>All time</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Spent</h6>
                    <h3 class="mb-0">$<?php echo number_format($customer['total_spent'], 2); ?></h3>
                    <small>All time</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Average Order</h6>
                    <h3 class="mb-0">$<?php echo number_format($customer['total_spent'] / $customer['total_orders'], 2); ?></h3>
                    <small>Per order</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Last Visit</h6>
                    <h3 class="mb-0"><?php echo date('M d, Y', strtotime($customer['last_visit'])); ?></h3>
                    <small><?php echo date('H:i', strtotime($customer['last_visit'])); ?></small>
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
                    <label class="form-label">Transaction Type</label>
                    <select class="form-select" id="transactionType">
                        <?php foreach ($transaction_types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>">
                                <?php echo htmlspecialchars($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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
                    <select class="form-select" id="transactionStatus">
                        <?php foreach ($transaction_statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status); ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
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

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['order_id']); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($transaction['date'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $transaction['type'] === 'Purchase' ? 'success' : 
                                            ($transaction['type'] === 'Refund' ? 'danger' : 'info'); 
                                    ?>">
                                        <?php echo htmlspecialchars($transaction['type']); ?>
                                    </span>
                                </td>
                                <td class="<?php echo $transaction['amount'] < 0 ? 'text-danger' : 'text-success'; ?>">
                                    $<?php echo number_format(abs($transaction['amount']), 2); ?>
                                </td>
                                <td><?php echo htmlspecialchars($transaction['payment_method']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $transaction['status'] === 'Completed' ? 'success' : 
                                            ($transaction['status'] === 'Pending' ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo htmlspecialchars($transaction['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="viewTransaction('<?php echo $transaction['transaction_id']; ?>')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="reprintReceipt('<?php echo $transaction['transaction_id']; ?>')">
                                            <i class="bi bi-printer"></i>
                                        </button>
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

<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Transaction Information</h6>
                        <p class="mb-1"><strong>Transaction ID:</strong> <span id="modalTransactionId"></span></p>
                        <p class="mb-1"><strong>Order ID:</strong> <span id="modalOrderId"></span></p>
                        <p class="mb-1"><strong>Date:</strong> <span id="modalTransactionDate"></span></p>
                        <p class="mb-1"><strong>Type:</strong> <span id="modalTransactionType"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Payment Information</h6>
                        <p class="mb-1"><strong>Amount:</strong> <span id="modalAmount"></span></p>
                        <p class="mb-1"><strong>Payment Method:</strong> <span id="modalPaymentMethod"></span></p>
                        <p class="mb-1"><strong>Status:</strong> <span id="modalStatus"></span></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <h6>Order Items</h6>
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
                <button type="button" class="btn btn-primary" onclick="reprintReceipt(currentTransactionId)">
                    <i class="bi bi-printer me-2"></i>Reprint Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let transactionDetailsModal;
let currentTransactionId;

document.addEventListener('DOMContentLoaded', function() {
    transactionDetailsModal = new bootstrap.Modal(document.getElementById('transactionDetailsModal'));
    
    // Initialize form submission
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
});

function applyFilters() {
    // Here you would typically make an AJAX call to fetch filtered transactions
    alert('Applying filters...');
}

function viewTransaction(transactionId) {
    currentTransactionId = transactionId;
    // Here you would typically fetch transaction details from the server
    const transaction = <?php echo json_encode($transactions); ?>[0]; // Using mock data for demo
    
    document.getElementById('modalTransactionId').textContent = transaction.transaction_id;
    document.getElementById('modalOrderId').textContent = transaction.order_id;
    document.getElementById('modalTransactionDate').textContent = 
        new Date(transaction.date).toLocaleString();
    document.getElementById('modalTransactionType').textContent = transaction.type;
    document.getElementById('modalAmount').textContent = 
        '$' + Math.abs(transaction.amount).toFixed(2);
    document.getElementById('modalPaymentMethod').textContent = transaction.payment_method;
    document.getElementById('modalStatus').textContent = transaction.status;
    
    const orderItems = document.getElementById('modalOrderItems');
    orderItems.innerHTML = '';
    let subtotal = 0;
    
    transaction.items.forEach(item => {
        const itemTotal = item.quantity * item.price;
        subtotal += itemTotal;
        
        orderItems.innerHTML += `
            <tr>
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>$${item.price.toFixed(2)}</td>
                <td>$${itemTotal.toFixed(2)}</td>
            </tr>
        `;
    });
    
    const tax = subtotal * 0.1;
    const total = subtotal + tax;
    
    document.getElementById('modalSubtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('modalTax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('modalTotal').textContent = `$${total.toFixed(2)}`;
    
    transactionDetailsModal.show();
}

function reprintReceipt(transactionId) {
    alert('Reprinting receipt for transaction: ' + transactionId);
    // Implement receipt reprinting functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 