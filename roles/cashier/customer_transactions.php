<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for customer statistics
$customer_stats = [
    'total_customers' => 150,
    'active_customers' => 120,
    'new_customers' => 25,
    'total_revenue' => 25000.00,
    'average_order' => 125.00,
    'repeat_customers' => 85,
    'customer_satisfaction' => 4.5
];

// Mock data for customer segments
$customer_segments = [
    'regular' => ['count' => 85, 'revenue' => 15000.00, 'avg_order' => 150.00],
    'occasional' => ['count' => 35, 'revenue' => 5000.00, 'avg_order' => 100.00],
    'new' => ['count' => 25, 'revenue' => 3000.00, 'avg_order' => 75.00],
    'inactive' => ['count' => 5, 'revenue' => 2000.00, 'avg_order' => 125.00]
];

// Mock data for customer trends
$customer_trends = [
    ['date' => '2024-02-01', 'new' => 5, 'active' => 100, 'revenue' => 1500.00],
    ['date' => '2024-02-08', 'new' => 8, 'active' => 105, 'revenue' => 1800.00],
    ['date' => '2024-02-15', 'new' => 12, 'active' => 110, 'revenue' => 2000.00],
    ['date' => '2024-02-22', 'new' => 15, 'active' => 115, 'revenue' => 2200.00],
    ['date' => '2024-03-01', 'new' => 20, 'active' => 120, 'revenue' => 2500.00],
    ['date' => '2024-03-07', 'new' => 25, 'active' => 120, 'revenue' => 2800.00]
];

// Mock data for top customers
$top_customers = [
    [
        'customer_id' => 'CUST001',
        'name' => 'John Doe',
        'total_orders' => 25,
        'total_spent' => 2500.00,
        'last_visit' => '2024-03-07',
        'avg_order' => 100.00
    ],
    [
        'customer_id' => 'CUST002',
        'name' => 'Jane Smith',
        'total_orders' => 20,
        'total_spent' => 2000.00,
        'last_visit' => '2024-03-06',
        'avg_order' => 100.00
    ],
    [
        'customer_id' => 'CUST003',
        'name' => 'Bob Wilson',
        'total_orders' => 15,
        'total_spent' => 1500.00,
        'last_visit' => '2024-03-05',
        'avg_order' => 100.00
    ]
];
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2>Customer Transactions Report</h2>
            <p class="text-muted">Customer behavior and transaction patterns</p>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="exportReport()">
                <i class="bi bi-download me-2"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Customers</h6>
                    <h3 class="mb-0"><?php echo $customer_stats['total_customers']; ?></h3>
                    <small><?php echo $customer_stats['active_customers']; ?> active</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Revenue</h6>
                    <h3 class="mb-0">$<?php echo number_format($customer_stats['total_revenue'], 2); ?></h3>
                    <small>From all customers</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">New Customers</h6>
                    <h3 class="mb-0"><?php echo $customer_stats['new_customers']; ?></h3>
                    <small>This month</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Customer Satisfaction</h6>
                    <h3 class="mb-0"><?php echo number_format($customer_stats['customer_satisfaction'], 1); ?></h3>
                    <small>Out of 5.0</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Segments -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Segments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Segment</th>
                                    <th>Customers</th>
                                    <th>Revenue</th>
                                    <th>Avg. Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customer_segments as $segment => $data): ?>
                                    <tr>
                                        <td><?php echo ucfirst($segment); ?></td>
                                        <td><?php echo $data['count']; ?></td>
                                        <td>$<?php echo number_format($data['revenue'], 2); ?></td>
                                        <td>$<?php echo number_format($data['avg_order'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Trends Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Customer Growth Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="customerTrendsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Customers -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Customers</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Customer ID</th>
                                    <th>Name</th>
                                    <th>Total Orders</th>
                                    <th>Total Spent</th>
                                    <th>Average Order</th>
                                    <th>Last Visit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($top_customers as $customer): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($customer['customer_id']); ?></td>
                                        <td><?php echo htmlspecialchars($customer['name']); ?></td>
                                        <td><?php echo $customer['total_orders']; ?></td>
                                        <td>$<?php echo number_format($customer['total_spent'], 2); ?></td>
                                        <td>$<?php echo number_format($customer['avg_order'], 2); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($customer['last_visit'])); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewCustomerHistory('<?php echo $customer['customer_id']; ?>')">
                                                <i class="bi bi-eye"></i>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Customer Trends Chart
    const customerTrendsCtx = document.getElementById('customerTrendsChart').getContext('2d');
    new Chart(customerTrendsCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($customer_trends, 'date')); ?>,
            datasets: [
                {
                    label: 'Active Customers',
                    data: <?php echo json_encode(array_column($customer_trends, 'active')); ?>,
                    borderColor: '#28a745',
                    tension: 0.1
                },
                {
                    label: 'New Customers',
                    data: <?php echo json_encode(array_column($customer_trends, 'new')); ?>,
                    borderColor: '#17a2b8',
                    tension: 0.1
                },
                {
                    label: 'Revenue',
                    data: <?php echo json_encode(array_column($customer_trends, 'revenue')); ?>,
                    borderColor: '#ffc107',
                    tension: 0.1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Customers'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Revenue ($)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });
});

function viewCustomerHistory(customerId) {
    window.location.href = `customer_history.php?id=${customerId}`;
}

function exportReport() {
    alert('Exporting customer transactions report...');
    // Implement report export functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 