<ul class="list-group">
    <a href="dashboard.php" class="list-group-item">
        <span class="fa-solid fa-house"></span> &nbsp;
        Back To Dashboard
    </a>
    <a href="add-customer.php" class="list-group-item" style="display:<?php echo checkPermissions(49); ?>">
        <span class="fa-solid fa-plus"></span> &nbsp;
        Add Customer
    </a>
    <a href="view-customers.php" class="list-group-item" style="display:<?php echo checkPermissions(50); ?>">
        <span class="fa-solid fa-users"></span> &nbsp;
        View Customers
    </a>
    <a href="past-tours-by-customers.php" class="list-group-item" style="display:<?php echo checkPermissions(166); ?>">
        <span class="fa-solid fa-scroll"></span> &nbsp;
        Past Tours By Customers
    </a>
    <a href="revenue-by-customers.php" class="list-group-item" style="display:<?php echo checkPermissions(147); ?>">
        <span class="fa-solid fa-file-invoice-dollar"></span> &nbsp;
        Revenue By Customers
    </a>
</ul>