<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for payment methods
$payment_methods = [
    [
        'id' => 'cash',
        'name' => 'Cash',
        'icon' => 'bi-cash',
        'description' => 'Process cash payment'
    ],
    [
        'id' => 'card',
        'name' => 'Card',
        'icon' => 'bi-credit-card',
        'description' => 'Process card payment'
    ],
    [
        'id' => 'mobile',
        'name' => 'Mobile Payment',
        'icon' => 'bi-phone',
        'description' => 'Process mobile payment'
    ],
    [
        'id' => 'qr',
        'name' => 'QR Code',
        'icon' => 'bi-qr-code',
        'description' => 'Scan QR code for payment'
    ]
];

// Mock data for current order
$current_order = [
    'order_id' => 'ORD003',
    'customer_name' => 'Mike Johnson',
    'customer_type' => 'Registered',
    'total_amount' => 150.00,
    'items' => [
        ['name' => 'Product A', 'quantity' => 1, 'price' => 75.00],
        ['name' => 'Product D', 'quantity' => 1, 'price' => 75.00]
    ]
];
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2>Process Payment</h2>
            <p class="text-muted">Complete payment for order #<?php echo htmlspecialchars($current_order['order_id']); ?></p>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='pending_orders.php'">
                <i class="bi bi-arrow-left me-2"></i>Back to Pending Orders
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Customer Information</h6>
                        <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($current_order['customer_name']); ?></p>
                        <p class="mb-1"><strong>Type:</strong> 
                            <span class="badge bg-<?php echo $current_order['customer_type'] === 'Registered' ? 'primary' : 'secondary'; ?>">
                                <?php echo htmlspecialchars($current_order['customer_type']); ?>
                            </span>
                        </p>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($current_order['items'] as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>$<?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Subtotal:</strong></td>
                                    <td>$<?php echo number_format($current_order['total_amount'] / 1.1, 2); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Tax (10%):</strong></td>
                                    <td>$<?php echo number_format(($current_order['total_amount'] / 1.1) * 0.1, 2); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>$<?php echo number_format($current_order['total_amount'], 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Processing -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Select Payment Method</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php foreach ($payment_methods as $method): ?>
                            <div class="col-md-6">
                                <div class="card h-100 payment-method-card" 
                                     onclick="selectPaymentMethod('<?php echo $method['id']; ?>')"
                                     data-method-id="<?php echo $method['id']; ?>">
                                    <div class="card-body text-center">
                                        <i class="bi <?php echo $method['icon']; ?> display-4 mb-3"></i>
                                        <h5 class="card-title"><?php echo htmlspecialchars($method['name']); ?></h5>
                                        <p class="card-text text-muted"><?php echo htmlspecialchars($method['description']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Payment Method Forms -->
                    <div id="paymentForms" class="mt-4">
                        <!-- Cash Payment Form -->
                        <div id="cashPaymentForm" style="display: none;">
                            <h6 class="mb-3">Cash Payment</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Amount Received</label>
                                    <input type="number" class="form-control" id="amountReceived" step="0.01" 
                                           onchange="calculateChange()">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Change</label>
                                    <input type="text" class="form-control" id="change" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Card Payment Form -->
                        <div id="cardPaymentForm" style="display: none;">
                            <h6 class="mb-3">Card Payment</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Expiry Date</label>
                                    <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" id="cardCvv" placeholder="123">
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Payment Form -->
                        <div id="mobilePaymentForm" style="display: none;">
                            <h6 class="mb-3">Mobile Payment</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control" id="mobileNumber" placeholder="+1234567890">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Payment App</label>
                                    <select class="form-select" id="paymentApp">
                                        <option value="">Select Payment App</option>
                                        <option value="gpay">Google Pay</option>
                                        <option value="applepay">Apple Pay</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- QR Payment Form -->
                        <div id="qrPaymentForm" style="display: none;">
                            <h6 class="mb-3">QR Code Payment</h6>
                            <div class="text-center">
                                <div class="mb-3">
                                    <img src="https://via.placeholder.com/200" alt="QR Code" class="img-fluid">
                                </div>
                                <p class="text-muted">Scan this QR code with your mobile payment app</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Notes -->
                    <div class="mt-4">
                        <label class="form-label">Payment Notes</label>
                        <textarea class="form-control" id="paymentNotes" rows="2" 
                                  placeholder="Add any notes about the payment..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <button type="button" class="btn btn-primary" onclick="processPayment()">
                            <i class="bi bi-check-circle me-2"></i>Complete Payment
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="cancelPayment()">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let selectedPaymentMethod = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize payment method cards
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.addEventListener('click', function() {
            selectPaymentMethod(this.dataset.methodId);
        });
    });
});

function selectPaymentMethod(methodId) {
    selectedPaymentMethod = methodId;
    
    // Update card styles
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.classList.remove('border-primary');
    });
    document.querySelector(`[data-method-id="${methodId}"]`).classList.add('border-primary');
    
    // Show/hide payment forms
    document.querySelectorAll('#paymentForms > div').forEach(form => {
        form.style.display = 'none';
    });
    document.getElementById(`${methodId}PaymentForm`).style.display = 'block';
}

function calculateChange() {
    const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;
    const total = <?php echo $current_order['total_amount']; ?>;
    const change = amountReceived - total;
    
    document.getElementById('change').value = change >= 0 ? `$${change.toFixed(2)}` : 'Insufficient amount';
}

function processPayment() {
    if (!selectedPaymentMethod) {
        alert('Please select a payment method');
        return;
    }
    
    // Validate payment method specific fields
    let isValid = true;
    switch (selectedPaymentMethod) {
        case 'cash':
            const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;
            if (amountReceived < <?php echo $current_order['total_amount']; ?>) {
                alert('Insufficient amount received');
                isValid = false;
            }
            break;
        case 'card':
            const cardNumber = document.getElementById('cardNumber').value;
            const cardExpiry = document.getElementById('cardExpiry').value;
            const cardCvv = document.getElementById('cardCvv').value;
            if (!cardNumber || !cardExpiry || !cardCvv) {
                alert('Please fill in all card details');
                isValid = false;
            }
            break;
        case 'mobile':
            const mobileNumber = document.getElementById('mobileNumber').value;
            const paymentApp = document.getElementById('paymentApp').value;
            if (!mobileNumber || !paymentApp) {
                alert('Please fill in all mobile payment details');
                isValid = false;
            }
            break;
    }
    
    if (isValid) {
        // Here you would typically send the payment data to the server
        alert('Payment processed successfully!');
        window.location.href = 'pending_orders.php';
    }
}

function cancelPayment() {
    if (confirm('Are you sure you want to cancel this payment?')) {
        window.location.href = 'pending_orders.php';
    }
}
</script>

<?php require_once '../../includes/footer.php'; ?> 