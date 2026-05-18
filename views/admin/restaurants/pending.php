<?php
$pageTitle = 'Pending Restaurants';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Pending Approvals</h1>
    <p>Review and approve or reject new restaurant registration requests</p>
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
            <h2>Restaurant Applications</h2>
            <p>New restaurants waiting for your approval</p>
        </div>
        <?php if (!empty($pending_restaurants)) : ?>
            <span class="badge-status badge-warning">
                <?= count($pending_restaurants) ?> Pending
            </span>
        <?php endif; ?>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Restaurant</th>
                    <th>Manager</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pending_restaurants)) : ?>
                    <?php foreach ($pending_restaurants as $r) : ?>
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
                                <i class="fa-solid fa-location-dot" style="color:var(--primary);margin-right:5px;"></i>
                                <?= htmlspecialchars($r['address']) ?>, <?= htmlspecialchars($r['city']) ?>
                            </td>
                            <td style="display:flex;gap:8px;align-items:center;">
                                <button
                                    class="btn btn-success btn-sm ajax-action-btn"
                                    data-id="<?= htmlspecialchars($r['id']) ?>"
                                    data-id-field="restaurant_id"
                                    data-action="approve"
                                    data-confirm="Approve this restaurant?">
                                    <i class="fa-solid fa-check"></i> Approve
                                </button>
                                <button
                                    class="btn btn-danger btn-sm ajax-action-btn"
                                    data-id="<?= htmlspecialchars($r['id']) ?>"
                                    data-id-field="restaurant_id"
                                    data-action="reject"
                                    data-confirm="Reject and remove this restaurant application?">
                                    <i class="fa-solid fa-xmark"></i> Reject
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fa-solid fa-clock"></i>
                                <p>No pending restaurant applications</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>