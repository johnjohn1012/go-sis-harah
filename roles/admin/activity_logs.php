<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for activity logs
$activity_logs = [
    [
        'log_id' => 'LOG001',
        'timestamp' => '2024-03-07 14:30:25',
        'user_id' => 'USR001',
        'username' => 'admin',
        'action' => 'Login',
        'ip_address' => '192.168.1.100',
        'status' => 'Success',
        'details' => 'User logged in successfully'
    ],
    [
        'log_id' => 'LOG002',
        'timestamp' => '2024-03-07 14:35:12',
        'user_id' => 'USR001',
        'username' => 'admin',
        'action' => 'Add Product',
        'ip_address' => '192.168.1.100',
        'status' => 'Success',
        'details' => 'Added new product: Chocolate Cake (PROD123)'
    ],
    [
        'log_id' => 'LOG003',
        'timestamp' => '2024-03-07 14:40:45',
        'user_id' => 'USR002',
        'username' => 'cashier1',
        'action' => 'Process Sale',
        'ip_address' => '192.168.1.101',
        'status' => 'Success',
        'details' => 'Processed sale transaction: SALE456'
    ],
    [
        'log_id' => 'LOG004',
        'timestamp' => '2024-03-07 14:45:30',
        'user_id' => 'USR003',
        'username' => 'stockclerk1',
        'action' => 'Update Stock',
        'ip_address' => '192.168.1.102',
        'status' => 'Success',
        'details' => 'Updated stock for: Flour (MAT001) - Added 50kg'
    ],
    [
        'log_id' => 'LOG005',
        'timestamp' => '2024-03-07 14:50:15',
        'user_id' => 'USR004',
        'username' => 'manager1',
        'action' => 'Generate Report',
        'ip_address' => '192.168.1.103',
        'status' => 'Success',
        'details' => 'Generated daily sales report'
    ],
    [
        'log_id' => 'LOG006',
        'timestamp' => '2024-03-07 14:55:00',
        'user_id' => 'USR005',
        'username' => 'cashier2',
        'action' => 'Login',
        'ip_address' => '192.168.1.104',
        'status' => 'Failed',
        'details' => 'Invalid password attempt'
    ],
    [
        'log_id' => 'LOG007',
        'timestamp' => '2024-03-07 15:00:30',
        'user_id' => 'USR001',
        'username' => 'admin',
        'action' => 'Update Settings',
        'ip_address' => '192.168.1.100',
        'status' => 'Success',
        'details' => 'Updated system security settings'
    ],
    [
        'log_id' => 'LOG008',
        'timestamp' => '2024-03-07 15:05:45',
        'user_id' => 'USR002',
        'username' => 'cashier1',
        'action' => 'Void Transaction',
        'ip_address' => '192.168.1.101',
        'status' => 'Success',
        'details' => 'Voided transaction: SALE457'
    ],
    [
        'log_id' => 'LOG009',
        'timestamp' => '2024-03-07 15:10:20',
        'user_id' => 'USR003',
        'username' => 'stockclerk1',
        'action' => 'Add Supplier',
        'ip_address' => '192.168.1.102',
        'status' => 'Success',
        'details' => 'Added new supplier: Fresh Ingredients Ltd.'
    ],
    [
        'log_id' => 'LOG010',
        'timestamp' => '2024-03-07 15:15:00',
        'user_id' => 'USR001',
        'username' => 'admin',
        'action' => 'Logout',
        'ip_address' => '192.168.1.100',
        'status' => 'Success',
        'details' => 'User logged out successfully'
    ]
];

$status_badges = [
    'Success' => 'bg-success',
    'Failed' => 'bg-danger',
    'Warning' => 'bg-warning',
    'Info' => 'bg-info'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Activity Logs</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="exportLogs()">
                <i class="bi bi-download me-2"></i>Export Logs
            </button>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Date Range</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Action Type</label>
                            <select class="form-select">
                                <option value="all">All Actions</option>
                                <option value="login">Login/Logout</option>
                                <option value="sale">Sales</option>
                                <option value="stock">Stock</option>
                                <option value="settings">Settings</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option value="all">All Status</option>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                                <option value="warning">Warning</option>
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

    <!-- Activity Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Log ID</th>
                                    <th>Timestamp</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>IP Address</th>
                                    <th>Status</th>
                                    <th>Details</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activity_logs as $log): ?>
                                    <tr>
                                        <td><?php echo $log['log_id']; ?></td>
                                        <td><?php echo $log['timestamp']; ?></td>
                                        <td>
                                            <div><?php echo htmlspecialchars($log['username']); ?></div>
                                            <small class="text-muted"><?php echo $log['user_id']; ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($log['action']); ?></td>
                                        <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $status_badges[$log['status']]; ?>">
                                                <?php echo $log['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                                <?php echo htmlspecialchars($log['details']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details" onclick="viewLogDetails('<?php echo $log['log_id']; ?>')">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete Log" onclick="deleteLog('<?php echo $log['log_id']; ?>')">
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

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Log ID</label>
                    <input type="text" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Timestamp</label>
                    <input type="text" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">User</label>
                    <input type="text" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Action</label>
                    <input type="text" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">IP Address</label>
                    <input type="text" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <input type="text" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Details</label>
                    <textarea class="form-control" rows="3" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewLogDetails(logId) {
    alert('Viewing details for log: ' + logId);
    // Implement view details functionality
    $('#logDetailsModal').modal('show');
}

function deleteLog(logId) {
    if (confirm('Are you sure you want to delete this log?')) {
        alert('Deleting log: ' + logId);
        // Implement delete functionality
    }
}

function exportLogs() {
    alert('Exporting logs...');
    // Implement export functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 