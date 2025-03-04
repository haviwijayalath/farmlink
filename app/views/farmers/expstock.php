<?php require APPROOT . '/views/farmers/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<style>
  .container {
    margin-left: 250px;
    margin-top: 50px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }
  table, th, td {
    border: 1px solid black;
  }
  th, td {
    padding: 8px;
    text-align: left;
  }
  th {
    background-color: #f2f2f2;
  }
</style>

<div class="container">
  <h2>Expired Stock</h2>
  <table>
    <thead>
      <tr>
        <th>Product ID</th>
        <th>Farmer ID</th>
        <th>Name</th>
        <th>Type</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Expiry Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data as $product): ?>
        <?php if($product->farmer_id != $_SESSION['user_id']) continue; ?>
        <tr>
          <td><?php echo $product->fproduct_id; ?></td>
          <td><?php echo $product->farmer_id; ?></td>
          <td><?php echo $product->name; ?></td>
          <td><?php echo $product->type; ?></td>
          <td><?php echo $product->price; ?></td>
          <td><?php echo $product->stock; ?></td>
          <td><?php echo $product->exp_date; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>