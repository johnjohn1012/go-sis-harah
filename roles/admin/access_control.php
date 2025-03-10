<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for roles and permissions
$roles = [
    [
        'role_id' => 1,
        'role_name' => 'Admin',
        'description' => 'Full system access',
        'permissions_count' => 25,
        'users_count' => 2,
        'status' => 'Active'
    ],
    [
        'role_id' => 2,
        'role_name' => 'Stock Clerk',
        'description' => 'Inventory management access',
        'permissions_count' => 15,
        'users_count' => 5,
        'status' => 'Active'
    ],
    [
        'role_id' => 3,
        'role_name' => 'Cashier',
        'description' => 'Sales and POS access',
        'permissions_count' => 10,
        'users_count' => 8,
        'status' => 'Active'
    ]
];

$permissions = [
    'inventory' => [
        'view_inventory' => 'View Inventory',
        'add_inventory' => 'Add Inventory',
        'edit_inventory' => 'Edit Inventory',
        'delete_inventory' => 'Delete Inventory',
        'adjust_stock' => 'Adjust Stock'
    ],
    'sales' => [
        'view_sales' => 'View Sales',
        'create_sale' => 'Create Sale',
        'void_sale' => 'Void Sale',
        'view_reports' => 'View Reports'
    ],
    'users' => [
        'view_users' => 'View Users',
        'add_user' => 'Add User',
        'edit_user' => 'Edit User',
        'delete_user' => 'Delete User',
        'manage_roles' => 'Manage Roles'
    ],
    'settings' => [
        'view_settings' => 'View Settings',
        'edit_settings' => 'Edit Settings',
        'manage_backup' => 'Manage Backup'
    ]
];

$status_badges = [
    'Active' => 'success',
    'Inactive' => 'danger'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Access Control</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                <i class="bi bi-plus-circle me-2"></i>Add New Role
            </button>
        </div>
    </div>

    <!-- Roles Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Roles</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search roles...">
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
                                    <th>Role ID</th>
                                    <th>Role Name</th>
                                    <th>Description</th>
                                    <th>Permissions</th>
                                    <th>Users</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roles as $role): ?>
                                    <tr>
                                        <td><?php echo $role['role_id']; ?></td>
                                        <td><?php echo htmlspecialchars($role['role_name']); ?></td>
                                        <td><?php echo htmlspecialchars($role['description']); ?></td>
                                        <td><?php echo $role['permissions_count']; ?> permissions</td>
                                        <td><?php echo $role['users_count']; ?> users</td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$role['status']]; ?>">
                                                <?php echo $role['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Permissions">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit Role">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete Role">
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

    <!-- Permissions Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Permissions Matrix</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Permission</th>
                                    <?php foreach ($roles as $role): ?>
                                        <th><?php echo htmlspecialchars($role['role_name']); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($permissions as $category => $perms): ?>
                                    <tr class="table-secondary">
                                        <td colspan="<?php echo count($roles) + 1; ?>">
                                            <strong><?php echo ucfirst($category); ?></strong>
                                        </td>
                                    </tr>
                                    <?php foreach ($perms as $key => $name): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($name); ?></td>
                                            <?php foreach ($roles as $role): ?>
                                                <td class="text-center">
                                                    <input type="checkbox" class="form-check-input" 
                                                           <?php echo ($role['role_name'] === 'Admin') ? 'checked disabled' : ''; ?>>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Role Name</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Permissions</label>
                        <?php foreach ($permissions as $category => $perms): ?>
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><?php echo ucfirst($category); ?></h6>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($perms as $key => $name): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="<?php echo $key; ?>">
                                            <label class="form-check-label" for="<?php echo $key; ?>">
                                                <?php echo htmlspecialchars($name); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Create Role</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 