<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for material categories
$material_categories = [
    [
        'category_id' => 'MAT001',
        'category_name' => 'Flour & Grains',
        'description' => 'Various types of flour and grain products used in baking',
        'items_count' => 12,
        'total_stock' => 1500,
        'unit' => 'kg',
        'status' => 'Active',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'category_id' => 'MAT002',
        'category_name' => 'Sweeteners',
        'description' => 'Sugar, honey, and other sweetening agents',
        'items_count' => 8,
        'total_stock' => 800,
        'unit' => 'kg',
        'status' => 'Active',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'category_id' => 'MAT003',
        'category_name' => 'Dairy & Eggs',
        'description' => 'Milk, butter, eggs, and other dairy products',
        'items_count' => 10,
        'total_stock' => 500,
        'unit' => 'kg',
        'status' => 'Active',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'category_id' => 'MAT004',
        'category_name' => 'Fats & Oils',
        'description' => 'Butter, margarine, and cooking oils',
        'items_count' => 6,
        'total_stock' => 300,
        'unit' => 'kg',
        'status' => 'Active',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'category_id' => 'MAT005',
        'category_name' => 'Packaging',
        'description' => 'Boxes, bags, and other packaging materials',
        'items_count' => 15,
        'total_stock' => 2000,
        'unit' => 'pieces',
        'status' => 'Active',
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
            <h2 class="mb-4">Material Categories</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-circle me-2"></i>Add Category
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
                            <input type="text" class="form-control" placeholder="Search categories...">
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
                            <label class="form-label">Sort By</label>
                            <select class="form-select">
                                <option value="name">Name</option>
                                <option value="items">Items Count</option>
                                <option value="stock">Total Stock</option>
                                <option value="date">Last Updated</option>
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

    <!-- Categories Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Category ID</th>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th>Items Count</th>
                                    <th>Total Stock</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($material_categories as $category): ?>
                                    <tr>
                                        <td><?php echo $category['category_id']; ?></td>
                                        <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                                <?php echo htmlspecialchars($category['description']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $category['items_count']; ?></td>
                                        <td><?php echo $category['total_stock']; ?></td>
                                        <td><?php echo htmlspecialchars($category['unit']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $status_badges[$category['status']]; ?>">
                                                <?php echo $category['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($category['last_updated'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit Category" onclick="editCategory('<?php echo $category['category_id']; ?>')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" title="View Items" onclick="viewItems('<?php echo $category['category_id']; ?>')">
                                                <i class="bi bi-box"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete Category" onclick="deleteCategory('<?php echo $category['category_id']; ?>')">
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

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Material Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Default Unit</label>
                        <select class="form-select" required>
                            <option value="kg">Kilograms (kg)</option>
                            <option value="g">Grams (g)</option>
                            <option value="l">Liters (L)</option>
                            <option value="ml">Milliliters (mL)</option>
                            <option value="pieces">Pieces</option>
                            <option value="boxes">Boxes</option>
                            <option value="bags">Bags</option>
                        </select>
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
                <button type="button" class="btn btn-primary" onclick="saveCategory()">Save Category</button>
            </div>
        </div>
    </div>
</div>

<script>
function editCategory(categoryId) {
    alert('Editing category: ' + categoryId);
    // Implement edit functionality
}

function viewItems(categoryId) {
    alert('Viewing items for category: ' + categoryId);
    // Implement view items functionality
}

function deleteCategory(categoryId) {
    if (confirm('Are you sure you want to delete this category?')) {
        alert('Deleting category: ' + categoryId);
        // Implement delete functionality
    }
}

function saveCategory() {
    alert('Saving new category...');
    // Implement save functionality
    $('#addCategoryModal').modal('hide');
}
</script>

<?php require_once '../../includes/footer.php'; ?> 