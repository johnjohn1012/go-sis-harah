<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for suppliers
$suppliers = [
    [
        'supplier_id' => 1,
        'supplier_name' => 'Baking Supplies Co.',
        'contact_person' => 'John Smith',
        'email' => 'john@bakingsupplies.com',
        'phone' => '09123456789',
        'address' => '123 Baking Street, Manila',
        'status' => 'Active',
        'items_supplied' => 15,
        'last_order_date' => '2024-03-20'
    ],
    [
        'supplier_id' => 2,
        'supplier_name' => 'Dairy Products Inc.',
        'contact_person' => 'Maria Garcia',
        'email' => 'maria@dairyproducts.com',
        'phone' => '09187654321',
        'address' => '456 Dairy Avenue, Quezon City',
        'status' => 'Active',
        'items_supplied' => 8,
        'last_order_date' => '2024-03-19'
    ],
    [
        'supplier_id' => 3,
        'supplier_name' => 'Spices & More',
        'contact_person' => 'Pedro Santos',
        'email' => 'pedro@spicesandmore.com',
        'phone' => '09111223344',
        'address' => '789 Spice Road, Makati',
        'status' => 'Active',
        'items_supplied' => 12,
        'last_order_date' => '2024-03-18'
    ]
];

$status_badges = [
    'Active' => 'success',
    'Inactive' => 'danger',
    'Pending' => 'warning'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Supplier Management</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                <i class="bi bi-plus-circle me-2"></i>Add New Supplier
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Suppliers List</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search suppliers...">
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
                                    <th>Supplier ID</th>
                                    <th>Supplier Name</th>
                                    <th>Contact Person</th>
                                    <th>Contact Info</th>
                                    <th>Address</th>
                                    <th>Items Supplied</th>
                                    <th>Last Order</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <tr>
                                        <td><?php echo $supplier['supplier_id']; ?></td>
                                        <td><?php echo htmlspecialchars($supplier['supplier_name']); ?></td>
                                        <td><?php echo htmlspecialchars($supplier['contact_person']); ?></td>
                                        <td>
                                            <div><?php echo htmlspecialchars($supplier['email']); ?></div>
                                            <div><?php echo htmlspecialchars($supplier['phone']); ?></div>
                                        </td>
                                        <td><?php echo htmlspecialchars($supplier['address']); ?></td>
                                        <td><?php echo $supplier['items_supplied']; ?> items</td>
                                        <td><?php echo date('Y-m-d', strtotime($supplier['last_order_date'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$supplier['status']]; ?>">
                                                <?php echo $supplier['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" title="View Items">
                                                <i class="bi bi-box"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Person</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" rows="3" required></textarea>
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
                <button type="button" class="btn btn-primary">Save Supplier</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 