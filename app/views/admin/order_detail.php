<?php require APPROOT . '/views/inc/header.php'; ?>
<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/order_detail.css">
<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<main class="admin-main-content" style="margin-left:250px; padding:20px;">
  <div class="content">
    <h2>Order Details</h2>

    <div class="order-summary">
      <div class="order-info">
        <div class="order-id">
          ORDER ID: #<?= htmlspecialchars($order->id) ?>
        </div>
        <div class="created">
          CREATED: <?= date('M d, Y H:i', strtotime($order->created_at)) ?>
        </div>
        <div class="customer">
          CUSTOMER: <?= htmlspecialchars($order->buyerName) ?>
          <?php if (!empty($order->buyerEmail)): ?>
            (<?= htmlspecialchars($order->buyerEmail) ?>)
          <?php endif; ?>
        </div>
        <?php if (!empty($order->dropAddress)): ?>
          <div class="drop-address">
            DROP‑OFF: <?= htmlspecialchars($order->dropAddress) ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="order-totals">
        <div class="total">
          TOTAL: Rs <?= number_format($order->total_amount, 2) ?>
        </div>
        <div class="status <?= strtolower($order->status) ?>">
          STATUS: <?= ucfirst(htmlspecialchars($order->status)) ?>
        </div>
      </div>
    </div>

    <table class="order-items">
      <thead>
        <tr>
          <th>SKU</th>
          <th>NAME</th>
          <th>PRICE (Rs)</th>
          <th>QTY</th>
          <th>LINE TOTAL (Rs)</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $subtotal     = 0;
          $deliveryFee  = 0;
        ?>
        <?php if (!empty($orderItems)): ?>
          <?php foreach ($orderItems as $item): 
            $lineTotal = $item->price * $item->quantity;
            $subtotal  += $lineTotal;
            $deliveryFee += $item->delivery_fee;
          ?>
            <tr>
              <td><?= htmlspecialchars($item->sku) ?></td>
              <td><?= htmlspecialchars($item->name) ?></td>
              <td><?= number_format($item->price, 2) ?></td>
              <td>x<?= (int)$item->quantity ?></td>
              <td><?= number_format($lineTotal, 2) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5">No items found for this order.</td></tr>
        <?php endif; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="4">Subtotal:</td>
          <td>Rs <?= number_format($subtotal, 2) ?></td>
        </tr>
        <tr>
          <td colspan="4">Delivery Fee:</td>
          <td>Rs <?= number_format($deliveryFee, 2) ?></td>
        </tr>
        <tr>
          <td colspan="4"><strong>Total:</strong></td>
          <td><strong>Rs <?= number_format($subtotal + $deliveryFee, 2) ?></strong></td>
        </tr>
      </tfoot>
    </table>
  </div>
</main>

<?php require APPROOT . '/views/inc/footer.php'; ?>
