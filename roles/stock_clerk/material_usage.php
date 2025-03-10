<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for material usage
$material_usage = [
    [
        'usage_id' => 1,
        'production_date' => '2024-03-20',
        'product_name' => 'Chocolate Cake',
        'batch_number' => 'BATCH-2024-001',
        'quantity_produced' => 5,
        'unit' => 'pieces',
        'materials_used' => 8,
        'status' => 'Completed',
        'created_by' => 'John Doe'
    ],
    [
        'usage_id' => 2,
        'production_date' => '2024-03-19',
        'product_name' => 'Vanilla Cupcake',
        'batch_number' => 'BATCH-2024-002',
        'quantity_produced' => 48,
        'unit' => 'pieces',
        'materials_used' => 6,
        'status' => 'In Progress',
        'created_by' => 'John Doe'
    ],
    [
        'usage_id' => 3,
        'production_date' => '2024-03-18',
        'product_name' => 'Bread Loaf',
        'batch_number' => 'BATCH-2024-003',
        'quantity_produced' => 4,
        'unit' => 'pieces',
        'materials_used' => 5,
        'status' => 'Completed',
        'created_by' => 'John Doe'
    ]
];

$status_badges = [
    'Pending' => 'warning',
    'In Progress' => 'info',
    'Completed' => 'success',
    'Cancelled' => 'danger'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Material Usage</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUsageModal">
                <i class="bi bi-plus-circle me-2"></i>Record Material Usage
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Material Usage Records</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search records...">
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
                                    <th>Usage ID</th>
                                    <th>Production Date</th>
                                    <th>Product</th>
                                    <th>Batch Number</th>
                                    <th>Quantity Produced</th>
                                    <th>Materials Used</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($material_usage as $usage): ?>
                                    <tr>
                                        <td><?php echo $usage['usage_id']; ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($usage['production_date'])); ?></td>
                                        <td><?php echo htmlspecialchars($usage['product_name']); ?></td>
                                        <td><?php echo $usage['batch_number']; ?></td>
                                        <td><?php echo $usage['quantity_produced'] . ' ' . $usage['unit']; ?></td>
                                        <td><?php echo $usage['materials_used']; ?> materials</td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$usage['status']]; ?>">
                                                <?php echo $usage['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($usage['created_by']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" title="Complete Production">
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

<!-- Add Usage Modal -->
<div class="modal fade" id="addUsageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Record Material Usage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Product</label>
                            <select class="form-select" required>
                                <option value="">Select Product</option>
                                <option value="1">Chocolate Cake</option>
                                <option value="2">Vanilla Cupcake</option>
                                <option value="3">Bread Loaf</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Production Date</label>
                            <input type="date" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Batch Number</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Quantity to Produce</label>
                            <input type="number" class="form-control" required>
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Required Qty</th>
                                    <th>Actual Qty Used</th>
                                    <th>Unit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="materialUsage">
                                <tr>
                                    <td>Flour</td>
                                    <td>2.5 kg</td>
                                    <td>
                                        <input type="number" class="form-control" step="0.01" required>
                                    </td>
                                    <td>kg</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-material">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-secondary btn-sm" id="addMaterial">
                            <i class="bi bi-plus-circle me-1"></i>Add Material
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Usage Record</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 