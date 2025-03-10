<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Cashier
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Cashier') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for payment summary
$payment_summary = [
    'date' => '2024-03-07',
    'total_payments' => 1250.00,
    'total_transactions' => 25,
    'successful_payments' => 23,
    'failed_payments' => 2,
    'refunded_payments' => 75.00,
    'net_payments' => 1175.00,
    'payment_methods' => [
        'cash' => ['count' => 15, 'amount' => 750.00, 'success_rate' => 100],
        'card' => ['count' => 7, 'amount' => 400.00, 'success_rate' => 85.7],
        'mobile' => ['count' => 2, 'amount' => 100.00, 'success_rate' => 100],
        'qr_code' => ['count' => 1, 'amount' => 50.00, 'success_rate' => 100]
    ]
];

// Mock data for payment trends
$payment_trends = [
    ['date' => '2024-03-01', 'cash' => 650.00, 'card' => 350.00, 'mobile' => 150.00, 'qr_code' => 50.00],
    ['date' => '2024-03-02', 'cash' => 700.00, 'card' => 400.00, 'mobile' => 100.00, 'qr_code' => 75.00],
    ['date' => '2024-03-03', 'cash' => 800.00, 'card' => 450.00, 'mobile' => 200.00, 'qr_code' => 100.00],
    ['date' => '2024-03-04', 'cash' => 750.00, 'card' => 350.00, 'mobile' => 150.00, 'qr_code' => 50.00],
    ['date' => '2024-03-05', 'cash' => 850.00, 'card' => 400.00, 'mobile' => 100.00, 'qr_code' => 75.00],
    ['date' => '2024-03-06', 'cash' => 900.00, 'card' => 450.00, 'mobile' => 150.00, 'qr_code' => 100.00],
    ['date' => '2024-03-07', 'cash' => 750.00, 'card' => 400.00, 'mobile' => 100.00, 'qr_code' => 50.00]
];

// Mock data for failed payments
$failed_payments = [
    [
        'transaction_id' => 'TRX001',
        'date' => '2024-03-07 14:30:00',
        'amount' => 150.00,
        'payment_method' => 'Card',
        'reason' => 'Insufficient Funds',
        'customer' => 'John Doe'
    ],
    [
        'transaction_id' => 'TRX002',
        'date' => '2024-03-07 15:45:00',
        'amount' => 75.00,
        'payment_method' => 'Card',
        'reason' => 'Card Declined',
        'customer' => 'Jane Smith'
    ]
];
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col">
            <h2>Payment Summary</h2>
            <p class="text-muted">Payment statistics for <?php echo date('F d, Y', strtotime($payment_summary['date'])); ?></p>
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
                    <h6 class="card-title">Total Payments</h6>
                    <h3 class="mb-0">$<?php echo number_format($payment_summary['total_payments'], 2); ?></h3>
                    <small><?php echo $payment_summary['total_transactions']; ?> transactions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Successful Payments</h6>
                    <h3 class="mb-0">$<?php echo number_format($payment_summary['successful_payments'], 2); ?></h3>
                    <small><?php echo $payment_summary['successful_payments']; ?> transactions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-title">Failed Payments</h6>
                    <h3 class="mb-0">$<?php echo number_format($payment_summary['failed_payments'], 2); ?></h3>
                    <small><?php echo $payment_summary['failed_payments']; ?> transactions</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Refunded Payments</h6>
                    <h3 class="mb-0">$<?php echo number_format($payment_summary['refunded_payments'], 2); ?></h3>
                    <small>Total refunds</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Payment Methods Breakdown -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Methods Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Method</th>
                                    <th>Transactions</th>
                                    <th>Amount</th>
                                    <th>Success Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payment_summary['payment_methods'] as $method => $data): ?>
                                    <tr>
                                        <td><?php echo ucfirst($method); ?></td>
                                        <td><?php echo $data['count']; ?></td>
                                        <td>$<?php echo number_format($data['amount'], 2); ?></td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: <?php echo $data['success_rate']; ?>%">
                                                    <?php echo $data['success_rate']; ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Trends Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Trends</h5>
                </div>
                <div class="card-body">
                    <canvas id="paymentTrendsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Failed Payments -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Failed Payments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($failed_payments as $payment): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($payment['transaction_id']); ?></td>
                                        <td><?php echo date('M d, Y H:i', strtotime($payment['date'])); ?></td>
                                        <td><?php echo htmlspecialchars($payment['customer']); ?></td>
                                        <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                                        <td><?php echo htmlspecialchars($payment['reason']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="retryPayment('<?php echo $payment['transaction_id']; ?>')">
                                                <i class="bi bi-arrow-clockwise"></i>
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
    // Payment Trends Chart
    const paymentTrendsCtx = document.getElementById('paymentTrendsChart').getContext('2d');
    new Chart(paymentTrendsCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($payment_trends, 'date')); ?>,
            datasets: [
                {
                    label: 'Cash',
                    data: <?php echo json_encode(array_column($payment_trends, 'cash')); ?>,
                    borderColor: '#28a745',
                    tension: 0.1
                },
                {
                    label: 'Card',
                    data: <?php echo json_encode(array_column($payment_trends, 'card')); ?>,
                    borderColor: '#17a2b8',
                    tension: 0.1
                },
                {
                    label: 'Mobile',
                    data: <?php echo json_encode(array_column($payment_trends, 'mobile')); ?>,
                    borderColor: '#ffc107',
                    tension: 0.1
                },
                {
                    label: 'QR Code',
                    data: <?php echo json_encode(array_column($payment_trends, 'qr_code')); ?>,
                    borderColor: '#dc3545',
                    tension: 0.1
                }
            ]
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

function retryPayment(transactionId) {
    alert('Retrying payment for transaction: ' + transactionId);
    // Implement payment retry functionality
}

function exportReport() {
    alert('Exporting payment summary report...');
    // Implement report export functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 