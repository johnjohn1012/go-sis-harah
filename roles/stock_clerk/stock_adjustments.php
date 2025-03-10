<?php
session_start();
require_once '../../config/database.php';
require_once '../../includes/header.php';

// Check if user is Stock Clerk
if (!isset($_SESSION['user_id']) || $_SESSION['job_name'] !== 'Stock Clerk') {
    header("Location: ../../auth/login.php");
    exit();
}

// Mock data for stock adjustments
$stock_adjustments = [
    [
        'adjustment_id' => 1,
        'adjustment_type' => 'Damage',
        'adjustment_date' => '2024-03-20 14:30:00',
        'item_name' => 'Flour',
        'quantity' => 5,
        'direction' => 'Remove',
        'notes' => 'Damaged during storage',
        'adjusted_by' => 'John Doe'
    ],
    [
        'adjustment_id' => 2,
        'adjustment_type' => 'Spoilage',
        'adjustment_date' => '2024-03-19 16:45:00',
        'item_name' => 'Eggs',
        'quantity' => 12,
        'direction' => 'Remove',
        'notes' => 'Expired items',
        'adjusted_by' => 'John Doe'
    ],
    [
        'adjustment_id' => 3,
        'adjustment_type' => 'Other',
        'adjustment_date' => '2024-03-18 09:15:00',
        'item_name' => 'Sugar',
        'quantity' => 2,
        'direction' => 'Add',
        'notes' => 'Found extra stock',
        'adjusted_by' => 'John Doe'
    ]
];

$adjustment_types = [
    'Damage' => 'Damage',
    'Spoilage' => 'Spoilage',
    'Theft' => 'Theft',
    'Other' => 'Other'
];
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-4">Stock Adjustments</h2>
        </div>
        <div class="col text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdjustmentModal">
                <i class="bi bi-plus-circle me-2"></i>New Adjustment
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Stock Adjustment History</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search adjustments...">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Direction</th>
                                    <th>Notes</th>
                                    <th>Adjusted By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stock_adjustments as $adjustment): ?>
                                    <tr>
                                        <td><?php echo $adjustment['adjustment_id']; ?></td>
                                        <td><?php echo date('Y-m-d H:i', strtotime($adjustment['adjustment_date'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                echo match($adjustment['adjustment_type']) {
                                                    'Damage' => 'danger',
                                                    'Spoilage' => 'warning',
                                                    'Theft' => 'dark',
                                                    default => 'info'
                                                };
                                            ?>">
                                                <?php echo $adjustment['adjustment_type']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($adjustment['item_name']); ?></td>
                                        <td><?php echo $adjustment['quantity']; ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $adjustment['direction'] === 'Add' ? 'success' : 'danger'; ?>">
                                                <?php echo $adjustment['direction']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($adjustment['notes']); ?></td>
                                        <td><?php echo htmlspecialchars($adjustment['adjusted_by']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
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

<!-- Add Adjustment Modal -->
<div class="modal fade" id="addAdjustmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Stock Adjustment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Adjustment Type</label>
                        <select class="form-select" required>
                            <option value="">Select Type</option>
                            <?php foreach ($adjustment_types as $key => $value): ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item</label>
                        <select class="form-select" required>
                            <option value="">Select Item</option>
                            <option value="1">Flour</option>
                            <option value="2">Sugar</option>
                            <option value="3">Eggs</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Direction</label>
                        <select class="form-select" required>
                            <option value="">Select Direction</option>
                            <option value="Add">Add Stock</option>
                            <option value="Remove">Remove Stock</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save Adjustment</button>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?> 