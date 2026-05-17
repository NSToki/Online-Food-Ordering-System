<?php
$pageTitle = 'Pending Agents';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Pending Agent Approvals</h1>
    <p>Review new delivery agent registration requests</p>
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
            <h2>Agent Applications</h2>
            <p>Approve or reject pending delivery agent accounts</p>
        </div>
        <?php if (!empty($pending_agents)) : ?>
            <span class="badge-status badge-warning">
                <?= count($pending_agents) ?> Pending
            </span>
        <?php endif; ?>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Agent</th>
                    <th>Email</th>
                    <th>Vehicle Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pending_agents)) : ?>
                    <?php foreach ($pending_agents as $a) : ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($a['id']) ?></strong></td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:36px;height:36px;border-radius:50%;background:var(--warning-bg);display:flex;align-items:center;justify-content:center;color:var(--warning);font-weight:700;font-size:14px;flex-shrink:0;">
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
                            <td style="display:flex;gap:8px;align-items:center;">
                                <button
                                    type="button"
                                    class="btn btn-success btn-sm ajax-action-btn"
                                    data-id="<?= htmlspecialchars($a['id']) ?>"
                                    data-id-field="agent_id"
                                    data-action="approve"
                                    data-confirm="Approve this delivery agent?">
                                    <i class="fa-solid fa-check"></i> Approve
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm ajax-action-btn"
                                    data-id="<?= htmlspecialchars($a['id']) ?>"
                                    data-id-field="agent_id"
                                    data-action="reject"
                                    data-confirm="Reject and delete this agent application?">
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
                                <p>No pending agent applications</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>