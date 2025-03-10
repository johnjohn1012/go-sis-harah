<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for suppliers
$suppliers = [
    [
        'supplier_id' => 'SUP001',
        'company_name' => 'Bakery Supplies Co.',
        'contact_person' => 'John Smith',
        'email' => 'john.smith@bakerysupplies.com',
        'phone' => '+63 912 345 6789',
        'address' => '123 Baking Street, Manila, Philippines',
        'categories' => ['Flour & Grains', 'Sweeteners', 'Dairy & Eggs'],
        'status' => 'Active',
        'total_orders' => 150,
        'total_purchases' => 750000.00,
        'last_order' => '2024-03-05',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'supplier_id' => 'SUP002',
        'company_name' => 'Fresh Ingredients Ltd.',
        'contact_person' => 'Maria Garcia',
        'email' => 'maria.garcia@freshingredients.com',
        'phone' => '+63 923 456 7890',
        'address' => '456 Fresh Avenue, Quezon City, Philippines',
        'categories' => ['Dairy & Eggs', 'Fats & Oils'],
        'status' => 'Active',
        'total_orders' => 120,
        'total_purchases' => 450000.00,
        'last_order' => '2024-03-03',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'supplier_id' => 'SUP003',
        'company_name' => 'Packaging Solutions',
        'contact_person' => 'Robert Chen',
        'email' => 'robert.chen@packagingsolutions.com',
        'phone' => '+63 934 567 8901',
        'address' => '789 Package Road, Makati, Philippines',
        'categories' => ['Packaging'],
        'status' => 'Active',
        'total_orders' => 80,
        'total_purchases' => 350000.00,
        'last_order' => '2024-03-04',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'supplier_id' => 'SUP004',
        'company_name' => 'Equipment Pro',
        'contact_person' => 'Sarah Johnson',
        'email' => 'sarah.johnson@equipmentpro.com',
        'phone' => '+63 945 678 9012',
        'address' => '321 Equipment Lane, Pasig, Philippines',
        'categories' => ['Equipment', 'Tools'],
        'status' => 'Active',
        'total_orders' => 50,
        'total_purchases' => 150000.00,
        'last_order' => '2024-02-28',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'supplier_id' => 'SUP005',
        'company_name' => 'Quality Ingredients',
        'contact_person' => 'David Lee',
        'email' => 'david.lee@qualityingredients.com',
        'phone' => '+63 956 789 0123',
        'address' => '654 Quality Street, Taguig, Philippines',
        'categories' => ['Flour & Grains', 'Sweeteners', 'Dairy & Eggs'],
        'status' => 'Active',
        'total_orders' => 100,
        'total_purchases' => 500000.00,
        'last_order' => '2024-03-06',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ]
];

$status_badges = [
    'Active' => 'bg-success',
    'Inactive' => 'bg-danger',
    'Pending' => 'bg-warning'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Supplier Management</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                <i class="bi bi-plus-circle me-2"></i>Add Supplier
            </button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" placeholder="Search suppliers...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select class="form-select">
                                <option value="all">All Categories</option>
                                <option value="flour">Flour & Grains</option>
                                <option value="sweeteners">Sweeteners</option>
                                <option value="dairy">Dairy & Eggs</option>
                                <option value="fats">Fats & Oils</option>
                                <option value="packaging">Packaging</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Supplier ID</th>
                                    <th>Company Name</th>
                                    <th>Contact Person</th>
                                    <th>Contact Info</th>
                                    <th>Categories</th>
                                    <th>Total Orders</th>
                                    <th>Total Purchases</th>
                                    <th>Status</th>
                                    <th>Last Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <tr>
                                        <td><?php echo $supplier['supplier_id']; ?></td>
                                        <td><?php echo htmlspecialchars($supplier['company_name']); ?></td>
                                        <td><?php echo htmlspecialchars($supplier['contact_person']); ?></td>
                                        <td>
                                            <div><?php echo htmlspecialchars($supplier['email']); ?></div>
                                            <div><?php echo htmlspecialchars($supplier['phone']); ?></div>
                                        </td>
                                        <td>
                                            <?php foreach ($supplier['categories'] as $category): ?>
                                                <span class="badge bg-secondary me-1"><?php echo $category; ?></span>
                                            <?php endforeach; ?>
                                        </td>
                                        <td><?php echo $supplier['total_orders']; ?></td>
                                        <td>₱<?php echo number_format($supplier['total_purchases'], 2); ?></td>
                                        <td>
                                            <span class="badge <?php echo $status_badges[$supplier['status']]; ?>">
                                                <?php echo $supplier['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($supplier['last_order'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit Supplier" onclick="editSupplier('<?php echo $supplier['supplier_id']; ?>')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" title="View Orders" onclick="viewOrders('<?php echo $supplier['supplier_id']; ?>')">
                                                <i class="bi bi-list"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete Supplier" onclick="deleteSupplier('<?php echo $supplier['supplier_id']; ?>')">
                                                <i class="bi bi-trash"></i>
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

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addSupplierForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Person</label>
                            <input type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categories</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="flour">
                                    <label class="form-check-label">Flour & Grains</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="sweeteners">
                                    <label class="form-check-label">Sweeteners</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="dairy">
                                    <label class="form-check-label">Dairy & Eggs</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="fats">
                                    <label class="form-check-label">Fats & Oils</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="packaging">
                                    <label class="form-check-label">Packaging</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveSupplier()">Save Supplier</button>
            </div>
        </div>
    </div>
</div>

<script>
function editSupplier(supplierId) {
    alert('Editing supplier: ' + supplierId);
    // Implement edit functionality
}

function viewOrders(supplierId) {
    alert('Viewing orders for supplier: ' + supplierId);
    // Implement view orders functionality
}

function deleteSupplier(supplierId) {
    if (confirm('Are you sure you want to delete this supplier?')) {
        alert('Deleting supplier: ' + supplierId);
        // Implement delete functionality
    }
}

function saveSupplier() {
    alert('Saving new supplier...');
    // Implement save functionality
    $('#addSupplierModal').modal('hide');
}
</script>

<?php require_once '../../includes/footer.php'; ?> 