<?php
$pageTitle = 'All Restaurants';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Restaurant Management</h1>
    <p>View and manage all registered restaurants on the platform</p>
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
            <h2>All Restaurants</h2>
            <p>Approve, suspend or reactivate restaurant accounts</p>
        </div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Restaurant</th>
                    <th>Manager</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($restaurants)) : ?>
                    <?php foreach ($restaurants as $r) : ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($r['id']) ?></strong></td>
                            <td>
                                <div style="font-weight:600;"><?= htmlspecialchars($r['name']) ?></div>
                            </td>
                            <td>
                                <div style="font-weight:500;"><?= htmlspecialchars($r['manager_name']) ?></div>
                                <div style="font-size:12px;color:var(--text-muted);"><?= htmlspecialchars($r['manager_email']) ?></div>
                            </td>
                            <td>
                                <?php if ($r['is_approved']) : ?>
                                    <span class="badge-status badge-success">Approved</span>
                                <?php else : ?>
                                    <span class="badge-status badge-danger">Suspended</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($r['is_approved']) : ?>
                                    <button
                                        class="btn btn-warning btn-sm ajax-action-btn"
                                        data-id="<?= htmlspecialchars($r['id']) ?>"
                                        data-id-field="restaurant_id"
                                        data-action="suspend"
                                        data-confirm="Are you sure you want to suspend this restaurant?">
                                        <i class="fa-solid fa-ban"></i> Suspend
                                    </button>
                                <?php else : ?>
                                    <button
                                        class="btn btn-success btn-sm ajax-action-btn"
                                        data-id="<?= htmlspecialchars($r['id']) ?>"
                                        data-id-field="restaurant_id"
                                        data-action="reactivate"
                                        data-confirm="Reactivate this restaurant?">
                                        <i class="fa-solid fa-rotate-right"></i> Reactivate
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fa-solid fa-store"></i>
                                <p>No restaurants found</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>