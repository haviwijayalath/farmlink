<?php require APPROOT . '/views/farmers/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/farmers/editstocks.css?version=1">

<br><br>

<div class="container">

  <a href="<?php echo URLROOT; ?>/farmers/managestocks" class="btn btn-secondary"><</a>
  <h2>Edit Stocks</h2>

  <form action="<?php echo URLROOT; ?>/farmers/editstocks/<?php echo $data['id']; ?>" method="POST" enctype="multipart/form-data">

    <div class="form-group">
      <label for="product_name">Product Name: </label>
      <input type="text" name="product_name" class="form-control form-control-lg" value="<?php echo $data['product_name']; ?>" >
    </div>

    <div class="form-group">
      <label for="quantity">Quantity: </label>
      <input type="number" name="quantity" class="form-control form-control-lg" value="<?php echo $data['quantity']; ?>" >
    </div>

    <div class="form-group">
      <label for="price">Price: </label>
      <input type="text" name="price" class="form-control form-control-lg" value="<?php echo $data['price']; ?>" >
    </div>

    <div class="form-group">
      <label for="expiry_date">Expiry Date: </label>
      <input type="date" name="exp_date" class="form-control form-control-lg" value="<?php echo $data['exp_date']; ?>" >
    </div>

    <div class="form-group">
      <label for="image">Product Image:</label>
      <input type="file" id="image" name="image" class="form-control form-control-lg" onchange="previewImage(event)">
    </div>

    <div id="image-preview" style="margin-top: 15px;">
        <img id="output" src="<?php echo URLROOT . '/public/uploads/farmer/products/' . $data['image']; ?>" alt="Image Preview" style="max-width: 200px; <?php echo empty($data['image']) ? 'display: none;' : ''; ?>">
        <span style="color: red;"><?php echo $data['image_err']; ?></span>
    </div>

    <div class="form-group">
      <label for="description">Description:</label>
      <textarea name="description" class="form-control form-control-lg"><?php echo $data['description']; ?></textarea>
    </div>

    <div class="form-group">
      <input type="submit" class="btn btn-success" value="Update Stock" name="submit">
    </div>

  </form>
</div>

<br><br>

<script>
  function previewImage(event) {
    const imagePreview = document.getElementById('output');
    imagePreview.src = URL.createObjectURL(event.target.files[0]);
    imagePreview.style.display = 'block';
    imagePreview.onload = () => URL.revokeObjectURL(imagePreview.src); // Free memory
  }
</script>

<?php require APPROOT . '/views/farmers/inc/footer.php'; ?>