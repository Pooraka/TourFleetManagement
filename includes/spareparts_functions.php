<ul class="list-group">
    <a href="dashboard.php" class="list-group-item">
        <span class="fa-solid fa-house"></span> &nbsp;
        Back To Dashboard
    </a>
    <a href="register-spareparts.php" class="list-group-item" style="display:<?php echo checkPermissions(98); ?>">
        <span class="fa-solid fa-gears"></span> &nbsp;
        Register Spare Parts
    </a>
    <a href="spare-part-types.php" class="list-group-item" style="display:<?php echo checkPermissions(99); ?>">
        <span class="fas fa-eye"></span> &nbsp;
        View Spare Part Types
    </a>
    <a href="add-spare-parts.php" class="list-group-item" style="display:<?php echo checkPermissions(101); ?>">
        <span class="fa-solid fa-cart-plus"></span> &nbsp;
        Add Spare Parts
    </a>
    <a href="view-grns.php" class="list-group-item" style="display:<?php echo checkPermissions(102); ?>">
        <span class="fa-solid fa-truck-ramp-box"></span> &nbsp;
        View GRNs
    </a>
    <a href="view-spare-parts.php" class="list-group-item" style="display:<?php echo checkPermissions(103); ?>">
        <span class="fa-solid fa-boxes-stacked"></span> &nbsp;
        View Spare Parts
    </a>
    <a href="../reports/part-inventory-report.php" class="list-group-item" target="_blank" style="display:<?php echo checkPermissions(106); ?>">
        <span class="fa-solid fa-warehouse"></span> &nbsp;
        Spare Part Inventory Report
    </a>
    <a href="spare-part-transaction-history.php" class="list-group-item" style="display:<?php echo checkPermissions(107); ?>">
        <span class="fa-solid fa-right-left"></span> &nbsp;
        Spare Part Transactions
    </a>
</ul>
