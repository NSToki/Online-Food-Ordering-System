<?php
$pageTitle = 'Delivery Agents';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Delivery Agents</h1>
    <p>Manage all approved delivery agents on the platform</p>
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
            <h2>Active Agents</h2>
            <p>Enable or disable agent accounts</p>
        </div>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Agent</th>
                    <th>Email</th>
                    <th>Vehicle</th>
                    <th>Online</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($agents)) : ?>
                    <?php foreach ($agents as $a) : ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($a['id']) ?></strong></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:36px;height:36px;border-radius:50%;background:var(--primary-glow);display:flex;align-items:center;justify-content:center;color:var(--primary);font-weight:700;font-size:14px;flex-shrink:0;">
                                        <?= htmlspecialchars(strtoupper(substr($a['name'], 0, 1))) ?>
                                    </div>
                                    <span style="font-weight:600;"><?= htmlspecialchars($a['name']) ?></span>
                                </div>
                            </td>
                            <td style="color:var(--text-muted);font-size:13px;"><?= htmlspecialchars($a['email']) ?></td>
                            <td>
                                <i class="fa-solid fa-motorcycle" style="color:var(--primary);margin-right:5px;"></i>
                                <?= htmlspecialchars($a['vehicle_type']) ?>
                            </td>
                            <td>
                                <?php if ($a['is_online'] == 1) : ?>
                                    <span class="badge-status badge-success">Online</span>
                                <?php else : ?>
                                    <span class="badge-status badge-muted">Offline</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($a['is_active'] == 1) : ?>
                                    <span class="badge-status badge-success">Active</span>
                                <?php else : ?>
                                    <span class="badge-status badge-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($a['is_active'] == 1) : ?>
                                    <button
                                        type="button"
                                        class="btn btn-warning btn-sm ajax-action-btn"
                                        data-id="<?= htmlspecialchars($a['id']) ?>"
                                        data-id-field="agent_id"
                                        data-action="deactivate"
                                        data-confirm="Deactivate this agent?">
                                        <i class="fa-solid fa-ban"></i> Deactivate
                                    </button>
                                <?php else : ?>
                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm ajax-action-btn"
                                        data-id="<?= htmlspecialchars($a['id']) ?>"
                                        data-id-field="agent_id"
                                        data-action="reactivate"
                                        data-confirm="Reactivate this agent?">
                                        <i class="fa-solid fa-rotate-right"></i> Reactivate
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fa-solid fa-person-biking"></i>
                                <p>No agents found</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>