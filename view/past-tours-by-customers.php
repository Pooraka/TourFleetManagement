<?php

include_once '../commons/session.php';
include_once '../model/customer_model.php';

//get user information from session
$userSession=$_SESSION["user"];
$userFunctions=$_SESSION['user_functions'];

$customerObj = new Customer();
$customersWithToursResult = $customerObj->getCustomersWithTours();

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Customer - Past Tours By Customer" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
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
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <table class="table" id="customer_table">
                        <thead>
                            <tr>
                                <th>Customer NIC</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Past Tours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($customerRow = $customersWithToursResult->fetch_assoc()) { 
                                
                                $customerId = $customerRow['customer_id'];
                                $customerContactResult = $customerObj->getCustomerContact($customerId,1);
                                $customerContactRow = $customerContactResult->fetch_assoc();
                                ?>
                            <tr>
                                <td><?php echo $customerRow['customer_nic']; ?></td>
                                <td><?php echo $customerRow['customer_fname'] . ' ' . $customerRow['customer_lname']; ?></td>
                                <td><?php echo $customerRow['customer_email']; ?></td>
                                <td><?php echo $customerContactRow['contact_number']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="loadTourInfo(<?php echo $customerId; ?>)" 
                                    data-toggle="modal" data-target="#tourInfoModal" >
                                        View Tour Info
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<div class="modal fade" id="tourInfoModal" role="dialog" >
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Past Tours</h4>
            </div>
            <div class="modal-body">
                <div id="tourInfoContent">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        
        var dataTableOptions = {
            "pageLength": 10,
            "order": [
                [ 1, "desc" ]
            ],
             "scrollX": true
        };
        
        var table = $("#customer_table").DataTable(dataTableOptions);


    });

    function loadTourInfo(customerId) {

        var url = "../controller/tour_controller.php?status=load_past_tours_by_customer";

        $.post(url, {customerId: customerId}, function(data) {
            $("#tourInfoContent").html(data);

            $("#tour_list_table").DataTable({
                "pageLength": 5,
                "order": [[ 0, "desc" ]]
            });
        });
    }
</script>

</html>