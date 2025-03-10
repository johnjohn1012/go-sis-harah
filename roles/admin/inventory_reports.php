<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for inventory reports
$inventory_summary = [
    'total_items' => 150,
    'total_value' => 250000.00,
    'low_stock_items' => 12,
    'out_of_stock' => 3,
    'overstocked_items' => 5,
    'total_categories' => 8,
    'average_stock_level' => 85,
    'stock_turnover_rate' => 4.2
];

$stock_movements = [
    [
        'date' => '2024-03-01',
        'incoming' => 500,
        'outgoing' => 450,
        'adjustments' => -10
    ],
    [
        'date' => '2024-03-02',
        'incoming' => 600,
        'outgoing' => 480,
        'adjustments' => -5
    ],
    [
        'date' => '2024-03-03',
        'incoming' => 550,
        'outgoing' => 520,
        'adjustments' => -8
    ],
    [
        'date' => '2024-03-04',
        'incoming' => 700,
        'outgoing' => 600,
        'adjustments' => -12
    ],
    [
        'date' => '2024-03-05',
        'incoming' => 650,
        'outgoing' => 580,
        'adjustments' => -7
    ],
    [
        'date' => '2024-03-06',
        'incoming' => 750,
        'outgoing' => 650,
        'adjustments' => -15
    ],
    [
        'date' => '2024-03-07',
        'incoming' => 600,
        'outgoing' => 550,
        'adjustments' => -10
    ]
];

$low_stock_items = [
    [
        'item_id' => 'ITM001',
        'item_name' => 'Flour',
        'category' => 'Raw Materials',
        'current_stock' => 50,
        'unit' => 'kg',
        'reorder_level' => 100,
        'days_until_empty' => 5,
        'last_purchase' => '2024-03-05'
    ],
    [
        'item_id' => 'ITM002',
        'item_name' => 'Sugar',
        'category' => 'Raw Materials',
        'current_stock' => 30,
        'unit' => 'kg',
        'reorder_level' => 80,
        'days_until_empty' => 3,
        'last_purchase' => '2024-03-03'
    ],
    [
        'item_id' => 'ITM003',
        'item_name' => 'Eggs',
        'category' => 'Raw Materials',
        'current_stock' => 200,
        'unit' => 'pieces',
        'reorder_level' => 500,
        'days_until_empty' => 4,
        'last_purchase' => '2024-03-04'
    ],
    [
        'item_id' => 'ITM004',
        'item_name' => 'Butter',
        'category' => 'Raw Materials',
        'current_stock' => 15,
        'unit' => 'kg',
        'reorder_level' => 40,
        'days_until_empty' => 2,
        'last_purchase' => '2024-03-02'
    ],
    [
        'item_id' => 'ITM005',
        'item_name' => 'Milk',
        'category' => 'Raw Materials',
        'current_stock' => 25,
        'unit' => 'liters',
        'reorder_level' => 60,
        'days_until_empty' => 3,
        'last_purchase' => '2024-03-03'
    ]
];

$category_distribution = [
    'Raw Materials' => 45,
    'Finished Products' => 35,
    'Packaging' => 15,
    'Others' => 5
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Inventory Reports</h2>
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

    <!-- Inventory Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Items</h6>
                    <h3 class="mb-0"><?php echo $inventory_summary['total_items']; ?></h3>
                    <small class="text-success">+5 items from last month</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Value</h6>
                    <h3 class="mb-0">₱<?php echo number_format($inventory_summary['total_value'], 2); ?></h3>
                    <small class="text-success">+8.5% from last month</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Low Stock Items</h6>
                    <h3 class="mb-0"><?php echo $inventory_summary['low_stock_items']; ?></h3>
                    <small class="text-danger">+2 items from last week</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Stock Turnover Rate</h6>
                    <h3 class="mb-0"><?php echo $inventory_summary['stock_turnover_rate']; ?></h3>
                    <small class="text-success">+0.3 from last month</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Movements Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Stock Movements</h5>
                </div>
                <div class="card-body">
                    <canvas id="stockMovementsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Items and Category Distribution -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Low Stock Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item ID</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Unit</th>
                                    <th>Reorder Level</th>
                                    <th>Days Until Empty</th>
                                    <th>Last Purchase</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($low_stock_items as $item): ?>
                                    <tr>
                                        <td><?php echo $item['item_id']; ?></td>
                                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                                        <td><?php echo $item['current_stock']; ?></td>
                                        <td><?php echo htmlspecialchars($item['unit']); ?></td>
                                        <td><?php echo $item['reorder_level']; ?></td>
                                        <td>
                                            <span class="badge bg-danger">
                                                <?php echo $item['days_until_empty']; ?> days
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($item['last_purchase'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="Create Purchase Order">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" title="View Details">
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Category Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryDistributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Stock Movements Chart
const stockMovementsCtx = document.getElementById('stockMovementsChart').getContext('2d');
new Chart(stockMovementsCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($stock_movements, 'date')); ?>,
        datasets: [{
            label: 'Incoming',
            data: <?php echo json_encode(array_column($stock_movements, 'incoming')); ?>,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }, {
            label: 'Outgoing',
            data: <?php echo json_encode(array_column($stock_movements, 'outgoing')); ?>,
            borderColor: 'rgb(255, 99, 132)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Category Distribution Chart
const categoryDistributionCtx = document.getElementById('categoryDistributionChart').getContext('2d');
new Chart(categoryDistributionCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode(array_keys($category_distribution)); ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($category_distribution)); ?>,
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