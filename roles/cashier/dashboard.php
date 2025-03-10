<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for dashboard metrics
$dashboard_metrics = [
    'sales' => [
        'today' => 12500.00,
        'transactions' => 45,
        'average_ticket' => 277.78,
        'growth' => 15.5
    ],
    'payments' => [
        'cash' => 7500.00,
        'card' => 3500.00,
        'mobile' => 1500.00,
        'pending' => 2500.00
    ],
    'orders' => [
        'pending' => 12,
        'processing' => 8,
        'completed' => 25,
        'cancelled' => 3
    ],
    'customers' => [
        'walk_in' => 35,
        'registered' => 10,
        'new_today' => 5,
        'total' => 45
    ]
];

// Mock data for recent transactions
$recent_transactions = [
    [
        'id' => 'TRX001',
        'customer' => 'John Doe',
        'type' => 'Walk-in',
        'amount' => 250.00,
        'payment_method' => 'Cash',
        'status' => 'Completed',
        'timestamp' => '2024-03-07 14:30:00'
    ],
    [
        'id' => 'TRX002',
        'customer' => 'Jane Smith',
        'type' => 'Registered',
        'amount' => 350.00,
        'payment_method' => 'Card',
        'status' => 'Completed',
        'timestamp' => '2024-03-07 14:25:00'
    ],
    [
        'id' => 'TRX003',
        'customer' => 'Mike Johnson',
        'type' => 'Walk-in',
        'amount' => 150.00,
        'payment_method' => 'Mobile',
        'status' => 'Pending',
        'timestamp' => '2024-03-07 14:20:00'
    ],
    [
        'id' => 'TRX004',
        'customer' => 'Sarah Wilson',
        'type' => 'Registered',
        'amount' => 450.00,
        'payment_method' => 'Card',
        'status' => 'Completed',
        'timestamp' => '2024-03-07 14:15:00'
    ],
    [
        'id' => 'TRX005',
        'customer' => 'Tom Brown',
        'type' => 'Walk-in',
        'amount' => 200.00,
        'payment_method' => 'Cash',
        'status' => 'Completed',
        'timestamp' => '2024-03-07 14:10:00'
    ]
];

// Mock data for hourly sales
$hourly_sales = [
    'labels' => ['9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM'],
    'data' => [1200, 1500, 1800, 2200, 2000, 1800, 1600, 1400]
];
?>

<div class="container-fluid py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col">
        <h2 class="mb-4">Cashier Dashboard</h2>
            <p class="text-muted">Here's your sales overview for today.</p>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="refreshDashboard()">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-3">
            <a href="new_sale.php" class="card text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-plus-circle text-primary fs-1"></i>
                    <h5 class="mt-2">New Sale</h5>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="process_payment.php" class="card text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-cash text-success fs-1"></i>
                    <h5 class="mt-2">Process Payment</h5>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="qr_registration.php" class="card text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-qr-code text-info fs-1"></i>
                    <h5 class="mt-2">QR Registration</h5>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="refunds.php" class="card text-decoration-none">
                <div class="card-body text-center">
                    <i class="bi bi-arrow-counterclockwise text-warning fs-1"></i>
                    <h5 class="mt-2">Refunds</h5>
                </div>
            </a>
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
                                <i class="bi bi-arrow-up"></i> <?php echo $dashboard_metrics['sales']['growth']; ?>% vs yesterday
                            </small>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-cart-check text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Transactions</h6>
                            <h3 class="mb-0"><?php echo $dashboard_metrics['sales']['transactions']; ?></h3>
                            <small class="text-muted">
                                Avg: $<?php echo number_format($dashboard_metrics['sales']['average_ticket'], 2); ?>
                            </small>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-receipt text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Orders Card -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Pending Orders</h6>
                            <h3 class="mb-0"><?php echo $dashboard_metrics['orders']['pending']; ?></h3>
                            <small class="text-warning">
                                <i class="bi bi-clock"></i> <?php echo $dashboard_metrics['orders']['processing']; ?> processing
                            </small>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-hourglass-split text-warning fs-4"></i>
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
                            <h6 class="card-title text-muted mb-2">Today's Customers</h6>
                            <h3 class="mb-0"><?php echo $dashboard_metrics['customers']['total']; ?></h3>
                            <small class="text-info">
                                <i class="bi bi-person-plus"></i> <?php echo $dashboard_metrics['customers']['new_today']; ?> new
                            </small>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Hourly Sales Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Hourly Sales</h5>
                </div>
                <div class="card-body">
                    <canvas id="hourlySalesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Methods</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Cash</span>
                        <span>$<?php echo number_format($dashboard_metrics['payments']['cash'], 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Card</span>
                        <span>$<?php echo number_format($dashboard_metrics['payments']['card'], 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Mobile</span>
                        <span>$<?php echo number_format($dashboard_metrics['payments']['mobile'], 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Pending</span>
                        <span>$<?php echo number_format($dashboard_metrics['payments']['pending'], 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Transactions</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['customer']); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['type']); ?></td>
                                    <td>$<?php echo number_format($transaction['amount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($transaction['payment_method']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $transaction['status'] === 'Completed' ? 'success' : 'warning'; ?>">
                                            <?php echo htmlspecialchars($transaction['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('H:i', strtotime($transaction['timestamp'])); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewTransaction('<?php echo $transaction['id']; ?>')">
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
// Hourly Sales Chart
const ctx = document.getElementById('hourlySalesChart').getContext('2d');
const hourlySalesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($hourly_sales['labels']); ?>,
        datasets: [{
            label: 'Hourly Sales',
            data: <?php echo json_encode($hourly_sales['data']); ?>,
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

function viewTransaction(id) {
    alert('Viewing transaction details for ID: ' + id);
    // Implement view transaction functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 