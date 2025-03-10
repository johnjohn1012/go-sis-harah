<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for customers
$customers = [
    [
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
    ],
    [
        'customer_id' => 'CUST002',
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'email' => 'jane.smith@email.com',
        'phone' => '+1987654321',
        'registration_date' => '2024-02-01',
        'total_orders' => 8,
        'total_spent' => 850.00,
        'status' => 'Active',
        'last_visit' => '2024-03-07 15:25:00'
    ],
    [
        'customer_id' => 'CUST003',
        'first_name' => 'Mike',
        'last_name' => 'Johnson',
        'email' => 'mike.j@email.com',
        'phone' => '+1122334455',
        'registration_date' => '2024-02-15',
        'total_orders' => 5,
        'total_spent' => 450.00,
        'status' => 'Inactive',
        'last_visit' => '2024-02-28 10:15:00'
    ]
];

// Mock data for filters
$customer_statuses = ['All', 'Active', 'Inactive'];
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2>Customer List</h2>
            <p class="text-muted">View and manage customer information</p>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="exportCustomers()">
                <i class="bi bi-download me-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Customers</h6>
                    <h3 class="mb-0">3</h3>
                    <small>Registered customers</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Active Customers</h6>
                    <h3 class="mb-0">2</h3>
                    <small>Currently active</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">New Today</h6>
                    <h3 class="mb-0">0</h3>
                    <small>New registrations</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Revenue</h6>
                    <h3 class="mb-0">$2,550.00</h3>
                    <small>From all customers</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="customerStatus">
                        <?php foreach ($customer_statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status); ?>">
                                <?php echo htmlspecialchars($status); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Registration Date</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="startDate">
                        <span class="input-group-text">to</span>
                        <input type="date" class="form-control" id="endDate">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" id="searchInput" 
                           placeholder="Customer ID, Name, Email, Phone...">
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

    <!-- Customers Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Registration Date</th>
                            <th>Total Orders</th>
                            <th>Total Spent</th>
                            <th>Last Visit</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer['customer_id']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?>
                                </td>
                                <td>
                                    <div><?php echo htmlspecialchars($customer['email']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($customer['phone']); ?></small>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($customer['registration_date'])); ?></td>
                                <td><?php echo $customer['total_orders']; ?></td>
                                <td>$<?php echo number_format($customer['total_spent'], 2); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($customer['last_visit'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $customer['status'] === 'Active' ? 'success' : 'danger'; 
                                    ?>">
                                        <?php echo htmlspecialchars($customer['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="viewCustomer('<?php echo $customer['customer_id']; ?>')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                onclick="editCustomer('<?php echo $customer['customer_id']; ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                onclick="viewHistory('<?php echo $customer['customer_id']; ?>')">
                                            <i class="bi bi-clock-history"></i>
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

<!-- Customer Details Modal -->
<div class="modal fade" id="customerDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Personal Information</h6>
                        <p class="mb-1"><strong>Customer ID:</strong> <span id="modalCustomerId"></span></p>
                        <p class="mb-1"><strong>Name:</strong> <span id="modalCustomerName"></span></p>
                        <p class="mb-1"><strong>Email:</strong> <span id="modalCustomerEmail"></span></p>
                        <p class="mb-1"><strong>Phone:</strong> <span id="modalCustomerPhone"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Account Information</h6>
                        <p class="mb-1"><strong>Registration Date:</strong> <span id="modalRegistrationDate"></span></p>
                        <p class="mb-1"><strong>Status:</strong> <span id="modalCustomerStatus"></span></p>
                        <p class="mb-1"><strong>Total Orders:</strong> <span id="modalTotalOrders"></span></p>
                        <p class="mb-1"><strong>Total Spent:</strong> <span id="modalTotalSpent"></span></p>
                    </div>
                </div>
                <div class="table-responsive">
                    <h6>Recent Orders</h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="modalRecentOrders">
                            <!-- Recent orders will be dynamically added here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="editCustomer(currentCustomerId)">
                    <i class="bi bi-pencil me-2"></i>Edit Customer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let customerDetailsModal;
let currentCustomerId;

document.addEventListener('DOMContentLoaded', function() {
    customerDetailsModal = new bootstrap.Modal(document.getElementById('customerDetailsModal'));
    
    // Initialize form submission
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
});

function applyFilters() {
    // Here you would typically make an AJAX call to fetch filtered customers
    alert('Applying filters...');
}

function viewCustomer(customerId) {
    currentCustomerId = customerId;
    // Here you would typically fetch customer details from the server
    const customer = <?php echo json_encode($customers); ?>[0]; // Using mock data for demo
    
    document.getElementById('modalCustomerId').textContent = customer.customer_id;
    document.getElementById('modalCustomerName').textContent = 
        customer.first_name + ' ' + customer.last_name;
    document.getElementById('modalCustomerEmail').textContent = customer.email;
    document.getElementById('modalCustomerPhone').textContent = customer.phone;
    document.getElementById('modalRegistrationDate').textContent = 
        new Date(customer.registration_date).toLocaleDateString();
    document.getElementById('modalCustomerStatus').textContent = customer.status;
    document.getElementById('modalTotalOrders').textContent = customer.total_orders;
    document.getElementById('modalTotalSpent').textContent = 
        '$' + customer.total_spent.toFixed(2);
    
    // Here you would typically fetch recent orders from the server
    const recentOrders = document.getElementById('modalRecentOrders');
    recentOrders.innerHTML = `
        <tr>
            <td>ORD001</td>
            <td>Mar 7, 2024 14:30</td>
            <td>$250.00</td>
            <td><span class="badge bg-success">Completed</span></td>
        </tr>
        <tr>
            <td>ORD002</td>
            <td>Mar 6, 2024 15:45</td>
            <td>$350.00</td>
            <td><span class="badge bg-success">Completed</span></td>
        </tr>
    `;
    
    customerDetailsModal.show();
}

function editCustomer(customerId) {
    alert('Editing customer: ' + customerId);
    // Implement customer editing functionality
}

function viewHistory(customerId) {
    alert('Viewing history for customer: ' + customerId);
    // Implement customer history viewing functionality
}

function exportCustomers() {
    alert('Exporting customers...');
    // Implement export functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 