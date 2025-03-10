<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for security settings
$security_settings = [
    'password_policy' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_special_chars' => true,
        'password_expiry_days' => 90,
        'password_history_count' => 5
    ],
    'session_settings' => [
        'session_timeout_minutes' => 30,
        'max_concurrent_sessions' => 2,
        'force_logout_on_timeout' => true,
        'remember_me_days' => 7
    ],
    'login_settings' => [
        'max_login_attempts' => 5,
        'lockout_duration_minutes' => 15,
        'require_2fa' => false,
        'allowed_ips' => ['192.168.1.1', '192.168.1.2']
    ],
    'audit_settings' => [
        'log_login_attempts' => true,
        'log_password_changes' => true,
        'log_user_activity' => true,
        'retention_days' => 90
    ]
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Security Settings</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" id="saveSettings">
                <i class="bi bi-save me-2"></i>Save Changes
            </button>
        </div>
    </div>

    <!-- Password Policy Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Password Policy</h5>
                </div>
                <div class="card-body">
                    <form id="passwordPolicyForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Minimum Password Length</label>
                                <input type="number" class="form-control" name="min_length" 
                                       value="<?php echo $security_settings['password_policy']['min_length']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password Expiry (Days)</label>
                                <input type="number" class="form-control" name="password_expiry_days" 
                                       value="<?php echo $security_settings['password_policy']['password_expiry_days']; ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Password History Count</label>
                                <input type="number" class="form-control" name="password_history_count" 
                                       value="<?php echo $security_settings['password_policy']['password_history_count']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Requirements</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="require_uppercase" 
                                       <?php echo $security_settings['password_policy']['require_uppercase'] ? 'checked' : ''; ?>>
                                <label class="form-check-label">Require uppercase letters</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="require_lowercase" 
                                       <?php echo $security_settings['password_policy']['require_lowercase'] ? 'checked' : ''; ?>>
                                <label class="form-check-label">Require lowercase letters</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="require_numbers" 
                                       <?php echo $security_settings['password_policy']['require_numbers'] ? 'checked' : ''; ?>>
                                <label class="form-check-label">Require numbers</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="require_special_chars" 
                                       <?php echo $security_settings['password_policy']['require_special_chars'] ? 'checked' : ''; ?>>
                                <label class="form-check-label">Require special characters</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Settings Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Session Settings</h5>
                </div>
                <div class="card-body">
                    <form id="sessionSettingsForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Session Timeout (Minutes)</label>
                                <input type="number" class="form-control" name="session_timeout_minutes" 
                                       value="<?php echo $security_settings['session_settings']['session_timeout_minutes']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Max Concurrent Sessions</label>
                                <input type="number" class="form-control" name="max_concurrent_sessions" 
                                       value="<?php echo $security_settings['session_settings']['max_concurrent_sessions']; ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Remember Me Duration (Days)</label>
                                <input type="number" class="form-control" name="remember_me_days" 
                                       value="<?php echo $security_settings['session_settings']['remember_me_days']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="force_logout_on_timeout" 
                                           <?php echo $security_settings['session_settings']['force_logout_on_timeout'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Force logout on session timeout</label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Settings Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Login Settings</h5>
                </div>
                <div class="card-body">
                    <form id="loginSettingsForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Max Login Attempts</label>
                                <input type="number" class="form-control" name="max_login_attempts" 
                                       value="<?php echo $security_settings['login_settings']['max_login_attempts']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lockout Duration (Minutes)</label>
                                <input type="number" class="form-control" name="lockout_duration_minutes" 
                                       value="<?php echo $security_settings['login_settings']['lockout_duration_minutes']; ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="require_2fa" 
                                           <?php echo $security_settings['login_settings']['require_2fa'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Require 2FA for all users</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Allowed IP Addresses</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="allowed_ips" 
                                       value="<?php echo implode(', ', $security_settings['login_settings']['allowed_ips']); ?>">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-plus-circle"></i>
                                </button>
                            </div>
                            <small class="text-muted">Separate multiple IPs with commas</small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Settings Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Audit Settings</h5>
                </div>
                <div class="card-body">
                    <form id="auditSettingsForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Log Retention (Days)</label>
                                <input type="number" class="form-control" name="retention_days" 
                                       value="<?php echo $security_settings['audit_settings']['retention_days']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Audit Events</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="log_login_attempts" 
                                       <?php echo $security_settings['audit_settings']['log_login_attempts'] ? 'checked' : ''; ?>>
                                <label class="form-check-label">Log login attempts</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="log_password_changes" 
                                       <?php echo $security_settings['audit_settings']['log_password_changes'] ? 'checked' : ''; ?>>
                                <label class="form-check-label">Log password changes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="log_user_activity" 
                                       <?php echo $security_settings['audit_settings']['log_user_activity'] ? 'checked' : ''; ?>>
                                <label class="form-check-label">Log user activity</label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('saveSettings').addEventListener('click', function() {
    // Collect form data
    const forms = document.querySelectorAll('form');
    const formData = new FormData();
    
    forms.forEach(form => {
        const formEntries = new FormData(form);
        for (let [key, value] of formEntries.entries()) {
            formData.append(key, value);
        }
    });

    // Show success message
    alert('Security settings saved successfully!');
});
</script>

<?php require_once '../../includes/footer.php'; ?> 