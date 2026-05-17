<div class="mb-4">
    <h2><i class="fa-solid fa-star"></i> Customer Reviews</h2>
    <p class="text-muted">Read and respond to feedback from your customers.</p>
</div>

<div class="grid-2">
    <?php if(empty($reviews)): ?>
        <p class="text-muted col-span-2">No reviews yet.</p>
    <?php else: foreach($reviews as $r): ?>
        <div class="card">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <strong><?= htmlspecialchars($r['customer_name']) ?></strong>
                    <div style="color: #f59e0b;">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <i class="<?= $i <= $r['rating'] ? 'fa-solid' : 'fa-regular' ?> fa-star"></i>
                        <?php endfor; ?>
                    </div>
                </div>
                <small class="text-muted"><?= date('M d, Y', strtotime($r['created_at'])) ?></small>
            </div>
            
            <p style="margin-bottom: 16px; font-style: italic;">"<?= htmlspecialchars($r['comment']) ?>"</p>
            
            <?php if(!empty($r['manager_reply'])): ?>
                <div style="background: rgba(59, 130, 246, 0.1); padding: 12px; border-radius: 8px; border-left: 3px solid var(--primary);">
                    <strong style="color: var(--primary); font-size: 0.9rem;">Your Reply:</strong>
                    <p style="font-size: 0.95rem; margin-top: 4px;"><?= htmlspecialchars($r['manager_reply']) ?></p>
                </div>
            <?php else: ?>
                <form method="POST" action="?route=manager/reviews">
                    <input type="hidden" name="review_id" value="<?= $r['id'] ?>">
                    <textarea name="manager_reply" class="form-control mb-3" rows="2" placeholder="Write a public reply..." required></textarea>
                    <button type="submit" class="btn btn-secondary btn-sm">Post Reply</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; endif; ?>
</div>
