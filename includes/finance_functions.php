<ul class="list-group">
    <a href="dashboard.php" class="list-group-item">
        <span class="fa-solid fa-house"></span> &nbsp;
        Back To Dashboard
    </a>
    <a href="pending-service-payments.php" class="list-group-item" style="display:<?php echo checkPermissions(134); ?>">
        <span class="fa-solid fa-file-invoice-dollar"></span> &nbsp;
        Pending Service Payments
    </a>
    <a href="pending-supplier-payments.php" class="list-group-item" style="display:<?php echo checkPermissions(136); ?>">
        <span class="fa-solid fa-file-invoice-dollar"></span> &nbsp;
        Pending Supplier Payments
    </a>
    <a href="past-payment-info.php" class="list-group-item" style="display:<?php echo checkPermissions(161); ?>">
        <span class="fa-solid fa-scroll"></span> &nbsp;
        Past Payment Info
    </a>
    <a href="customer-invoice-summary.php" class="list-group-item" style="display:<?php echo checkPermissions(145); ?>">
        <span class="fa-solid fa-file-lines"></span> &nbsp;
        Customer Invoice Summary
    </a>
    <a href="cash-flow.php" class="list-group-item" style="display:<?php echo checkPermissions(158); ?>">
        <span class="fa-solid fa-piggy-bank"></span> &nbsp;
        Cash Flow
    </a>
    <a href="service-cost-trend.php" class="list-group-item" style="display:<?php echo checkPermissions(146); ?>">
        <span class="fa-solid fa-arrow-trend-up"></span> &nbsp;
        Service Cost Trend
    </a>
    <a href="supplier-cost-trend.php" class="list-group-item" style="display:<?php echo checkPermissions(159); ?>">
        <span class="fa-solid fa-arrow-trend-up"></span> &nbsp;
        Supplier Cost Trend
    </a>
    <a href="tour-income-trend.php" class="list-group-item" style="display:<?php echo checkPermissions(155); ?>">
        <span class="fa-solid fa-chart-line"></span> &nbsp;
        Tour Income Trend
    </a>
    <a href="service-payment-monthly-chart.php" class="list-group-item" style="display:<?php echo checkPermissions(160); ?>">
        <span class="fa-solid fa-chart-column"></span> &nbsp;
        Service Monthly Pmt Chart
    </a>
    <a href="supplier-payment-monthly-chart.php" class="list-group-item" style="display:<?php echo checkPermissions(144); ?>">
        <span class="fa-solid fa-chart-column"></span> &nbsp;
        Supplier Monthly Pmt Chart
    </a>
</ul>
