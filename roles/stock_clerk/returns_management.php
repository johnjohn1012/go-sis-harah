<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for returns
$returns = [
    [
        'return_id' => 1,
        'return_number' => 'RET-2024-001',
        'supplier_name' => 'Baking Supplies Co.',
        'item_name' => 'Flour',
        'quantity' => 10,
        'unit' => 'kg',
        'reason' => 'Quality Issue',
        'return_date' => '2024-03-20',
        'status' => 'Pending Approval',
        'created_by' => 'John Doe'
    ],
    [
        'return_id' => 2,
        'return_number' => 'RET-2024-002',
        'supplier_name' => 'Dairy Products Inc.',
        'item_name' => 'Eggs',
        'quantity' => 50,
        'unit' => 'pieces',
        'reason' => 'Damaged During Transit',
        'return_date' => '2024-03-19',
        'status' => 'Approved',
        'created_by' => 'John Doe'
    ],
    [
        'return_id' => 3,
        'return_number' => 'RET-2024-003',
        'supplier_name' => 'Spices & More',
        'item_name' => 'Vanilla Extract',
        'quantity' => 2,
        'unit' => 'liters',
        'reason' => 'Wrong Item Received',
        'return_date' => '2024-03-18',
        'status' => 'Completed',
        'created_by' => 'John Doe'
    ]
];

$status_badges = [
    'Pending Approval' => 'warning',
    'Approved' => 'success',
    'Completed' => 'primary',
    'Rejected' => 'danger',
    'Cancelled' => 'secondary'
];

$return_reasons = [
    'Quality Issue' => 'Quality Issue',
    'Damaged During Transit' => 'Damaged During Transit',
    'Wrong Item Received' => 'Wrong Item Received',
    'Expired Items' => 'Expired Items',
    'Other' => 'Other'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Returns Management</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReturnModal">
                <i class="bi bi-plus-circle me-2"></i>Create Return
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Returns List</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search returns...">
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
                                    <th>Return ID</th>
                                    <th>Return Number</th>
                                    <th>Supplier</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Reason</th>
                                    <th>Return Date</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($returns as $return): ?>
                                    <tr>
                                        <td><?php echo $return['return_id']; ?></td>
                                        <td><?php echo $return['return_number']; ?></td>
                                        <td><?php echo htmlspecialchars($return['supplier_name']); ?></td>
                                        <td><?php echo htmlspecialchars($return['item_name']); ?></td>
                                        <td><?php echo $return['quantity'] . ' ' . $return['unit']; ?></td>
                                        <td><?php echo htmlspecialchars($return['reason']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($return['return_date'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$return['status']]; ?>">
                                                <?php echo $return['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($return['created_by']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" title="Process Return">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
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

<!-- Add Return Modal -->
<div class="modal fade" id="addReturnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Return</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <select class="form-select" required>
                            <option value="">Select Supplier</option>
                            <option value="1">Baking Supplies Co.</option>
                            <option value="2">Dairy Products Inc.</option>
                            <option value="3">Spices & More</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item</label>
                        <select class="form-select" required>
                            <option value="">Select Item</option>
                            <option value="1">Flour</option>
                            <option value="2">Eggs</option>
                            <option value="3">Vanilla Extract</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Return Reason</label>
                        <select class="form-select" required>
                            <option value="">Select Reason</option>
                            <?php foreach ($return_reasons as $key => $value): ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Return Date</label>
                        <input type="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Create Return</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 