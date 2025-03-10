<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for orders
$orders = [
    [
        'order_id' => 'ORD001',
        'customer_name' => 'John Doe',
        'customer_type' => 'Registered',
        'order_date' => '2024-03-07 14:30:00',
        'total_amount' => 250.00,
        'payment_method' => 'Cash',
        'payment_status' => 'Completed',
        'order_status' => 'Completed',
        'items' => [
            ['name' => 'Product A', 'quantity' => 2, 'price' => 75.00],
            ['name' => 'Product B', 'quantity' => 1, 'price' => 100.00]
        ]
    ],
    [
        'order_id' => 'ORD002',
        'customer_name' => 'Jane Smith',
        'customer_type' => 'Walk-in',
        'order_date' => '2024-03-07 14:25:00',
        'total_amount' => 350.00,
        'payment_method' => 'Card',
        'payment_status' => 'Completed',
        'order_status' => 'Completed',
        'items' => [
            ['name' => 'Product C', 'quantity' => 3, 'price' => 90.00],
            ['name' => 'Product D', 'quantity' => 2, 'price' => 40.00]
        ]
    ],
    [
        'order_id' => 'ORD003',
        'customer_name' => 'Mike Johnson',
        'customer_type' => 'Registered',
        'order_date' => '2024-03-07 14:20:00',
        'total_amount' => 150.00,
        'payment_method' => 'Mobile',
        'payment_status' => 'Pending',
        'order_status' => 'Processing',
        'items' => [
            ['name' => 'Product A', 'quantity' => 1, 'price' => 75.00],
            ['name' => 'Product D', 'quantity' => 1, 'price' => 75.00]
        ]
    ]
];

// Mock data for filters
$payment_methods = ['All', 'Cash', 'Card', 'Mobile'];
$payment_statuses = ['All', 'Completed', 'Pending', 'Failed'];
$order_statuses = ['All', 'Completed', 'Processing', 'Cancelled'];
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2>Order History</h2>
            <p class="text-muted">View and manage past orders</p>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="exportOrders()">
                <i class="bi bi-download me-2"></i>Export
            </button>
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
                    <label class="form-label">Payment Status</label>
                    <select class="form-select" id="paymentStatus">
                        <?php foreach ($payment_statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status); ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Order Status</label>
                    <select class="form-select" id="orderStatus">
                        <?php foreach ($order_statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status); ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchInput" placeholder="Order ID, Customer Name...">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Apply Filters
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="resetFilters()">
                        <i class="bi bi-x-circle me-2"></i>Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th>Order Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $order['customer_type'] === 'Registered' ? 'primary' : 'secondary'; ?>">
                                        <?php echo htmlspecialchars($order['customer_type']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($order['order_date'])); ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $order['payment_status'] === 'Completed' ? 'success' : 
                                            ($order['payment_status'] === 'Pending' ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo htmlspecialchars($order['payment_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $order['order_status'] === 'Completed' ? 'success' : 
                                            ($order['order_status'] === 'Processing' ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo htmlspecialchars($order['order_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="viewOrder('<?php echo $order['order_id']; ?>')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                onclick="reprintReceipt('<?php echo $order['order_id']; ?>')">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        <?php if ($order['payment_status'] === 'Pending'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="processPayment('<?php echo $order['order_id']; ?>')">
                                                <i class="bi bi-cash"></i>
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
                <button type="button" class="btn btn-primary" onclick="reprintReceipt(currentOrderId)">
                    <i class="bi bi-printer me-2"></i>Reprint Receipt
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

function applyFilters() {
    // Here you would typically make an AJAX call to fetch filtered orders
    alert('Applying filters...');
}

function resetFilters() {
    document.getElementById('filterForm').reset();
    applyFilters();
}

function viewOrder(orderId) {
    currentOrderId = orderId;
    // Here you would typically fetch order details from the server
    const order = <?php echo json_encode($orders); ?>[0]; // Using mock data for demo
    
    document.getElementById('modalOrderId').textContent = order.order_id;
    document.getElementById('modalOrderDate').textContent = new Date(order.order_date).toLocaleString();
    document.getElementById('modalOrderStatus').textContent = order.order_status;
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

function reprintReceipt(orderId) {
    alert('Reprinting receipt for order: ' + orderId);
    // Implement receipt reprinting functionality
}

function processPayment(orderId) {
    alert('Processing payment for order: ' + orderId);
    // Implement payment processing functionality
}

function exportOrders() {
    alert('Exporting orders...');
    // Implement export functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 