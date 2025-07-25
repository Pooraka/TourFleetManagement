<ul class="list-group">
    <a href="dashboard.php" class="list-group-item">
        <span class="fa-solid fa-house"></span> &nbsp;
        Back To Dashboard
    </a>
    <a href="tour-management.php" class="list-group-item">
        <span class="fa-solid fa-window-restore"></span> &nbsp;
        Back To Tour Module
    </a>
    <a href="add-tour.php" class="list-group-item" style="display:<?php echo checkPermissions(82); ?>">
        <span class="fa-solid fa-plus"></span> &nbsp;
        Add Tour
    </a>
    <a href="pending-tours.php" class="list-group-item" style="display:<?php echo checkPermissions(83); ?>">
        <span class="fa-solid fa-clock-rotate-left"></span> &nbsp;
        Pending Tours
    </a>
    <a href="inspection-failed.php" class="list-group-item" style="display:<?php echo checkPermissions(87); ?>">
        <span class="fa-solid fa-triangle-exclamation"></span> &nbsp;
        Pre-Tour Failed Inspections
    </a>
    <a href="past-tour-info.php" class="list-group-item" style="display:<?php echo checkPermissions(165); ?>">
        <span class="fa-solid fa-scroll"></span> &nbsp;
        Past Tour Info
    </a>
</ul>