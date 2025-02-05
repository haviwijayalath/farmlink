<?php require APPROOT . '/views/farmers/inc/header.php'; ?>
<?php require APPROOT . '/views/farmers/inc/farmer_sidebar.php'; ?>

<?php
$vegesNfruits = ["Carrot", "Broccoli", "Spinach", "Tomato", "Cucumber", "Pepper", "Lettuce", "Potato", "Onion", "Garlic",

'Persimmon', 'Strawberry', 'Banana', 'Tomato', 'Pear', 'Durian', 'Blackberry', 'Lingonberry', 'Kiwi', 'Lychee', 'Pineapple', 'Fig', 'Gooseberry', 'Passionfruit', 'Plum', 'Orange', 'GreenApple', 'Raspberry', 'Watermelon', 'Lemon', 'Mango', 'Blueberry', 'Apple', 'Guava', 'Apricot', 'Melon', 'Tangerine', 'Pitahaya', 'Lime', 'Pomegranate', 'Dragonfruit', 'Grape', 'Morus', 'Feijoa', 'Avocado', 'Kiwifruit', 'Cranberry', 'Cherry', 'Peach', 'Jackfruit', 'Horned Melon', 'Hazelnut', 'Pomelo', 'Mangosteen', 'Pumpkin', 'Japanese Persimmon', 'Papaya', 'Annona', 'Ceylon Gooseberry'];
?>

<link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/farmers/addstocks.css?version=1">

<br><br>

<div class="container">

  <a href="<?php echo URLROOT; ?>/farmers/managestocks" class="btn btn-secondary"><</a>
  <h2>Add Stocks</h2>

  <form action="<?php echo URLROOT; ?>/farmers/addstocks" method="POST" enctype="multipart/form-data">

    <div class="form-group">
      <label for="product_name">Product Name: <sup>*</sup></label>
      <!-- <input type="text" name="product_name" class="form-control form-control-lg" required> -->
      <input type="text" name="product_name" class="form-control form-control-lg" id="item-input" list="item-list" required>
      <datalist id="item-list">
        <?php foreach ($vegesNfruits as $item) : ?>
          <option value="<?php echo $item; ?>">
        <?php endforeach; ?>
      </datalist>
    </div>

    <div class="form-group">
      <label for="quantity">Quantity: <sup>*</sup></label>
      <input type="number" name="quantity" class="form-control form-control-lg" required>
    </div>

    <div class="form-group">
      <label for="price">Price: <sup>*</sup></label>
      <input type="text" name="price" class="form-control form-control-lg" required>
    </div>

    <div class="form-group">
      <label for="expiry_date">Expiry Date: <sup>*</sup></label>
      <input type="date" name="exp_date" class="form-control form-control-lg" required>
    </div>

    <div class="form-group">
      <label for="image">Product Image:</label>
      <input type="file" id="image" name="image" class="form-control form-control-lg" onchange="previewImage(event)">
    </div>

    <div id="image-preview" style="margin-top: 15px;">
        <img id="output" src="" alt="Image Preview" style="max-width: 200px; display: none;">
        <span style="color: red;"><?php echo $data['image_err']; ?></span>
    </div>

    <div class="form-group">
      <label for="description">Description:</label>
      <textarea name="description" class="form-control form-control-lg"></textarea>
    </div>

    <div class="form-group">
      <input type="submit" class="btn btn-success" value="Add Stock" name="submit">
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