<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for raw materials
$raw_materials = [
    [
        'material_id' => 1,
        'material_name' => 'Flour',
        'category_name' => 'Baking Supplies',
        'unit' => 'kg',
        'current_stock' => 25,
        'reorder_level' => 30,
        'status' => 'Active'
    ],
    [
        'material_id' => 2,
        'material_name' => 'Sugar',
        'category_name' => 'Baking Supplies',
        'unit' => 'kg',
        'current_stock' => 15,
        'reorder_level' => 20,
        'status' => 'Active'
    ],
    [
        'material_id' => 3,
        'material_name' => 'Eggs',
        'category_name' => 'Dairy',
        'unit' => 'pieces',
        'current_stock' => 100,
        'reorder_level' => 150,
        'status' => 'Active'
    ]
];

$categories = [
    ['category_id' => 1, 'category_name' => 'Baking Supplies'],
    ['category_id' => 2, 'category_name' => 'Dairy'],
    ['category_id' => 3, 'category_name' => 'Spices']
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Raw Materials Management</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterialModal">
                <i class="bi bi-plus-circle me-2"></i>Add New Material
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Raw Materials List</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search materials...">
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
                                    <th>ID</th>
                                    <th>Material Name</th>
                                    <th>Category</th>
                                    <th>Unit</th>
                                    <th>Current Stock</th>
                                    <th>Reorder Level</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($raw_materials as $material): ?>
                                    <tr>
                                        <td><?php echo $material['material_id']; ?></td>
                                        <td><?php echo htmlspecialchars($material['material_name']); ?></td>
                                        <td><?php echo htmlspecialchars($material['category_name']); ?></td>
                                        <td><?php echo $material['unit']; ?></td>
                                        <td>
                                            <span class="<?php echo $material['current_stock'] <= $material['reorder_level'] ? 'text-danger' : ''; ?>">
                                                <?php echo $material['current_stock']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $material['reorder_level']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $material['status'] === 'Active' ? 'success' : 'danger'; ?>">
                                                <?php echo $material['status']; ?>
                                            </span>
                                        </td>
                                        <td>
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

<!-- Add Material Modal -->
<div class="modal fade" id="addMaterialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Material Name</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>">
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Initial Stock</label>
                        <input type="number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reorder Level</label>
                        <input type="number" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Material</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 