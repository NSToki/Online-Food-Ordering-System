<div class="mb-4">
    <h2><i class="fa-solid fa-circle-exclamation"></i> Complaints</h2>
    <p class="text-muted">View complaints related to your restaurant submitted to the platform admin.</p>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Submitted By</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($complaints)): ?>
                    <tr><td colspan="6" class="text-muted text-center">No complaints found.</td></tr>
                <?php else: foreach($complaints as $c): ?>
                    <tr>
                        <td>#<?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['submitter_name']) ?></td>
                        <td><strong><?= htmlspecialchars($c['subject']) ?></strong></td>
                        <td style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($c['description']) ?>">
                            <?= htmlspecialchars($c['description']) ?>
                        </td>
                        <td>
                            <?php if($c['status'] == 'open'): ?>
                                <span class="badge badge-pending">Open</span>
                            <?php else: ?>
                                <span class="badge badge-delivered">Resolved</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('M d, Y', strtotime($c['created_at'])) ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
