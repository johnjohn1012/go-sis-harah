<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for pending orders
$pending_orders = [
    [
        'order_id' => 'ORD003',
        'customer_name' => 'Mike Johnson',
        'customer_type' => 'Registered',
        'order_date' => '2024-03-07 14:20:00',
        'total_amount' => 150.00,
        'payment_method' => 'Mobile',
        'payment_status' => 'Pending',
        'order_status' => 'Processing',
        'waiting_time' => '15 minutes',
        'items' => [
            ['name' => 'Product A', 'quantity' => 1, 'price' => 75.00],
            ['name' => 'Product D', 'quantity' => 1, 'price' => 75.00]
        ]
    ],
    [
        'order_id' => 'ORD004',
        'customer_name' => 'Sarah Wilson',
        'customer_type' => 'Walk-in',
        'order_date' => '2024-03-07 14:15:00',
        'total_amount' => 275.00,
        'payment_method' => 'Card',
        'payment_status' => 'Failed',
        'order_status' => 'Pending',
        'waiting_time' => '20 minutes',
        'items' => [
            ['name' => 'Product B', 'quantity' => 2, 'price' => 100.00],
            ['name' => 'Product C', 'quantity' => 1, 'price' => 75.00]
        ]
    ]
];

// Mock data for filters
$order_types = ['All', 'Payment Pending', 'Processing', 'Failed Payment'];
$waiting_times = ['All', '0-5 minutes', '5-15 minutes', '15-30 minutes', '30+ minutes'];
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2>Pending Orders</h2>
            <p class="text-muted">Manage orders that require attention</p>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="refreshOrders()">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Payment Pending</h6>
                    <h3 class="mb-0">2</h3>
                    <small>Orders waiting for payment</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Processing</h6>
                    <h3 class="mb-0">1</h3>
                    <small>Orders being processed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title">Failed Payments</h6>
                    <h3 class="mb-0">1</h3>
                    <small>Payment processing failed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Ready for Pickup</h6>
                    <h3 class="mb-0">0</h3>
                    <small>Orders ready for customer</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Order Type</label>
                    <select class="form-select" id="orderType">
                        <?php foreach ($order_types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>">
                                <?php echo htmlspecialchars($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Waiting Time</label>
                    <select class="form-select" id="waitingTime">
                        <?php foreach ($waiting_times as $time): ?>
                            <option value="<?php echo htmlspecialchars($time); ?>">
                                <?php echo htmlspecialchars($time); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Order ID, Customer Name...">
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

    <!-- Pending Orders Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Waiting Time</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $order['customer_type'] === 'Registered' ? 'primary' : 'secondary'; ?>">
                                        <?php echo htmlspecialchars($order['customer_type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo strpos($order['waiting_time'], '30') !== false ? 'danger' : 
                                            (strpos($order['waiting_time'], '15') !== false ? 'warning' : 'info'); 
                                    ?>">
                                        <?php echo htmlspecialchars($order['waiting_time']); ?>
                                    </span>
                                </td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $order['payment_status'] === 'Pending' ? 'warning' : 
                                            ($order['payment_status'] === 'Failed' ? 'danger' : 'info'); 
                                    ?>">
                                        <?php echo htmlspecialchars($order['payment_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="viewOrder('<?php echo $order['order_id']; ?>')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <?php if ($order['payment_status'] === 'Pending'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="processPayment('<?php echo $order['order_id']; ?>')">
                                                <i class="bi bi-cash"></i>
                                            </button>
                                        <?php endif; ?>
                                        <?php if ($order['payment_status'] === 'Failed'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="retryPayment('<?php echo $order['order_id']; ?>')">
                                                <i class="bi bi-arrow-repeat"></i>
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

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Order Information</h6>
                        <p class="mb-1"><strong>Order ID:</strong> <span id="modalOrderId"></span></p>
                        <p class="mb-1"><strong>Date:</strong> <span id="modalOrderDate"></span></p>
                        <p class="mb-1"><strong>Status:</strong> <span id="modalOrderStatus"></span></p>
                        <p class="mb-1"><strong>Waiting Time:</strong> <span id="modalWaitingTime"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Customer Information</h6>
                        <p class="mb-1"><strong>Name:</strong> <span id="modalCustomerName"></span></p>
                        <p class="mb-1"><strong>Type:</strong> <span id="modalCustomerType"></span></p>
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
                <button type="button" class="btn btn-primary" onclick="processPayment(currentOrderId)">
                    <i class="bi bi-cash me-2"></i>Process Payment
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let orderDetailsModal;
let currentOrderId;

document.addEventListener('DOMContentLoaded', function() {
    orderDetailsModal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
    
    // Initialize form submission
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
});

function refreshOrders() {
    // Here you would typically make an AJAX call to fetch updated orders
    alert('Refreshing orders...');
}

function applyFilters() {
    // Here you would typically make an AJAX call to fetch filtered orders
    alert('Applying filters...');
}

function viewOrder(orderId) {
    currentOrderId = orderId;
    // Here you would typically fetch order details from the server
    const order = <?php echo json_encode($pending_orders); ?>[0]; // Using mock data for demo
    
    document.getElementById('modalOrderId').textContent = order.order_id;
    document.getElementById('modalOrderDate').textContent = new Date(order.order_date).toLocaleString();
    document.getElementById('modalOrderStatus').textContent = order.order_status;
    document.getElementById('modalWaitingTime').textContent = order.waiting_time;
    document.getElementById('modalCustomerName').textContent = order.customer_name;
    document.getElementById('modalCustomerType').textContent = order.customer_type;
    
    const orderItems = document.getElementById('modalOrderItems');
    orderItems.innerHTML = '';
    let subtotal = 0;
    
    order.items.forEach(item => {
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
    
    orderDetailsModal.show();
}

function processPayment(orderId) {
    alert('Processing payment for order: ' + orderId);
    // Implement payment processing functionality
}

function retryPayment(orderId) {
    alert('Retrying payment for order: ' + orderId);
    // Implement payment retry functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 