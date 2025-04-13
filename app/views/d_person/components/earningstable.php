<form action="#" method="POST" class="filter-form">
    <label for="date">Date:</label>
    <input type="date" name="date" id="date" value="<?= htmlspecialchars($data['date']); ?>">

    <label for="order_id">Order ID:</label>
    <input type="text" name="order_id" id="order_id" placeholder="Enter Order ID" value="<?= htmlspecialchars($data['order_id']); ?>">

    <button type="submit">Filter</button>
    <button type="button" onclick="window.location.href = window.location.pathname;">Reset</button> <!-- Reset button -->
</form>


<table class="earnings-table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Amount (Rs)</th>
            <th>Status</th>
            <th>Payment Date</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($data['earnings'])): ?>
            <tr><td colspan="4">No earnings found for the given filters.</td></tr>
        <?php else: ?>
            <?php foreach ($data['earnings'] as $earning): ?>
                <tr>
                    <td><?= htmlspecialchars($earning->order_id); ?></td>
                    <td><?= number_format($earning->amount, 2); ?></td>
                    <td><?= htmlspecialchars($earning->status); ?></td>
                    <td><?= htmlspecialchars($earning->date); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
