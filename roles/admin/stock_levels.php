<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Admin
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Admin') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for stock levels
$stock_summary = [
    'total_items' => 150,
    'low_stock_items' => 12,
    'out_of_stock' => 3,
    'total_value' => 250000.00,
    'items_requiring_attention' => 15
];

$stock_items = [
    [
        'item_id' => 'MAT001',
        'item_name' => 'All-Purpose Flour',
        'category' => 'Flour & Grains',
        'current_stock' => 150,
        'minimum_stock' => 200,
        'maximum_stock' => 500,
        'unit' => 'kg',
        'unit_price' => 45.00,
        'total_value' => 6750.00,
        'status' => 'Low Stock',
        'last_received' => '2024-03-05',
        'last_issued' => '2024-03-07',
        'reorder_point' => 200,
        'supplier' => 'Bakery Supplies Co.'
    ],
    [
        'item_id' => 'MAT002',
        'item_name' => 'Granulated Sugar',
        'category' => 'Sweeteners',
        'current_stock' => 300,
        'minimum_stock' => 250,
        'maximum_stock' => 600,
        'unit' => 'kg',
        'unit_price' => 55.00,
        'total_value' => 16500.00,
        'status' => 'In Stock',
        'last_received' => '2024-03-06',
        'last_issued' => '2024-03-07',
        'reorder_point' => 250,
        'supplier' => 'Fresh Ingredients Ltd.'
    ],
    [
        'item_id' => 'MAT003',
        'item_name' => 'Butter',
        'category' => 'Dairy & Eggs',
        'current_stock' => 50,
        'minimum_stock' => 100,
        'maximum_stock' => 200,
        'unit' => 'kg',
        'unit_price' => 350.00,
        'total_value' => 17500.00,
        'status' => 'Low Stock',
        'last_received' => '2024-03-04',
        'last_issued' => '2024-03-07',
        'reorder_point' => 100,
        'supplier' => 'Fresh Ingredients Ltd.'
    ],
    [
        'item_id' => 'MAT004',
        'item_name' => 'Eggs',
        'category' => 'Dairy & Eggs',
        'current_stock' => 0,
        'minimum_stock' => 200,
        'maximum_stock' => 500,
        'unit' => 'pieces',
        'unit_price' => 12.00,
        'total_value' => 0.00,
        'status' => 'Out of Stock',
        'last_received' => '2024-03-03',
        'last_issued' => '2024-03-06',
        'reorder_point' => 200,
        'supplier' => 'Fresh Ingredients Ltd.'
    ],
    [
        'item_id' => 'MAT005',
        'item_name' => 'Baking Powder',
        'category' => 'Leavening Agents',
        'current_stock' => 25,
        'minimum_stock' => 30,
        'maximum_stock' => 100,
        'unit' => 'kg',
        'unit_price' => 180.00,
        'total_value' => 4500.00,
        'status' => 'Low Stock',
        'last_received' => '2024-03-02',
        'last_issued' => '2024-03-07',
        'reorder_point' => 30,
        'supplier' => 'Bakery Supplies Co.'
    ]
];

$status_badges = [
    'In Stock' => 'bg-success',
    'Low Stock' => 'bg-warning',
    'Out of Stock' => 'bg-danger',
    'Overstocked' => 'bg-info'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Stock Levels</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" onclick="exportStockReport()">
                <i class="bi bi-download me-2"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Stock Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Items</h5>
                    <h2 class="card-text"><?php echo $stock_summary['total_items']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Low Stock Items</h5>
                    <h2 class="card-text"><?php echo $stock_summary['low_stock_items']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Out of Stock</h5>
                    <h2 class="card-text"><?php echo $stock_summary['out_of_stock']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Value</h5>
                    <h2 class="card-text">₱<?php echo number_format($stock_summary['total_value'], 2); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Category</label>
                            <select class="form-select">
                                <option value="all">All Categories</option>
                                <option value="flour">Flour & Grains</option>
                                <option value="sweeteners">Sweeteners</option>
                                <option value="dairy">Dairy & Eggs</option>
                                <option value="fats">Fats & Oils</option>
                                <option value="leavening">Leavening Agents</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option value="all">All Status</option>
                                <option value="in_stock">In Stock</option>
                                <option value="low_stock">Low Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="overstocked">Overstocked</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Supplier</label>
                            <select class="form-select">
                                <option value="all">All Suppliers</option>
                                <option value="bsc">Bakery Supplies Co.</option>
                                <option value="fil">Fresh Ingredients Ltd.</option>
                                <option value="ps">Packaging Solutions</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" placeholder="Search items...">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Levels Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item ID</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Min/Max</th>
                                    <th>Unit</th>
                                    <th>Unit Price</th>
                                    <th>Total Value</th>
                                    <th>Status</th>
                                    <th>Last Received</th>
                                    <th>Last Issued</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stock_items as $item): ?>
                                    <tr>
                                        <td><?php echo $item['item_id']; ?></td>
                                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                                        <td><?php echo $item['current_stock']; ?></td>
                                        <td>
                                            <div><?php echo $item['minimum_stock']; ?> / <?php echo $item['maximum_stock']; ?></div>
                                            <small class="text-muted">Reorder: <?php echo $item['reorder_point']; ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($item['unit']); ?></td>
                                        <td>₱<?php echo number_format($item['unit_price'], 2); ?></td>
                                        <td>₱<?php echo number_format($item['total_value'], 2); ?></td>
                                        <td>
                                            <span class="badge <?php echo $status_badges[$item['status']]; ?>">
                                                <?php echo $item['status']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($item['last_received'])); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($item['last_issued'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" title="View History" onclick="viewStockHistory('<?php echo $item['item_id']; ?>')">
                                                <i class="bi bi-clock-history"></i>
                                            </button>
                                            <button class="btn btn-sm btn-success" title="Receive Stock" onclick="receiveStock('<?php echo $item['item_id']; ?>')">
                                                <i class="bi bi-box-arrow-in-down"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info" title="Issue Stock" onclick="issueStock('<?php echo $item['item_id']; ?>')">
                                                <i class="bi bi-box-arrow-up"></i>
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

<!-- Stock History Modal -->
<div class="modal fade" id="stockHistoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stock History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Reference</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Stock history data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Receive Stock Modal -->
<div class="modal fade" id="receiveStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Receive Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="receiveStockForm">
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference Number</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveReceiveStock()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Issue Stock Modal -->
<div class="modal fade" id="issueStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Issue Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="issueStockForm">
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference Number</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveIssueStock()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewStockHistory(itemId) {
    alert('Viewing stock history for item: ' + itemId);
    // Implement view history functionality
    $('#stockHistoryModal').modal('show');
}

function receiveStock(itemId) {
    alert('Receiving stock for item: ' + itemId);
    // Implement receive stock functionality
    $('#receiveStockModal').modal('show');
}

function issueStock(itemId) {
    alert('Issuing stock for item: ' + itemId);
    // Implement issue stock functionality
    $('#issueStockModal').modal('show');
}

function saveReceiveStock() {
    alert('Saving received stock...');
    // Implement save functionality
    $('#receiveStockModal').modal('hide');
}

function saveIssueStock() {
    alert('Saving issued stock...');
    // Implement save functionality
    $('#issueStockModal').modal('hide');
}

function exportStockReport() {
    alert('Exporting stock report...');
    // Implement export functionality
}
</script>

<?php require_once '../../includes/footer.php'; ?> 