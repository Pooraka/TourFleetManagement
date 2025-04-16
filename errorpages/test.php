<?php

include_once '../commons/session.php';
include_once '../model/module_model.php';

//get user information from session
$userSession=$_SESSION["user"];
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="User Management" ?>
        <div class="row" >
    <div class="col-md-offset-0 col-xs-3 col-xs-offset-4  col-md-3 col-sm-offset-0 " style="text-align: center;">
        <a href="../view/dashboard.php"><img src="../images/logo.png" alt="" style="height:100px;"></a>
    </div>
    <div class="col-md-6 hidden-xs" style="text-align:center;">
        </br>
        <h1>Tour Fleet Management System</h1>
    </div>
    <div class="col-md-3 hidden-xs"></div>
</div>
<hr />
<div class="row">
    <div class="col-md-3 col-xs-3 col-sm-3" style="vertical-align: middle;margin-top:5px">
        <b><?php echo ucwords($userSession["user_fname"]." ".$userSession["user_lname"]); ?></b>
    </div>
    <div class="col-md-6 col-xs-6 col-sm-6" style="text-align:center;margin-top:5px">
        <b id="page_name"><?php echo $pageName; ?></b> 
    </div>
    <div class="col-md-1 col-md-offset-2 col-xs-1 col-sm-offset-1 col-sm-1">
        <a href="../controller/login_controller.php?status=logout" class="btn btn-primary">Logout</a>
    </div>
</div>
<hr />
        <div class="col-md-3" id="sidebar">
            <button type="button" id="sidebarToggleBtn" class="btn btn-default btn-sm" style="margin-bottom: 15px; width: 100%;">
                <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
                <span class="button-text"> Collapse Menu</span> 
            </button>
            <div id="sidebarNav">
            <ul class="list-group" style="background-color: transparent;">
                <a href="add-user.php" class="list-group-item" style="background-color: #e4eaeb">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add User
                </a>
                <a href="view-users.php" class="list-group-item" style="background-color: #e4eaeb">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Users
                </a>
                <a href="generate-user-reports.php" class="list-group-item" style="background-color: #e4eaeb">
                    <span class="glyphicon glyphicon-book"></span> &nbsp;
                    Generate User Reports
                </a>
            </ul>
            </div>
            <div id="sidebarIconsOnly" style="text-align: center;"> <a href="add-user.php" title="Add User" class="icon-only-link">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a>
                <a href="view-users.php" title="View Users" class="icon-only-link">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                </a>
                <a href="generate-user-reports.php" title="Generate User Reports" class="icon-only-link">
                    <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        <div class="col-md-9" id="mainContent">
            <div class="col-md-6">
                <div class="panel panel-info" style="height:180px">
                    <div class="panel-heading">
                        <p align="center">No of Active Users</p>
                    </div>
                    <div class="panel-body">
                        <h1 class="h1" align="center">5</h1>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-info" style="height:180px">
                    <div class="panel-heading">
                        <p align="center">No of De-active Users</p>
                    </div>
                    <div class="panel-body">
                        <h1 class="h1" align="center">3</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../js/jquery-3.7.1.js"></script>
<script>
$(document).ready(function() {

    // Select the toggle button using its ID
    var $toggleBtn = $('#sidebarToggleBtn');

    // Attach the click event handler
    $toggleBtn.on('click', function() {

        // Toggle the 'sidebar-collapsed' class on the <body> element
        // The actual collapsing/expanding is handled by CSS rules
        // targeting descendants based on this body class.
        $('body').toggleClass('sidebar-collapsed');

        // --- Optional: Change Button Icon & Text ---

        // Find the icon element within the button that was clicked
        var $icon = $(this).find('.glyphicon');
        // Find the text element within the button (requires you add the span)
        var $text = $(this).find('.button-text');

        // Check if the body NOW has the collapsed class
        if ($('body').hasClass('sidebar-collapsed')) {
            // --- Sidebar is NOW collapsed ---
            // Change icon to indicate 'expand' (e.g., right arrow)
            $icon.removeClass('glyphicon-menu-hamburger').addClass('glyphicon-menu-right');
            // Hide the text if the span exists, otherwise just set HTML to icon only
            if ($text.length) {
                 $text.hide();
             } else {
                 // Fallback if span wasn't added: Replace entire button content
                 $(this).html('<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>');
             }
        } else {
            // --- Sidebar is NOW expanded ---
            // Change icon back to indicate 'collapse' (e.g., hamburger)
            $icon.removeClass('glyphicon-menu-right').addClass('glyphicon-menu-hamburger');
             // Show the text if the span exists, otherwise set HTML back
             if ($text.length) {
                 $text.show();
             } else {
                  // Fallback if span wasn't added: Replace entire button content
                 $(this).html('<span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span> Collapse Menu');
             }
        }
    }); // End of click handler

    // --- Recommendation for easier text toggling ---
    // For the text hide/show above to work best with .find('.button-text'),
    // modify your button HTML like this:
    /*
    <button type="button" id="sidebarToggleBtn" class="btn btn-default btn-sm" ...>
        <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
        <span class="button-text"> Collapse Menu</span>  <-- Wrap text in span
    </button>
    */

}); // End of document ready
</script>
</html>