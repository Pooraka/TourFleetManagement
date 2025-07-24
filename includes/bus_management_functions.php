<ul class="list-group">
    <a href="dashboard.php" class="list-group-item">
        <span class="fa-solid fa-house"></span> &nbsp;
        Back To Dashboard
    </a>
    <a href="add-bus.php" class="list-group-item" style="display:<?php echo checkPermissions(108); ?>">
        <span class="fa-solid fa-plus"></span> &nbsp;
        Add Bus
    </a>
    <a href="view-buses.php" class="list-group-item" style="display:<?php echo checkPermissions(109); ?>">
        <span class="fa-solid fa-bus"></span> &nbsp;
        View Buses
    </a>
    <a href="bus-fleet-report.php" class="list-group-item" style="display:<?php echo checkPermissions(113); ?>">
        <span class="fa-solid fa-file-contract"></span> &nbsp;
        Bus Fleet Details Report
    </a>
</ul>
