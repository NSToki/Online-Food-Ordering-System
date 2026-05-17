<?php
$pageTitle = 'Customer Management';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Customer Management</h1>
    <p>View and manage all registered customers on the platform</p>
</div>

<?php if (!empty($message)) : ?>
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div>
            <h2>All Customers</h2>
            <p>Activate or deactivate customer accounts</p>
        </div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)) : ?>
                    <?php foreach ($customers as $c) : ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($c['id']) ?></strong></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:36px;height:36px;border-radius:50%;background:var(--info-bg);display:flex;align-items:center;justify-content:center;color:var(--info);font-weight:700;font-size:14px;flex-shrink:0;">
                                        <?= htmlspecialchars(strtoupper(substr($c['name'], 0, 1))) ?>
                                    </div>
                                    <span style="font-weight:600;"><?= htmlspecialchars($c['name']) ?></span>
                                </div>
                            </td>
                            <td style="color:var(--text-muted);font-size:13px;"><?= htmlspecialchars($c['email']) ?></td>
                            <td><?= htmlspecialchars($c['phone']) ?></td>
                            <td>
                                <?php if ($c['is_active'] == 1) : ?>
                                    <span class="badge-status badge-success">Active</span>
                                <?php else : ?>
                                    <span class="badge-status badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($c['is_active'] == 1) : ?>
                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm ajax-action-btn"
                                        data-id="<?= htmlspecialchars($c['id']) ?>"
                                        data-id-field="customer_id"
                                        data-action="deactivate"
                                        data-confirm="Deactivate this customer account?">
                                        <i class="fa-solid fa-ban"></i> Deactivate
                                    </button>
                                <?php else : ?>
                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm ajax-action-btn"
                                        data-id="<?= htmlspecialchars($c['id']) ?>"
                                        data-id-field="customer_id"
                                        data-action="reactivate"
                                        data-confirm="Reactivate this customer account?">
                                        <i class="fa-solid fa-rotate-right"></i> Reactivate
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fa-solid fa-users"></i>
                                <p>No customers found</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>