<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for system logs
$log_summary = [
    'total_logs' => 1250,
    'error_logs' => 45,
    'warning_logs' => 120,
    'info_logs' => 1085,
    'critical_logs' => 5,
    'login_attempts' => 380,
    'failed_logins' => 25,
    'system_errors' => 15
];

$recent_logs = [
    [
        'timestamp' => '2024-03-07 14:30:25',
        'level' => 'INFO',
        'category' => 'User Activity',
        'message' => 'User John Doe logged in successfully',
        'ip_address' => '192.168.1.100',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'timestamp' => '2024-03-07 14:28:15',
        'level' => 'WARNING',
        'category' => 'Inventory',
        'message' => 'Low stock alert: Sugar (30 kg remaining)',
        'ip_address' => '192.168.1.101',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'timestamp' => '2024-03-07 14:25:45',
        'level' => 'ERROR',
        'category' => 'Database',
        'message' => 'Failed to connect to database: Connection timeout',
        'ip_address' => '192.168.1.102',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'timestamp' => '2024-03-07 14:20:30',
        'level' => 'INFO',
        'category' => 'Sales',
        'message' => 'New order #ORD1234 created',
        'ip_address' => '192.168.1.103',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'timestamp' => '2024-03-07 14:15:20',
        'level' => 'CRITICAL',
        'category' => 'Security',
        'message' => 'Multiple failed login attempts detected from IP: 192.168.1.104',
        'ip_address' => '192.168.1.104',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'timestamp' => '2024-03-07 14:10:15',
        'level' => 'INFO',
        'category' => 'Inventory',
        'message' => 'Stock level updated: Flour (500 kg)',
        'ip_address' => '192.168.1.105',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'timestamp' => '2024-03-07 14:05:30',
        'level' => 'WARNING',
        'category' => 'System',
        'message' => 'High CPU usage detected: 85%',
        'ip_address' => '192.168.1.106',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    [
        'timestamp' => '2024-03-07 14:00:45',
        'level' => 'INFO',
        'category' => 'User Activity',
        'message' => 'User Jane Smith updated product prices',
        'ip_address' => '192.168.1.107',
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ]
];

$log_categories = [
    'User Activity' => 450,
    'Inventory' => 300,
    'Sales' => 250,
    'System' => 150,
    'Security' => 100
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">System Logs</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="exportToExcel()">
                <i class="bi bi-file-earmark-excel me-2"></i>Export to Excel
            </button>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Date Range</label>
                            <select class="form-select">
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="last7days" selected>Last 7 Days</option>
                                <option value="last30days">Last 30 Days</option>
                                <option value="thisMonth">This Month</option>
                                <option value="lastMonth">Last Month</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Log Level</label>
                            <select class="form-select">
                                <option value="all">All Levels</option>
                                <option value="critical">Critical</option>
                                <option value="error">Error</option>
                                <option value="warning">Warning</option>
                                <option value="info">Info</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select class="form-select">
                                <option value="all">All Categories</option>
                                <option value="user">User Activity</option>
                                <option value="inventory">Inventory</option>
                                <option value="sales">Sales</option>
                                <option value="system">System</option>
                                <option value="security">Security</option>
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

    <!-- Log Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Logs</h6>
                    <h3 class="mb-0"><?php echo $log_summary['total_logs']; ?></h3>
                    <small class="text-success">Last 24 hours</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Error Logs</h6>
                    <h3 class="mb-0"><?php echo $log_summary['error_logs']; ?></h3>
                    <small class="text-danger">+5 from yesterday</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Critical Logs</h6>
                    <h3 class="mb-0"><?php echo $log_summary['critical_logs']; ?></h3>
                    <small class="text-danger">+2 from yesterday</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Failed Logins</h6>
                    <h3 class="mb-0"><?php echo $log_summary['failed_logins']; ?></h3>
                    <small class="text-danger">+3 from yesterday</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Categories Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Log Categories Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="logCategoriesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Logs</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Level</th>
                                    <th>Category</th>
                                    <th>Message</th>
                                    <th>IP Address</th>
                                    <th>User Agent</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_logs as $log): ?>
                                    <tr>
                                        <td><?php echo $log['timestamp']; ?></td>
                                        <td>
                                            <?php
                                            $badge_class = match($log['level']) {
                                                'CRITICAL' => 'bg-danger',
                                                'ERROR' => 'bg-danger',
                                                'WARNING' => 'bg-warning',
                                                default => 'bg-info'
                                            };
                                            ?>
                                            <span class="badge <?php echo $badge_class; ?>">
                                                <?php echo $log['level']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($log['category']); ?></td>
                                        <td><?php echo htmlspecialchars($log['message']); ?></td>
                                        <td><?php echo $log['ip_address']; ?></td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                                <?php echo htmlspecialchars($log['user_agent']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete Log">
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Log Categories Chart
const logCategoriesCtx = document.getElementById('logCategoriesChart').getContext('2d');
new Chart(logCategoriesCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_keys($log_categories)); ?>,
        datasets: [{
            label: 'Number of Logs',
            data: <?php echo json_encode(array_values($log_categories)); ?>,
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

function exportToExcel() {
    alert('Exporting to Excel...');
    // Implement Excel export functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 