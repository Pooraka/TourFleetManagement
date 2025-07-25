<ul class="list-group">
    <a href="dashboard.php" class="list-group-item">
        <span class="fa-solid fa-house"></span> &nbsp;
        Back To Dashboard
    </a>
    <a href="purchasing.php" class="list-group-item">
        <span class="fa-solid fa-window-restore"></span> &nbsp;
        Back To Purchasing Module
    </a>
    <a href="awarded-bids.php" class="list-group-item" style="display:<?php echo checkPermissions(89); ?>">
        <span class="fa-solid fa-gavel"></span> &nbsp;
        View Awarded Bids
    </a>
    <a href="pending-purchase-orders.php" class="list-group-item" style="display:<?php echo checkPermissions(92); ?>">
        <span class="fa-solid fa-file-import"></span> &nbsp;
        View Pending PO
    </a>
    <a href="past-purchase-orders.php" class="list-group-item" style="display:<?php echo checkPermissions(162); ?>">
        <span class="fa-solid fa-scroll"></span> &nbsp;
        Past Purchase Orders
    </a>
    <a href="po-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(97); ?>">
        <span class="fa-solid fa-chart-gantt"></span> &nbsp;
        PO Status Report
    </a>
</ul>