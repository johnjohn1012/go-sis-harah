<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for 2FA settings and users
$two_factor_settings = [
    'enabled' => true,
    'method' => 'authenticator_app',
    'backup_codes_count' => 5,
    'backup_codes_length' => 8,
    'require_backup_codes' => true,
    'grace_period_minutes' => 5
];

$users_2fa_status = [
    [
        'user_id' => 'USR001',
        'username' => 'johndoe',
        'email' => 'john.doe@example.com',
        'role' => 'Stock Clerk',
        '2fa_enabled' => true,
        '2fa_method' => 'Authenticator App',
        'last_2fa_setup' => '2024-03-15 10:30:00',
        'backup_codes_remaining' => 3
    ],
    [
        'user_id' => 'USR002',
        'username' => 'janesmith',
        'email' => 'jane.smith@example.com',
        'role' => 'Cashier',
        '2fa_enabled' => false,
        '2fa_method' => 'Not Set',
        'last_2fa_setup' => null,
        'backup_codes_remaining' => 0
    ],
    [
        'user_id' => 'USR003',
        'username' => 'mikejohnson',
        'email' => 'mike.johnson@example.com',
        'role' => 'Admin',
        '2fa_enabled' => true,
        '2fa_method' => 'SMS',
        'last_2fa_setup' => '2024-03-18 15:45:00',
        'backup_codes_remaining' => 5
    ]
];

$status_badges = [
    true => 'success',
    false => 'danger'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Two-Factor Authentication Management</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editSettingsModal">
                <i class="bi bi-gear me-2"></i>Edit Settings
            </button>
        </div>
    </div>

    <!-- 2FA Settings Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Current 2FA Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">2FA Status</label>
                                <div>
                                    <span class="badge bg-<?php echo $status_badges[$two_factor_settings['enabled']]; ?>">
                                        <?php echo $two_factor_settings['enabled'] ? 'Enabled' : 'Disabled'; ?>
                                    </span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Default Method</label>
                                <div><?php echo ucfirst(str_replace('_', ' ', $two_factor_settings['method'])); ?></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Grace Period</label>
                                <div><?php echo $two_factor_settings['grace_period_minutes']; ?> minutes</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Backup Codes</label>
                                <div>Generate <?php echo $two_factor_settings['backup_codes_count']; ?> codes</div>
                                <div>Code length: <?php echo $two_factor_settings['backup_codes_length']; ?> characters</div>
                                <div>Required: <?php echo $two_factor_settings['require_backup_codes'] ? 'Yes' : 'No'; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users 2FA Status -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Users 2FA Status</h5>
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
                                    <th>2FA Status</th>
                                    <th>Method</th>
                                    <th>Last Setup</th>
                                    <th>Backup Codes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users_2fa_status as $user): ?>
                                    <tr>
                                        <td><?php echo $user['user_id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badges[$user['2fa_enabled']]; ?>">
                                                <?php echo $user['2fa_enabled'] ? 'Enabled' : 'Disabled'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['2fa_method']); ?></td>
                                        <td><?php echo $user['last_2fa_setup'] ? date('Y-m-d H:i', strtotime($user['last_2fa_setup'])) : 'Never'; ?></td>
                                        <td><?php echo $user['backup_codes_remaining']; ?> remaining</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning" title="Reset 2FA">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" title="Generate Backup Codes">
                                                <i class="bi bi-key"></i>
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

<!-- Edit Settings Modal -->
<div class="modal fade" id="editSettingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit 2FA Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSettingsForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="enabled" 
                                       <?php echo $two_factor_settings['enabled'] ? 'checked' : ''; ?>>
                                <label class="form-check-label">Enable 2FA</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Default Method</label>
                            <select class="form-select" name="method" required>
                                <option value="authenticator_app" <?php echo $two_factor_settings['method'] === 'authenticator_app' ? 'selected' : ''; ?>>
                                    Authenticator App
                                </option>
                                <option value="sms" <?php echo $two_factor_settings['method'] === 'sms' ? 'selected' : ''; ?>>
                                    SMS
                                </option>
                                <option value="email" <?php echo $two_factor_settings['method'] === 'email' ? 'selected' : ''; ?>>
                                    Email
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Grace Period (Minutes)</label>
                            <input type="number" class="form-control" name="grace_period_minutes" 
                                   value="<?php echo $two_factor_settings['grace_period_minutes']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Backup Codes Count</label>
                            <input type="number" class="form-control" name="backup_codes_count" 
                                   value="<?php echo $two_factor_settings['backup_codes_count']; ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Backup Codes Length</label>
                            <input type="number" class="form-control" name="backup_codes_length" 
                                   value="<?php echo $two_factor_settings['backup_codes_length']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="require_backup_codes" 
                                       <?php echo $two_factor_settings['require_backup_codes'] ? 'checked' : ''; ?>>
                                <label class="form-check-label">Require backup codes setup</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 