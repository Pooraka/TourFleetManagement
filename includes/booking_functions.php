<ul class="list-group">
    <a href="dashboard.php" class="list-group-item">
        <span class="fa-solid fa-house"></span> &nbsp;
        Back To Dashboard
    </a>
    <a href="generate-quotation.php" class="list-group-item" style="display:<?php echo checkPermissions(76); ?>">
        <span class="fa-solid fa-plus"></span> &nbsp;
        Generate Quotation
    </a>
    <a href="pending-quotations.php" class="list-group-item" style="display:<?php echo checkPermissions(77); ?>">
        <span class="fa-solid fa-hourglass-half"></span> &nbsp;
        Pending Quotations
    </a>
    <a href="pending-customer-invoices.php" class="list-group-item" style="display:<?php echo checkPermissions(149); ?>">
        <span class="fa-solid fa-file-invoice"></span> &nbsp;
        Pending Invoices
    </a>
    <a href="booking-history.php" class="list-group-item" style="display:<?php echo checkPermissions(81); ?>">
        <span class="fa-solid fa-receipt"></span> &nbsp;
        Booking History
    </a>
</ul>
