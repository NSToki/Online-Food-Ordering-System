<?php
$pageTitle = 'Complaints | FoodOrder';
require_once 'views/layouts/header.php';
?>

<style>
.complaint-container { display: flex; gap: 30px; align-items: flex-start; }
@media (max-width: 768px) { .complaint-container { flex-direction: column; } }
.complaint-form { flex: 1; background: var(--card-bg); border-radius: var(--radius); padding: 25px; box-shadow: var(--shadow); position: sticky; top: 90px; }
.complaint-list { flex: 2; }

.c-card { background: var(--card-bg); border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); margin-bottom: 20px; border: 1px solid var(--border); }
.c-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
.c-subject { font-size: 16px; font-weight: 700; color: var(--text); }
.c-date { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
.c-badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.badge-open { background: #fff3cd; color: #856404; }
.badge-resolved { background: #d4edda; color: #155724; }
.c-desc { font-size: 14px; color: #555; line-height: 1.5; background: #f8fafc; padding: 12px; border-radius: 6px; }
</style>

<h2 class="page-title"><i class="fa-solid fa-headset"></i> Support & Complaints</h2>

<div class="complaint-container">
    <div class="complaint-form">
        <h3 style="margin-bottom: 20px; font-size: 18px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">Submit a Complaint</h3>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=complaints">
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" class="form-control" required placeholder="Brief title...">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="5" required placeholder="Please describe your issue in detail..."></textarea>
            </div>
            <button type="submit" class="btn" style="width: 100%;"><i class="fa-solid fa-paper-plane"></i> Submit to Admin</button>
        </form>
    </div>

    <div class="complaint-list">
        <?php if (empty($complaints)): ?>
            <div class="empty-state">
                <i class="fa-regular fa-face-smile"></i>
                <p>No complaints submitted yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($complaints as $c): ?>
                <div class="c-card">
                    <div class="c-header">
                        <div>
                            <div class="c-subject"><?= htmlspecialchars($c['subject']) ?></div>
                            <div class="c-date"><?= date('M d, Y h:i A', strtotime($c['created_at'])) ?></div>
                        </div>
                        <div class="c-badge badge-<?= $c['status'] ?>"><?= $c['status'] ?></div>
                    </div>
                    <div class="c-desc">
                        <?= nl2br(htmlspecialchars($c['description'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
