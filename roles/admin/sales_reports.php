<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for sales reports
$sales_summary = [
    'total_sales' => 125000.00,
    'total_orders' => 450,
    'average_order_value' => 277.78,
    'total_customers' => 380,
    'new_customers' => 45,
    'returning_customers' => 335,
    'total_discounts' => 8500.00,
    'net_revenue' => 116500.00
];

$sales_trends = [
    [
        'date' => '2024-03-01',
        'sales' => 2500.00,
        'orders' => 12,
        'customers' => 10
    ],
    [
        'date' => '2024-03-02',
        'sales' => 3200.00,
        'orders' => 15,
        'customers' => 13
    ],
    [
        'date' => '2024-03-03',
        'sales' => 2800.00,
        'orders' => 14,
        'customers' => 12
    ],
    [
        'date' => '2024-03-04',
        'sales' => 3500.00,
        'orders' => 18,
        'customers' => 15
    ],
    [
        'date' => '2024-03-05',
        'sales' => 3100.00,
        'orders' => 16,
        'customers' => 14
    ],
    [
        'date' => '2024-03-06',
        'sales' => 3400.00,
        'orders' => 17,
        'customers' => 15
    ],
    [
        'date' => '2024-03-07',
        'sales' => 2900.00,
        'orders' => 14,
        'customers' => 12
    ]
];

$top_products = [
    [
        'product_id' => 'PRD001',
        'product_name' => 'Chocolate Cake',
        'quantity_sold' => 150,
        'revenue' => 37500.00,
        'growth' => 15.5
    ],
    [
        'product_id' => 'PRD002',
        'product_name' => 'Vanilla Cupcake',
        'quantity_sold' => 200,
        'revenue' => 20000.00,
        'growth' => 8.2
    ],
    [
        'product_id' => 'PRD003',
        'product_name' => 'Bread Loaf',
        'quantity_sold' => 180,
        'revenue' => 18000.00,
        'growth' => 12.3
    ],
    [
        'product_id' => 'PRD004',
        'product_name' => 'Croissant',
        'quantity_sold' => 120,
        'revenue' => 18000.00,
        'growth' => 5.7
    ],
    [
        'product_id' => 'PRD005',
        'product_name' => 'Muffin',
        'quantity_sold' => 160,
        'revenue' => 16000.00,
        'growth' => 10.1
    ]
];

$payment_methods = [
    'Cash' => 45000.00,
    'Credit Card' => 55000.00,
    'Mobile Payment' => 15000.00,
    'Online Payment' => 10000.00
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Sales Reports</h2>
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
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" value="2024-03-01">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" value="2024-03-07">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Sales</h6>
                    <h3 class="mb-0">₱<?php echo number_format($sales_summary['total_sales'], 2); ?></h3>
                    <small class="text-success">+12.5% from last period</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Orders</h6>
                    <h3 class="mb-0"><?php echo $sales_summary['total_orders']; ?></h3>
                    <small class="text-success">+8.3% from last period</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Average Order Value</h6>
                    <h3 class="mb-0">₱<?php echo number_format($sales_summary['average_order_value'], 2); ?></h3>
                    <small class="text-success">+3.9% from last period</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Net Revenue</h6>
                    <h3 class="mb-0">₱<?php echo number_format($sales_summary['net_revenue'], 2); ?></h3>
                    <small class="text-success">+11.2% from last period</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Trends Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Sales Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesTrendsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products and Payment Methods -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Products</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Quantity Sold</th>
                                    <th>Revenue</th>
                                    <th>Growth</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($top_products as $product): ?>
                                    <tr>
                                        <td><?php echo $product['product_id']; ?></td>
                                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                        <td><?php echo $product['quantity_sold']; ?></td>
                                        <td>₱<?php echo number_format($product['revenue'], 2); ?></td>
                                        <td>
                                            <span class="badge bg-success">+<?php echo $product['growth']; ?>%</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Payment Methods</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Sales Trends Chart
const salesTrendsCtx = document.getElementById('salesTrendsChart').getContext('2d');
new Chart(salesTrendsCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($sales_trends, 'date')); ?>,
        datasets: [{
            label: 'Sales',
            data: <?php echo json_encode(array_column($sales_trends, 'sales')); ?>,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Payment Methods Chart
const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
new Chart(paymentMethodsCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode(array_keys($payment_methods)); ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($payment_methods)); ?>,
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(75, 192, 192)'
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