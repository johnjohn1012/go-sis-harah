<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for daily sales
$daily_sales = [
    'date' => '2024-03-07',
    'total_sales' => 1250.00,
    'total_transactions' => 25,
    'average_order' => 50.00,
    'cash_sales' => 750.00,
    'card_sales' => 400.00,
    'mobile_sales' => 100.00,
    'refunds' => 75.00,
    'net_sales' => 1175.00
];

// Mock data for hourly sales
$hourly_sales = [
    ['hour' => '09:00', 'sales' => 150.00, 'transactions' => 3],
    ['hour' => '10:00', 'sales' => 225.00, 'transactions' => 5],
    ['hour' => '11:00', 'sales' => 300.00, 'transactions' => 6],
    ['hour' => '12:00', 'sales' => 275.00, 'transactions' => 5],
    ['hour' => '13:00', 'sales' => 200.00, 'transactions' => 4],
    ['hour' => '14:00', 'sales' => 100.00, 'transactions' => 2]
];

// Mock data for top products
$top_products = [
    ['name' => 'Product A', 'quantity' => 15, 'revenue' => 750.00],
    ['name' => 'Product B', 'quantity' => 12, 'revenue' => 600.00],
    ['name' => 'Product C', 'quantity' => 10, 'revenue' => 500.00],
    ['name' => 'Product D', 'quantity' => 8, 'revenue' => 400.00],
    ['name' => 'Product E', 'quantity' => 5, 'revenue' => 250.00]
];
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2>Daily Sales Report</h2>
            <p class="text-muted">Sales statistics for <?php echo date('F d, Y', strtotime($daily_sales['date'])); ?></p>
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
                    <h6 class="card-title">Total Sales</h6>
                    <h3 class="mb-0">$<?php echo number_format($daily_sales['total_sales'], 2); ?></h3>
                    <small><?php echo $daily_sales['total_transactions']; ?> transactions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Net Sales</h6>
                    <h3 class="mb-0">$<?php echo number_format($daily_sales['net_sales'], 2); ?></h3>
                    <small>After refunds</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Average Order</h6>
                    <h3 class="mb-0">$<?php echo number_format($daily_sales['average_order'], 2); ?></h3>
                    <small>Per transaction</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Refunds</h6>
                    <h3 class="mb-0">$<?php echo number_format($daily_sales['refunds'], 2); ?></h3>
                    <small>Total refunds</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sales by Payment Method -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sales by Payment Method</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentMethodChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Hourly Sales Trend -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Hourly Sales Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="hourlySalesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Products</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($top_products as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                                        <td><?php echo $product['quantity']; ?></td>
                                        <td>$<?php echo number_format($product['revenue'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hourly Breakdown -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Hourly Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Hour</th>
                                    <th>Sales</th>
                                    <th>Transactions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($hourly_sales as $hour): ?>
                                    <tr>
                                        <td><?php echo $hour['hour']; ?></td>
                                        <td>$<?php echo number_format($hour['sales'], 2); ?></td>
                                        <td><?php echo $hour['transactions']; ?></td>
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
    // Payment Method Chart
    const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
    new Chart(paymentMethodCtx, {
        type: 'doughnut',
        data: {
            labels: ['Cash', 'Card', 'Mobile'],
            datasets: [{
                data: [
                    <?php echo $daily_sales['cash_sales']; ?>,
                    <?php echo $daily_sales['card_sales']; ?>,
                    <?php echo $daily_sales['mobile_sales']; ?>
                ],
                backgroundColor: ['#28a745', '#17a2b8', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Hourly Sales Chart
    const hourlySalesCtx = document.getElementById('hourlySalesChart').getContext('2d');
    new Chart(hourlySalesCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($hourly_sales, 'hour')); ?>,
            datasets: [{
                label: 'Sales',
                data: <?php echo json_encode(array_column($hourly_sales, 'sales')); ?>,
                borderColor: '#007bff',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
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

function exportReport() {
    alert('Exporting daily sales report...');
    // Implement report export functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 