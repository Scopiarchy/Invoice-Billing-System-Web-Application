<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';

require_login();

$user_id = get_current_user_id();

$stats_query = "
    SELECT 
        COUNT(*) as total_invoices,
        SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_invoices,
        SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_invoices,
        COALESCE(SUM(CASE WHEN status = 'paid' THEN total_amount ELSE 0 END), 0) as total_income
    FROM invoices
    WHERE user_id = ?
";

$stmt = $conn->prepare($stats_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
$stmt->close();

$invoices_query = "
    SELECT i.id, i.invoice_date, c.name as client_name, i.total_amount, i.status
    FROM invoices i
    JOIN clients c ON i.client_id = c.id
    WHERE i.user_id = ?
    ORDER BY i.created_at DESC
    LIMIT 5
";

$stmt = $conn->prepare($invoices_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$latest_invoices = $stmt->get_result();
$stmt->close();

$user_id = get_current_user_id();
?>

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome back, <strong><?php echo htmlspecialchars($user_id['name'] ?? 'User'); ?></strong>!</p>
    </div>
    <a href="invoices/add.php" class="btn">+ New Invoice</a>
</div>


<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Invoices</h3>
        <div class="value"><?php echo $stats['total_invoices'] ?? 0; ?></div>
    </div>
    <div class="stat-card">
        <h3>Paid Invoices</h3>
        <!-- Using CSS class instead of inline style -->
        <div class="value text-success"><?php echo $stats['paid_invoices'] ?? 0; ?></div>
    </div>
    <div class="stat-card">
        <h3>Draft Invoices</h3>
        <!-- Using CSS class instead of inline style -->
        <div class="value text-warning"><?php echo $stats['draft_invoices'] ?? 0; ?></div>
    </div>
    <div class="stat-card">
        <h3>Total Income</h3>
        <div class="value">$<?php echo number_format($stats['total_income'] ?? 0, 2); ?></div>
    </div>
</div>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2>Latest Invoices</h2>
        <a href="invoices/list.php" class="btn btn-secondary btn-sm">View All</a>
    </div>

    <?php if ($latest_invoices->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($invoice = $latest_invoices->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo str_pad($invoice['id'], 5, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo htmlspecialchars($invoice['client_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($invoice['invoice_date'])); ?></td>
                        <td>$<?php echo number_format($invoice['total_amount'], 2); ?></td>
                        <td>
                            <!-- Using CSS class for status badge -->
                            <span class="status-<?php echo $invoice['status']; ?>">
                                <?php echo ucfirst($invoice['status']); ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-muted" style="padding: 2rem;">No invoices yet. <a href="invoices/add.php">Create your first invoice</a></p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
