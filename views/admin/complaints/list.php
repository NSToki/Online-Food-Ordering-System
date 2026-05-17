<?php
$pageTitle = 'Complaints';
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h1>Complaints</h1>
    <p>Review and resolve user-submitted complaints</p>
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
            <h2>All Complaints</h2>
            <p>Open complaints require your attention</p>
        </div>
        <?php
            $openCount = 0;
            if (!empty($complaints)) {
                foreach ($complaints as $c) {
                    if ($c['status'] === 'open') $openCount++;
                }
            }
        ?>
        <?php if ($openCount > 0) : ?>
            <span class="badge-status badge-danger">
                <?= $openCount ?> Open
            </span>
        <?php endif; ?>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Submitted By</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($complaints)) : ?>
                    <?php foreach ($complaints as $c) : ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($c['id']) ?></strong></td>
                            <td>
                                <div style="font-weight:600;"><?= htmlspecialchars($c['submitter_name']) ?></div>
                                <div style="font-size:12px;color:var(--text-muted);"><?= htmlspecialchars($c['submitter_email']) ?></div>
                                <span class="badge-status badge-info" style="margin-top:4px;font-size:10px;">
                                    <?= htmlspecialchars(ucfirst($c['submitter_role'])) ?>
                                </span>
                            </td>
                            <td style="font-weight:600;max-width:160px;">
                                <?= htmlspecialchars($c['subject']) ?>
                            </td>
                            <td style="color:var(--text-muted);font-size:13px;max-width:220px;line-height:1.5;">
                                <?= htmlspecialchars(mb_strimwidth($c['description'], 0, 120, '...')) ?>
                            </td>
                            <td>
                                <?php if ($c['status'] === 'open') : ?>
                                    <span class="badge-status badge-danger">Open</span>
                                <?php else : ?>
                                    <span class="badge-status badge-success">Resolved</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($c['status'] === 'open') : ?>
                                    <button
                                        class="btn btn-success btn-sm ajax-action-btn"
                                        data-id="<?= htmlspecialchars($c['id']) ?>"
                                        data-id-field="complaint_id"
                                        data-action="resolve"
                                        data-confirm="Mark this complaint as resolved?">
                                        <i class="fa-solid fa-check"></i> Resolve
                                    </button>
                                <?php else : ?>
                                    <span style="color:var(--text-light);font-size:13px;">
                                        <i class="fa-solid fa-circle-check" style="color:var(--success);"></i>
                                        Done
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <p>No complaints found</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>