<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for product recipes
$product_recipes = [
    [
        'recipe_id' => 1,
        'product_name' => 'Chocolate Cake',
        'category' => 'Cakes',
        'yield' => '1 cake',
        'yield_quantity' => 1,
        'unit' => 'piece',
        'ingredients_count' => 8,
        'status' => 'Active',
        'last_updated' => '2024-03-20'
    ],
    [
        'recipe_id' => 2,
        'product_name' => 'Vanilla Cupcake',
        'category' => 'Cupcakes',
        'yield' => '12 pieces',
        'yield_quantity' => 12,
        'unit' => 'pieces',
        'ingredients_count' => 6,
        'status' => 'Active',
        'last_updated' => '2024-03-19'
    ],
    [
        'recipe_id' => 3,
        'product_name' => 'Bread Loaf',
        'category' => 'Bread',
        'yield' => '2 loaves',
        'yield_quantity' => 2,
        'unit' => 'pieces',
        'ingredients_count' => 5,
        'status' => 'Active',
        'last_updated' => '2024-03-18'
    ]
];

$categories = [
    'Cakes' => 'Cakes',
    'Cupcakes' => 'Cupcakes',
    'Bread' => 'Bread',
    'Cookies' => 'Cookies',
    'Pastries' => 'Pastries'
];

$status_badges = [
    'Active' => 'success',
    'Inactive' => 'danger',
    'Draft' => 'warning'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Product Recipes</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecipeModal">
                <i class="bi bi-plus-circle me-2"></i>Add New Recipe
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Recipes List</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search recipes...">
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
                                    <th>Recipe ID</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Yield</th>
                                    <th>Ingredients</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($product_recipes as $recipe): ?>
                                    <tr>
                                        <td><?php echo $recipe['recipe_id']; ?></td>
                                        <td><?php echo htmlspecialchars($recipe['product_name']); ?></td>
                                        <td><?php echo htmlspecialchars($recipe['category']); ?></td>
                                        <td><?php echo $recipe['yield']; ?></td>
                                        <td><?php echo $recipe['ingredients_count']; ?> ingredients</td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$recipe['status']]; ?>">
                                                <?php echo $recipe['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($recipe['last_updated'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Recipe">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit Recipe">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" title="Calculate Cost">
                                                <i class="bi bi-calculator"></i>
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

<!-- Add Recipe Modal -->
<div class="modal fade" id="addRecipeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Recipe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Product Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select class="form-select" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $key => $value): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Yield Quantity</label>
                            <input type="number" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Unit</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Draft">Draft</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Ingredient</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Cost</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="recipeIngredients">
                                <tr>
                                    <td>
                                        <select class="form-select" required>
                                            <option value="">Select Ingredient</option>
                                            <option value="1">Flour</option>
                                            <option value="2">Sugar</option>
                                            <option value="3">Eggs</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" step="0.01" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-ingredient">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total Cost:</strong></td>
                                    <td colspan="2">
                                        <input type="text" class="form-control total-cost" readonly>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <button type="button" class="btn btn-secondary btn-sm" id="addIngredient">
                            <i class="bi bi-plus-circle me-1"></i>Add Ingredient
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Instructions</label>
                        <textarea class="form-control" rows="4"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Recipe</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 