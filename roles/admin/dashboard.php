<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for dashboard metrics
$dashboard_metrics = [
    'sales' => [
        'today' => 12500.00,
        'week' => 85000.00,
        'month' => 350000.00,
        'growth' => 15.5
    ],
    'inventory' => [
        'total_items' => 1250,
        'low_stock' => 45,
        'out_of_stock' => 12,
        'total_value' => 250000.00
    ],
    'customers' => [
        'total' => 850,
        'new_today' => 15,
        'active' => 720,
        'growth' => 8.2
    ],
    'orders' => [
        'today' => 45,
        'pending' => 12,
        'processing' => 18,
        'completed' => 15
    ]
];

// Mock data for recent activities
$recent_activities = [
    [
        'id' => 1,
        'type' => 'sale',
        'description' => 'New sale completed - Order #12345',
        'amount' => 250.00,
        'timestamp' => '2024-03-07 14:30:00'
    ],
    [
        'id' => 2,
        'type' => 'inventory',
        'description' => 'Stock received - 100 units of Product A',
        'amount' => 5000.00,
        'timestamp' => '2024-03-07 14:15:00'
    ],
    [
        'id' => 3,
        'type' => 'customer',
        'description' => 'New customer registration - John Doe',
        'amount' => 0,
        'timestamp' => '2024-03-07 14:00:00'
    ],
    [
        'id' => 4,
        'type' => 'order',
        'description' => 'Order #12344 marked as completed',
        'amount' => 350.00,
        'timestamp' => '2024-03-07 13:45:00'
    ],
    [
        'id' => 5,
        'type' => 'inventory',
        'description' => 'Low stock alert - Product B',
        'amount' => 0,
        'timestamp' => '2024-03-07 13:30:00'
    ]
];

// Mock data for sales trends
$sales_trends = [
    'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    'data' => [12000, 15000, 13500, 14500, 16000, 12500, 14000]
];

// Mock data for top products
$top_products = [
    [
        'id' => 1,
        'name' => 'Product A',
        'sales' => 250,
        'revenue' => 12500.00,
        'growth' => 15.5
    ],
    [
        'id' => 2,
        'name' => 'Product B',
        'sales' => 180,
        'revenue' => 9000.00,
        'growth' => 8.2
    ],
    [
        'id' => 3,
        'name' => 'Product C',
        'sales' => 150,
        'revenue' => 7500.00,
        'growth' => 12.3
    ],
    [
        'id' => 4,
        'name' => 'Product D',
        'sales' => 120,
        'revenue' => 6000.00,
        'growth' => 5.8
    ],
    [
        'id' => 5,
        'name' => 'Product E',
        'sales' => 100,
        'revenue' => 5000.00,
        'growth' => 3.2
    ]
];
?>

<div class="container-fluid py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Admin Dashboard</h2>
            <p class="text-muted">Here's what's happening with your store today.</p>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="refreshDashboard()">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Key Metrics Cards -->
    <div class="row mb-4">
        <!-- Sales Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Today's Sales</h6>
                            <h3 class="mb-0">$<?php echo number_format($dashboard_metrics['sales']['today'], 2); ?></h3>
                            <small class="text-success">
                                <i class="bi bi-arrow-up"></i> <?php echo $dashboard_metrics['sales']['growth']; ?>% vs last week
                            </small>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cart-check text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Inventory Status</h6>
                            <h3 class="mb-0"><?php echo $dashboard_metrics['inventory']['total_items']; ?></h3>
                            <small class="text-danger">
                                <i class="bi bi-exclamation-triangle"></i> <?php echo $dashboard_metrics['inventory']['low_stock']; ?> low stock items
                            </small>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-box-seam text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Customers</h6>
                            <h3 class="mb-0"><?php echo $dashboard_metrics['customers']['total']; ?></h3>
                            <small class="text-success">
                                <i class="bi bi-person-plus"></i> <?php echo $dashboard_metrics['customers']['new_today']; ?> new today
                            </small>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Today's Orders</h6>
                            <h3 class="mb-0"><?php echo $dashboard_metrics['orders']['today']; ?></h3>
                            <small class="text-warning">
                                <i class="bi bi-clock"></i> <?php echo $dashboard_metrics['orders']['pending']; ?> pending
                            </small>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-bag text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Sales Trends Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sales Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesTrendsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Products</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Sales</th>
                                    <th>Revenue</th>
                                    <th>Growth</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($top_products as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                    <td><?php echo $product['sales']; ?></td>
                                    <td>$<?php echo number_format($product['revenue'], 2); ?></td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="bi bi-arrow-up"></i> <?php echo $product['growth']; ?>%
                                        </span>
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

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Activities</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Activity</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_activities as $activity): ?>
                                <tr>
                                    <td><?php echo date('H:i', strtotime($activity['timestamp'])); ?></td>
                                    <td><?php echo htmlspecialchars($activity['description']); ?></td>
                                    <td>
                                        <?php if ($activity['amount'] > 0): ?>
                                            $<?php echo number_format($activity['amount'], 2); ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewActivity(<?php echo $activity['id']; ?>)">
                                            View
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
// Sales Trends Chart
const ctx = document.getElementById('salesTrendsChart').getContext('2d');
const salesTrendsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($sales_trends['labels']); ?>,
        datasets: [{
            label: 'Daily Sales',
            data: <?php echo json_encode($sales_trends['data']); ?>,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1,
            fill: true,
            backgroundColor: 'rgba(75, 192, 192, 0.1)'
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

function refreshDashboard() {
    alert('Refreshing dashboard data...');
    // Implement refresh functionality
}

function viewActivity(id) {
    alert('Viewing activity details for ID: ' + id);
    // Implement view activity functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 