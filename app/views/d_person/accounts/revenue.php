<?php require APPROOT . '/views/inc/header.php'; ?>


<link rel="stylesheet" href="<?= URLROOT ?>/public/css/d_person/revenue.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php require APPROOT . '/views/inc/sidebars/d_person.php'; ?>

<div class="container">
    <h2>Earnings Dashboard</h2>

    <?php require APPROOT . '/views/d_person/components/summaryboxes.php'; ?>
    <?php require APPROOT . '/views/d_person/components/earningstable.php'; ?>
</div>

<?php require APPROOT . '/views/d_person/components/earningcharts.php'; ?>
<?php require APPROOT . '/views/inc/footer.php'; ?>