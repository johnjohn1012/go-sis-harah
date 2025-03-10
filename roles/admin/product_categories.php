<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for product categories
$product_categories = [
    [
        'category_id' => 'CAT001',
        'category_name' => 'Cakes',
        'description' => 'Various types of cakes including birthday cakes, wedding cakes, and specialty cakes',
        'products_count' => 25,
        'status' => 'Active',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'category_id' => 'CAT002',
        'category_name' => 'Bread',
        'description' => 'Freshly baked bread and bread products',
        'products_count' => 15,
        'status' => 'Active',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'category_id' => 'CAT003',
        'category_name' => 'Pastries',
        'description' => 'Assorted pastries and baked goods',
        'products_count' => 30,
        'status' => 'Active',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'category_id' => 'CAT004',
        'category_name' => 'Cookies',
        'description' => 'Various types of cookies and biscuits',
        'products_count' => 20,
        'status' => 'Active',
        'created_at' => '2024-01-15',
        'last_updated' => '2024-03-07'
    ],
    [
        'category_id' => 'CAT005',
        'category_name' => 'Specialty Items',
        'description' => 'Special occasion and custom-made items',
        'products_count' => 10,
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
            <h2 class="mb-4">Product Categories</h2>
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
                                <option value="products">Products Count</option>
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
                                    <th>Products Count</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($product_categories as $category): ?>
                                    <tr>
                                        <td><?php echo $category['category_id']; ?></td>
                                        <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                                <?php echo htmlspecialchars($category['description']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $category['products_count']; ?></td>
                                        <td>
                                            <span class="badge <?php echo $status_badges[$category['status']]; ?>">
                                                <?php echo $category['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($category['created_at'])); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($category['last_updated'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Edit Category" onclick="editCategory('<?php echo $category['category_id']; ?>')">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" title="View Products" onclick="viewProducts('<?php echo $category['category_id']; ?>')">
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
                <h5 class="modal-title">Add New Category</h5>
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

function viewProducts(categoryId) {
    alert('Viewing products for category: ' + categoryId);
    // Implement view products functionality
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