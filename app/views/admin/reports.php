<?php require APPROOT . '/views/inc/header.php'; ?>

<link rel="stylesheet" href="<?= URLROOT ?>/public/css/admin/reports.css">

<?php require APPROOT . '/views/inc/sidebars/admin.php'; ?>

<main>
    <header class="reports-header">
        <h1>Sales Reports</h1>
        <p>Get insights into the performance of your marketplace and customer trends.</p>
    </header>

    <div class="report-section">
        <div class="report-card">
            <h3>Total Sales</h3>
            <p>$395,560</p>
            <p class="positive">+20% Since Last Year</p>
        </div>
        <div class="report-card">
            <h3>Total Orders</h3>
            <p>12,345</p>
            <p class="positive">+15% Since Last Month</p>
        </div>
        <div class="report-card">
            <h3>Top Selling Product</h3>
            <p>Organic Tomatoes</p>
            <p>5,678 units sold</p>
        </div>
        <div class="report-card">
            <h3>Active Customers</h3>
            <p>8,120</p>
            <p class="positive">+12% Growth</p>
        </div>
    </div>

    <div class="chart-section">
        <h2>Performance Charts</h2>
        <div class="chart-wrapper">
            <canvas id="sales-statistic"></canvas>
            <canvas id="customer-growth"></canvas>
        </div>
        <div class="chart-wrapper">
            <canvas id="order-analysis"></canvas>
            <canvas id="profit-distribution"></canvas>
        </div>
    </div>

    <div class="customer-product-section">
        <h2>Customer and Product Insights</h2>
        <div class="insights">
            <canvas id="customer-distribution"></canvas>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
    // Sales Statistics Chart
    const salesCtx = document.getElementById('sales-statistic').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Sales (Rs)',
                data: [12000, 15000, 17000, 20000, 22000, 25000],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 14 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Sales: $' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

    // Customer Growth Chart
    const customerCtx = document.getElementById('customer-growth').getContext('2d');
    new Chart(customerCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Active Customers',
                data: [800, 950, 1100, 1250, 1400, 1600],
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 14 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return 'Active Customers: ' + tooltipItem.raw;
                        }
                    }
                }
            }
        }
    });

    // Order Analysis Chart
    const orderCtx = document.getElementById('order-analysis').getContext('2d');
    new Chart(orderCtx, {
        type: 'pie',
        data: {
            labels: ['Completed Orders', 'Ongoing Orders', 'Cancelled Orders'],
            datasets: [{
                label: 'Order Status',
                data: [70, 20, 10],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                borderColor: '#ffffff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 14 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                        }
                    }
                }
            }
        }
    });

    // Profit Distribution Chart
    const profitCtx = document.getElementById('profit-distribution').getContext('2d');
    new Chart(profitCtx, {
        type: 'doughnut',
        data: {
            labels: ['Farmers', 'Suppliers', 'Delivery Persons', 'Consultants'],
            datasets: [{
                label: 'Profit Distribution',
                data: [40, 30, 20, 10],
                backgroundColor: ['#007bff', '#17a2b8', '#ffc107', '#ff5914'],
                borderColor: '#ffffff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 14 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                        }
                    }
                }
            }
        }
    });

    // Customer Distribution Chart
    const distributionCtx = document.getElementById('customer-distribution').getContext('2d');
    new Chart(distributionCtx, {
        type: 'polarArea',
        data: {
            labels: ['Vegetables', 'Fruits', 'Grains'],
            datasets: [{
                label: 'Category Distribution',
                data: [40, 30, 30],
                backgroundColor: ['#dc3545', '#ffc107', '#28a745'],
                borderColor: '#ffffff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        font: { size: 14 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + '%';
                        }
                    }
                }
            }
        }
    });
</script>
