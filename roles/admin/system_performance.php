<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for system performance
$system_metrics = [
    'server' => [
        'cpu_usage' => 45.5,
        'memory_usage' => 65.2,
        'disk_usage' => 75.8,
        'uptime' => '15 days, 6 hours',
        'load_average' => [1.2, 1.5, 1.8],
        'php_version' => '8.1.15',
        'server_software' => 'Apache/2.4.54'
    ],
    'database' => [
        'connections' => 25,
        'queries_per_second' => 150,
        'slow_queries' => 3,
        'cache_hit_rate' => 85.5,
        'active_transactions' => 8,
        'database_size' => '2.5 GB',
        'last_backup' => '2024-03-07 03:00:00'
    ],
    'application' => [
        'active_users' => 45,
        'requests_per_minute' => 120,
        'average_response_time' => 0.25,
        'error_rate' => 0.5,
        'cache_effectiveness' => 92.5,
        'session_count' => 38,
        'last_restart' => '2024-03-01 02:00:00'
    ],
    'security' => [
        'failed_login_attempts' => 12,
        'blocked_ips' => 5,
        'ssl_certificate_status' => 'Valid',
        'firewall_status' => 'Active',
        'last_security_scan' => '2024-03-07 04:00:00',
        'security_alerts' => 2
    ]
];

$performance_history = [
    'cpu' => [45, 42, 48, 50, 45, 43, 45.5],
    'memory' => [62, 58, 65, 68, 63, 64, 65.2],
    'disk' => [73, 74, 75, 75.5, 75.7, 75.6, 75.8],
    'response_time' => [0.22, 0.24, 0.23, 0.26, 0.25, 0.24, 0.25]
];

$status_badges = [
    'Good' => 'bg-success',
    'Warning' => 'bg-warning',
    'Critical' => 'bg-danger',
    'Info' => 'bg-info'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">System Performance</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="refreshMetrics()">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Server Metrics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Server Resources</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">CPU Usage</label>
                                <div class="progress">
                                    <div class="progress-bar <?php echo $system_metrics['server']['cpu_usage'] > 80 ? 'bg-danger' : ($system_metrics['server']['cpu_usage'] > 60 ? 'bg-warning' : 'bg-success'); ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $system_metrics['server']['cpu_usage']; ?>%">
                                        <?php echo $system_metrics['server']['cpu_usage']; ?>%
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Memory Usage</label>
                                <div class="progress">
                                    <div class="progress-bar <?php echo $system_metrics['server']['memory_usage'] > 80 ? 'bg-danger' : ($system_metrics['server']['memory_usage'] > 60 ? 'bg-warning' : 'bg-success'); ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $system_metrics['server']['memory_usage']; ?>%">
                                        <?php echo $system_metrics['server']['memory_usage']; ?>%
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Disk Usage</label>
                                <div class="progress">
                                    <div class="progress-bar <?php echo $system_metrics['server']['disk_usage'] > 80 ? 'bg-danger' : ($system_metrics['server']['disk_usage'] > 60 ? 'bg-warning' : 'bg-success'); ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $system_metrics['server']['disk_usage']; ?>%">
                                        <?php echo $system_metrics['server']['disk_usage']; ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Server Uptime</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['server']['uptime']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Load Average</label>
                                <input type="text" class="form-control" value="<?php echo implode(', ', $system_metrics['server']['load_average']); ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">PHP Version</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['server']['php_version']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Server Software</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['server']['server_software']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Metrics -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Database Performance</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Active Connections</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['database']['connections']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Queries per Second</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['database']['queries_per_second']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Slow Queries</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['database']['slow_queries']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cache Hit Rate</label>
                                <div class="progress">
                                    <div class="progress-bar <?php echo $system_metrics['database']['cache_hit_rate'] > 80 ? 'bg-success' : ($system_metrics['database']['cache_hit_rate'] > 60 ? 'bg-warning' : 'bg-danger'); ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $system_metrics['database']['cache_hit_rate']; ?>%">
                                        <?php echo $system_metrics['database']['cache_hit_rate']; ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Active Transactions</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['database']['active_transactions']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Database Size</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['database']['database_size']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Backup</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['database']['last_backup']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Metrics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Application Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Active Users</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['application']['active_users']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Requests per Minute</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['application']['requests_per_minute']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Average Response Time</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['application']['average_response_time']; ?> seconds" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Error Rate</label>
                                <div class="progress">
                                    <div class="progress-bar <?php echo $system_metrics['application']['error_rate'] > 5 ? 'bg-danger' : ($system_metrics['application']['error_rate'] > 2 ? 'bg-warning' : 'bg-success'); ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $system_metrics['application']['error_rate'] * 10; ?>%">
                                        <?php echo $system_metrics['application']['error_rate']; ?>%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cache Effectiveness</label>
                                <div class="progress">
                                    <div class="progress-bar <?php echo $system_metrics['application']['cache_effectiveness'] > 80 ? 'bg-success' : ($system_metrics['application']['cache_effectiveness'] > 60 ? 'bg-warning' : 'bg-danger'); ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $system_metrics['application']['cache_effectiveness']; ?>%">
                                        <?php echo $system_metrics['application']['cache_effectiveness']; ?>%
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Session Count</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['application']['session_count']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Restart</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['application']['last_restart']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Metrics -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Security Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Failed Login Attempts</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['security']['failed_login_attempts']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Blocked IPs</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['security']['blocked_ips']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">SSL Certificate Status</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['security']['ssl_certificate_status']; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Firewall Status</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['security']['firewall_status']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Last Security Scan</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['security']['last_security_scan']; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Security Alerts</label>
                                <input type="text" class="form-control" value="<?php echo $system_metrics['security']['security_alerts']; ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Performance History</h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Performance Chart
const ctx = document.getElementById('performanceChart').getContext('2d');
const performanceChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['6h ago', '5h ago', '4h ago', '3h ago', '2h ago', '1h ago', 'Now'],
        datasets: [
            {
                label: 'CPU Usage (%)',
                data: <?php echo json_encode($performance_history['cpu']); ?>,
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            },
            {
                label: 'Memory Usage (%)',
                data: <?php echo json_encode($performance_history['memory']); ?>,
                borderColor: 'rgb(54, 162, 235)',
                tension: 0.1
            },
            {
                label: 'Disk Usage (%)',
                data: <?php echo json_encode($performance_history['disk']); ?>,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            },
            {
                label: 'Response Time (s)',
                data: <?php echo json_encode($performance_history['response_time']); ?>,
                borderColor: 'rgb(153, 102, 255)',
                tension: 0.1
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function refreshMetrics() {
    alert('Refreshing system metrics...');
    // Implement refresh functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 