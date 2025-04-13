<div class="summary-container">
    <div class="summary-box">
        <h3>Total Earnings (This Month)</h3>
        <p><?php echo number_format($data['currentMonth'], 2); ?></p>
    </div>
    <div class="summary-box">
        <h3>Last Month</h3>
        <p><?php echo number_format($data['lastMonth'], 2); ?></p>
    </div>
</div>

<div class="summary-chart-container">
    <div class="summary-box">
        <h3>Yearly Earnings</h3>
        <p><?php echo number_format($data['yearly'], 2); ?></p>
    </div>
    <div class="chart-box">
        <canvas id="earningsChart"></canvas>
    </div>
</div>


