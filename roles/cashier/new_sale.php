<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for products
$products = [
    [
        'id' => 1,
        'name' => 'Product A',
        'category' => 'Category 1',
        'price' => 25.00,
        'stock' => 100,
        'barcode' => '123456789'
    ],
    [
        'id' => 2,
        'name' => 'Product B',
        'category' => 'Category 2',
        'price' => 35.00,
        'stock' => 75,
        'barcode' => '987654321'
    ],
    [
        'id' => 3,
        'name' => 'Product C',
        'category' => 'Category 1',
        'price' => 45.00,
        'stock' => 50,
        'barcode' => '456789123'
    ],
    [
        'id' => 4,
        'name' => 'Product D',
        'category' => 'Category 3',
        'price' => 15.00,
        'stock' => 200,
        'barcode' => '789123456'
    ]
];

// Mock data for categories
$categories = ['All', 'Category 1', 'Category 2', 'Category 3'];
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Product Selection Section -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Product Selection</h5>
                        <div class="d-flex gap-2">
                            <input type="text" class="form-control" placeholder="Search products..." id="productSearch">
                            <select class="form-select" id="categoryFilter">
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category); ?>">
                                        <?php echo htmlspecialchars($category); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row row-cols-2 row-cols-md-3 g-3">
                        <?php foreach ($products as $product): ?>
                            <div class="col">
                                <div class="card h-100 product-card" 
                                     data-product-id="<?php echo $product['id']; ?>"
                                     data-category="<?php echo htmlspecialchars($product['category']); ?>">
                                    <div class="card-body">
                                        <h6 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h6>
                                        <p class="card-text text-muted mb-1">
                                            <?php echo htmlspecialchars($product['category']); ?>
                                        </p>
                                        <p class="card-text mb-2">
                                            <strong>$<?php echo number_format($product['price'], 2); ?></strong>
                                            <small class="text-muted ms-2">Stock: <?php echo $product['stock']; ?></small>
                                        </p>
                                        <button class="btn btn-primary btn-sm w-100" 
                                                onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name']); ?>', <?php echo $product['price']; ?>)">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Shopping Cart</h5>
                        <button class="btn btn-sm btn-outline-danger" onclick="clearCart()">
                            <i class="bi bi-trash"></i> Clear
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="cartItems" class="mb-3">
                        <!-- Cart items will be dynamically added here -->
                    </div>
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (10%):</span>
                            <span id="tax">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="total">$0.00</strong>
                        </div>
                        <button class="btn btn-success w-100" onclick="proceedToPayment()">
                            <i class="bi bi-cash me-2"></i>Proceed to Payment
                        </button>
                    </div>
                </div>
            </div>

            <!-- Customer Section -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Customer Type</label>
                        <select class="form-select" id="customerType">
                            <option value="walk-in">Walk-in Customer</option>
                            <option value="registered">Registered Customer</option>
                        </select>
                    </div>
                    <div id="registeredCustomerFields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Customer ID/QR Code</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="customerId">
                                <button class="btn btn-outline-secondary" type="button" onclick="scanQR()">
                                    <i class="bi bi-qr-code"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select class="form-select" id="paymentMethod">
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="mobile">Mobile Payment</option>
                    </select>
                </div>
                <div id="cashPaymentFields">
                    <div class="mb-3">
                        <label class="form-label">Amount Received</label>
                        <input type="number" class="form-control" id="amountReceived" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Change</label>
                        <input type="text" class="form-control" id="change" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="paymentNotes" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="completePayment()">Complete Payment</button>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];
let paymentModal;

document.addEventListener('DOMContentLoaded', function() {
    paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    
    // Initialize product search and filter
    document.getElementById('productSearch').addEventListener('input', filterProducts);
    document.getElementById('categoryFilter').addEventListener('change', filterProducts);
    document.getElementById('customerType').addEventListener('change', toggleCustomerFields);
    document.getElementById('amountReceived').addEventListener('input', calculateChange);
});

function addToCart(productId, productName, price) {
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: price,
            quantity: 1
        });
    }
    
    updateCartDisplay();
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    updateCartDisplay();
}

function updateQuantity(productId, change) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity = Math.max(1, item.quantity + change);
        updateCartDisplay();
    }
}

function updateCartDisplay() {
    const cartItems = document.getElementById('cartItems');
    const subtotalElement = document.getElementById('subtotal');
    const taxElement = document.getElementById('tax');
    const totalElement = document.getElementById('total');
    
    cartItems.innerHTML = '';
    let subtotal = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        cartItems.innerHTML += `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h6 class="mb-0">${item.name}</h6>
                    <small class="text-muted">$${item.price.toFixed(2)} x ${item.quantity}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.id}, -1)">-</button>
                    <span>${item.quantity}</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.id}, 1)">+</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    const tax = subtotal * 0.1;
    const total = subtotal + tax;
    
    subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
    taxElement.textContent = `$${tax.toFixed(2)}`;
    totalElement.textContent = `$${total.toFixed(2)}`;
}

function clearCart() {
    if (confirm('Are you sure you want to clear the cart?')) {
        cart = [];
        updateCartDisplay();
    }
}

function filterProducts() {
    const searchTerm = document.getElementById('productSearch').value.toLowerCase();
    const category = document.getElementById('categoryFilter').value;
    
    document.querySelectorAll('.product-card').forEach(card => {
        const productName = card.querySelector('.card-title').textContent.toLowerCase();
        const productCategory = card.dataset.category;
        
        const matchesSearch = productName.includes(searchTerm);
        const matchesCategory = category === 'All' || productCategory === category;
        
        card.closest('.col').style.display = matchesSearch && matchesCategory ? '' : 'none';
    });
}

function toggleCustomerFields() {
    const customerType = document.getElementById('customerType').value;
    const registeredFields = document.getElementById('registeredCustomerFields');
    registeredFields.style.display = customerType === 'registered' ? 'block' : 'none';
}

function scanQR() {
    alert('QR Scanner functionality will be implemented here');
}

function proceedToPayment() {
    if (cart.length === 0) {
        alert('Please add items to the cart first');
        return;
    }
    paymentModal.show();
}

function calculateChange() {
    const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;
    const total = parseFloat(document.getElementById('total').textContent.replace('$', ''));
    const change = amountReceived - total;
    
    document.getElementById('change').value = change >= 0 ? `$${change.toFixed(2)}` : 'Insufficient amount';
}

function completePayment() {
    const paymentMethod = document.getElementById('paymentMethod').value;
    const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;
    const total = parseFloat(document.getElementById('total').textContent.replace('$', ''));
    
    if (paymentMethod === 'cash' && amountReceived < total) {
        alert('Insufficient amount received');
        return;
    }
    
    // Here you would typically send the transaction data to the server
    alert('Payment processed successfully!');
    paymentModal.hide();
    clearCart();
}
</script>

<?php require_once '../../includes/footer.php'; ?> 