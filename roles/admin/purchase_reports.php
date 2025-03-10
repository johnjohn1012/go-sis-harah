<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for purchase reports
$purchase_summary = [
    'total_purchases' => 180000.00,
    'total_orders' => 45,
    'average_order_value' => 4000.00,
    'total_suppliers' => 12,
    'active_suppliers' => 8,
    'pending_orders' => 5,
    'total_discounts' => 12000.00,
    'net_cost' => 168000.00
];

$purchase_trends = [
    [
        'date' => '2024-03-01',
        'amount' => 5000.00,
        'orders' => 3,
        'items' => 15
    ],
    [
        'date' => '2024-03-02',
        'amount' => 6500.00,
        'orders' => 4,
        'items' => 20
    ],
    [
        'date' => '2024-03-03',
        'amount' => 4500.00,
        'orders' => 2,
        'items' => 12
    ],
    [
        'date' => '2024-03-04',
        'amount' => 7500.00,
        'orders' => 5,
        'items' => 25
    ],
    [
        'date' => '2024-03-05',
        'amount' => 5500.00,
        'orders' => 3,
        'items' => 18
    ],
    [
        'date' => '2024-03-06',
        'amount' => 8000.00,
        'orders' => 6,
        'items' => 30
    ],
    [
        'date' => '2024-03-07',
        'amount' => 6000.00,
        'orders' => 4,
        'items' => 22
    ]
];

$top_suppliers = [
    [
        'supplier_id' => 'SUP001',
        'supplier_name' => 'Bakery Supplies Co.',
        'total_purchases' => 75000.00,
        'orders_count' => 15,
        'average_delivery_time' => '2.5 days',
        'payment_terms' => 'Net 30'
    ],
    [
        'supplier_id' => 'SUP002',
        'supplier_name' => 'Fresh Ingredients Ltd.',
        'total_purchases' => 45000.00,
        'orders_count' => 12,
        'average_delivery_time' => '1.5 days',
        'payment_terms' => 'Net 15'
    ],
    [
        'supplier_id' => 'SUP003',
        'supplier_name' => 'Packaging Solutions',
        'total_purchases' => 35000.00,
        'orders_count' => 8,
        'average_delivery_time' => '3 days',
        'payment_terms' => 'Net 30'
    ],
    [
        'supplier_id' => 'SUP004',
        'supplier_name' => 'Equipment Pro',
        'total_purchases' => 15000.00,
        'orders_count' => 5,
        'average_delivery_time' => '5 days',
        'payment_terms' => 'Net 45'
    ],
    [
        'supplier_id' => 'SUP005',
        'supplier_name' => 'Quality Ingredients',
        'total_purchases' => 10000.00,
        'orders_count' => 5,
        'average_delivery_time' => '2 days',
        'payment_terms' => 'Net 15'
    ]
];

$payment_methods = [
    'Bank Transfer' => 100000.00,
    'Credit Card' => 50000.00,
    'Check' => 20000.00,
    'Cash' => 10000.00
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Purchase Reports</h2>
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

    <!-- Purchase Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Purchases</h6>
                    <h3 class="mb-0">₱<?php echo number_format($purchase_summary['total_purchases'], 2); ?></h3>
                    <small class="text-success">+15.2% from last period</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Orders</h6>
                    <h3 class="mb-0"><?php echo $purchase_summary['total_orders']; ?></h3>
                    <small class="text-success">+10.5% from last period</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Average Order Value</h6>
                    <h3 class="mb-0">₱<?php echo number_format($purchase_summary['average_order_value'], 2); ?></h3>
                    <small class="text-success">+4.3% from last period</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Net Cost</h6>
                    <h3 class="mb-0">₱<?php echo number_format($purchase_summary['net_cost'], 2); ?></h3>
                    <small class="text-success">+14.8% from last period</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase Trends Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Purchase Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="purchaseTrendsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Suppliers and Payment Methods -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Suppliers</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Supplier ID</th>
                                    <th>Supplier Name</th>
                                    <th>Total Purchases</th>
                                    <th>Orders Count</th>
                                    <th>Avg. Delivery Time</th>
                                    <th>Payment Terms</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($top_suppliers as $supplier): ?>
                                    <tr>
                                        <td><?php echo $supplier['supplier_id']; ?></td>
                                        <td><?php echo htmlspecialchars($supplier['supplier_name']); ?></td>
                                        <td>₱<?php echo number_format($supplier['total_purchases'], 2); ?></td>
                                        <td><?php echo $supplier['orders_count']; ?></td>
                                        <td><?php echo $supplier['average_delivery_time']; ?></td>
                                        <td><?php echo $supplier['payment_terms']; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" title="View Orders">
                                                <i class="bi bi-list"></i>
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
// Purchase Trends Chart
const purchaseTrendsCtx = document.getElementById('purchaseTrendsChart').getContext('2d');
new Chart(purchaseTrendsCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($purchase_trends, 'date')); ?>,
        datasets: [{
            label: 'Purchase Amount',
            data: <?php echo json_encode(array_column($purchase_trends, 'amount')); ?>,
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