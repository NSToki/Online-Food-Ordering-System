<div class="mb-4">
    <h2><i class="fa-solid fa-receipt"></i> Order History</h2>
    <p class="text-muted">View all past and current orders.</p>
</div>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($orders)): ?>
                    <tr><td colspan="6" class="text-muted text-center">No orders found.</td></tr>
                <?php else: foreach($orders as $o): ?>
                    <tr>
                        <td><strong>#<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
                        <td><?= htmlspecialchars($o['customer_name']) ?></td>
                        <td>
                            <ul style="margin-left: 16px; font-size: 0.9rem;">
                                <?php foreach($o['items'] as $item): ?>
                                    <li><?= $item['quantity'] ?>x <?= htmlspecialchars($item['item_name']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <strong>$<?= number_format($o['total_amount'], 2) ?></strong>
                            <br><small class="text-muted"><?= htmlspecialchars($o['payment_method']) ?></small>
                        </td>
                        <td>
                            <span class="badge badge-<?= $o['status'] ?>"><?= ucfirst(str_replace('_', ' ', $o['status'])) ?></span>
                        </td>
                        <td>
                            <?= date('M d, Y h:i A', strtotime($o['created_at'])) ?>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
