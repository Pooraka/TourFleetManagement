<ul class="list-group">
    <a href="dashboard.php" class="list-group-item">
        <span class="fa-solid fa-house"></span> &nbsp;
        Back To Dashboard
    </a>
    <a href="user.php" class="list-group-item">
        <span class="fa-solid fa-window-restore"></span> &nbsp;
        Back To User Module
    </a>
    <a href="add-user.php" class="list-group-item" style="display:<?php echo checkPermissions(148); ?>">
        <span class="fa-solid fa-plus"></span> &nbsp;
        Add User
    </a>
    <a href="view-users.php" class="list-group-item" style="display:<?php echo checkPermissions(54); ?>">
        <span class="fa-solid fa-users-cog"></span> &nbsp;
        View Users
    </a>
    <a href="user-list-report.php" class="list-group-item" style="display:<?php echo checkPermissions(55); ?>">
        <span class="fa-solid fa-address-book"></span> &nbsp;
        Generate User List
    </a>
</ul>
