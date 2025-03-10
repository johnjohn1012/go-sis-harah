<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for user accounts
$user_accounts = [
    [
        'user_id' => 'USR001',
        'username' => 'johndoe',
        'email' => 'john.doe@example.com',
        'role' => 'Stock Clerk',
        'status' => 'Active',
        'last_login' => '2024-03-20 14:30:00',
        'created_at' => '2024-01-15',
        '2fa_enabled' => true
    ],
    [
        'user_id' => 'USR002',
        'username' => 'janesmith',
        'email' => 'jane.smith@example.com',
        'role' => 'Cashier',
        'status' => 'Active',
        'last_login' => '2024-03-20 15:45:00',
        'created_at' => '2024-02-01',
        '2fa_enabled' => false
    ],
    [
        'user_id' => 'USR003',
        'username' => 'mikejohnson',
        'email' => 'mike.johnson@example.com',
        'role' => 'Admin',
        'status' => 'Active',
        'last_login' => '2024-03-20 16:20:00',
        'created_at' => '2024-02-15',
        '2fa_enabled' => true
    ]
];

$status_badges = [
    'Active' => 'success',
    'Inactive' => 'danger',
    'Suspended' => 'warning'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">User Accounts</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus-circle me-2"></i>Add New User
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">System Users</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search users...">
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
                                    <th>User ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>2FA</th>
                                    <th>Last Login</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($user_accounts as $user): ?>
                                    <tr>
                                        <td><?php echo $user['user_id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$user['status']]; ?>">
                                                <?php echo $user['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($user['2fa_enabled']): ?>
                                                <span class="badge bg-success">Enabled</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Disabled</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($user['last_login'])); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" title="Reset Password">
                                                <i class="bi bi-key"></i>
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <select class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Stock Clerk">Stock Clerk</option>
                                <option value="Cashier">Cashier</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Suspended">Suspended</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">2FA</label>
                            <select class="form-select" required>
                                <option value="1">Enable</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password Expiry</label>
                            <select class="form-select" required>
                                <option value="30">30 days</option>
                                <option value="60">60 days</option>
                                <option value="90">90 days</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="forcePasswordChange">
                            <label class="form-check-label" for="forcePasswordChange">
                                Force password change on first login
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Create User</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 