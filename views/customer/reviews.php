<?php
$pageTitle = 'My Reviews | FoodOrder';
require_once 'views/layouts/header.php';
?>

<style>
.review-card { background: var(--card-bg); border-radius: var(--radius); padding: 25px; box-shadow: var(--shadow); margin-bottom: 20px; border-left: 4px solid var(--brand); }
.rev-header { display: flex; justify-content: space-between; margin-bottom: 15px; }
.rev-rest-name { font-size: 18px; font-weight: 700; color: var(--text); }
.rev-date { font-size: 13px; color: var(--text-muted); }
.rev-stars { color: #f59e0b; font-size: 15px; margin-bottom: 10px; }
.rev-text { font-size: 15px; color: #444; line-height: 1.6; }
.manager-reply { background: #f8fafc; padding: 15px; border-radius: 6px; margin-top: 15px; border-left: 3px solid #ccc; font-size: 14px; }
</style>

<h2 class="page-title"><i class="fa-solid fa-star"></i> My Reviews</h2>

<?php if (empty($reviews)): ?>
    <div class="empty-state">
        <i class="fa-regular fa-comment-dots"></i>
        <p>You haven't left any reviews yet.<br>Complete an order to review a restaurant!</p>
    </div>
<?php else: ?>
    <div style="max-width: 800px;">
        <?php foreach ($reviews as $rev): ?>
            <div class="review-card">
                <div class="rev-header">
                    <div class="rev-rest-name">
                        <i class="fa-solid fa-store" style="color:#aaa;margin-right:6px;font-size:14px;"></i> 
                        <?= htmlspecialchars($rev['restaurant_name']) ?>
                    </div>
                    <div class="rev-date"><?= date('M d, Y', strtotime($rev['created_at'])) ?></div>
                </div>
                
                <div class="rev-stars">
                    <?php for($i=1; $i<=5; $i++): ?>
                        <i class="fa-<?= $i <= $rev['rating'] ? 'solid' : 'regular' ?> fa-star"></i>
                    <?php endfor; ?>
                </div>
                
                <div class="rev-text">
                    <?= nl2br(htmlspecialchars($rev['comment'])) ?>
                </div>

                <?php if (!empty($rev['manager_reply'])): ?>
                    <div class="manager-reply">
                        <strong><i class="fa-solid fa-reply"></i> Restaurant Reply:</strong><br>
                        <span style="color:#555;"><?= nl2br(htmlspecialchars($rev['manager_reply'])) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once 'views/layouts/footer.php'; ?>
