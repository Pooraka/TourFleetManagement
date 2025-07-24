<ul class="list-group">
    <a href="dashboard.php" class="list-group-item">
        <span class="fa-solid fa-house"></span> &nbsp;
        Back To Dashboard
    </a>
    <a href="add-supplier.php" class="list-group-item" style="display:<?php echo checkPermissions(61); ?>">
        <span class="fa-solid fa-plus"></span> &nbsp;
        Add Supplier
    </a>
    <a href="view-suppliers.php" class="list-group-item" style="display:<?php echo checkPermissions(62); ?>">
        <span class="fa-solid fa-truck-field"></span> &nbsp;
        View Suppliers
    </a>
    <a href="add-tender.php" class="list-group-item" style="display:<?php echo checkPermissions(67); ?>">
        <span class="fa-solid fa-plus"></span> &nbsp;
        Add Tender
    </a>
    <a href="open-tenders.php" class="list-group-item" style="display:<?php echo checkPermissions(68); ?>">
        <span class="fa-solid fa-folder-open"></span> &nbsp;
        View Pending Tenders
    </a>
    <a href="past-tenders.php" class="list-group-item" style="display:<?php echo checkPermissions(164); ?>">
        <span class="fa-solid fa-scroll"></span> &nbsp;
        Past Tenders
    </a>
    <a href="tender-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(150); ?>">
        <span class="fa-solid fa-file-contract"></span> &nbsp;
        Tender Status Report
    </a>
</ul>
