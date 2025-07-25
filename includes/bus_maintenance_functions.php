<ul class="list-group">
    <a href="dashboard.php" class="list-group-item">
        <span class="fa-solid fa-house"></span> &nbsp;
        Back To Dashboard
    </a>
    <a href="bus-maintenance.php" class="list-group-item">
        <span class="fa-solid fa-window-restore"></span> &nbsp;
        Back To Bus Maintenance Module
    </a>
    <a href="add-service-station.php" class="list-group-item" style="display:<?php echo checkPermissions(114); ?>">
        <span class="fa-solid fa-plus"></span> &nbsp;
        Add Service Station
    </a>
    <a href="view-service-stations.php" class="list-group-item" style="display:<?php echo checkPermissions(115); ?>">
        <span class="fa-solid fa-shop"></span> &nbsp;
        View Service Stations
    </a>
    <a href="initiate-service.php" class="list-group-item" style="display:<?php echo checkPermissions(118); ?>">
        <span class="fa-solid fa-wrench"></span> &nbsp;
        Initiate Service
    </a>
    <a href="view-ongoing-services.php" class="list-group-item" style="display:<?php echo checkPermissions(119); ?>">
        <span class="fa-solid fa-gear fa-spin"></span> &nbsp;
        View Ongoing Services
    </a>
    <a href="service-history.php" class="list-group-item" style="display:<?php echo checkPermissions(122); ?>">
        <span class="fa-solid fa-history"></span> &nbsp;
        Service History
    </a>
    <a href="manage-checklist-items.php" class="list-group-item" style="display:<?php echo checkPermissions(125); ?>">
        <span class="fa-solid fa-list-check"></span> &nbsp;
        Manage Checklist Items
    </a>
    <a href="manage-checklist-template.php" class="list-group-item" style="display:<?php echo checkPermissions(129); ?>">
        <span class="fa-solid fa-file-lines"></span> &nbsp;
        Manage Checklist Template
    </a>
    <a href="pending-inspections.php" class="list-group-item" style="display:<?php echo checkPermissions(130); ?>">
        <span class="fa-solid fa-car-on"></span> &nbsp;
        Pending Inspections
    </a>
    <a href="past-inspections.php" class="list-group-item" style="display:<?php echo checkPermissions(152); ?>">
        <span class="fa-solid fa-check-double"></span> &nbsp;
        View Past Inspections
    </a>
    <a href="../reports/upcoming-services-report.php" class="list-group-item" target="_blank" style="display:<?php echo checkPermissions(132); ?>">
        <span class="fa-solid fa-calendar-days"></span> &nbsp;
        Upcoming Services Report
    </a>
    <a href="inspection-status-report.php" class="list-group-item" style="display:<?php echo checkPermissions(133); ?>">
        <span class="fa-solid fa-clipboard-check"></span> &nbsp;
        Inspection Result Report
    </a>
</ul>