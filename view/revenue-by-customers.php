<?php

include_once '../commons/session.php';
include_once '../model/customer_invoice_model.php';

//get user information from session
$userSession=$_SESSION["user"];

$customerInvoiceObj = new CustomerInvoice();

$invoiceResult = $customerInvoiceObj->getRevenueByCustomers();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <link rel="stylesheet" type="text/css" href="../css/dataTables.bootstrap.min.css"/>
    <?php include_once "../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <?php $pageName="Customer - Revenue By Customers" ?>
        <?php include_once "../includes/header_row_includes.php";?>
        <div class="col-md-3">
            <ul class="list-group">
                <a href="add-customer.php" class="list-group-item">
                    <span class="glyphicon glyphicon-plus"></span> &nbsp;
                    Add Customer
                </a>
                <a href="view-customers.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    View Customers
                </a>
                <a href="revenue-by-customers.php" class="list-group-item">
                    <span class="glyphicon glyphicon-search"></span> &nbsp;
                    Revenue By Customers
                </a>
            </ul>
        </div>
        <div class="col-md-9">
            <table class="table" id="customer_revenue_table">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Customer NIC</th>
                        <th>Email</th>
                        <th>Revenue (LKR)</th>
                        <th>No of Tours</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($invoiceRow = $invoiceResult->fetch_assoc()){?>
                    
                    <tr>
                        <td><?php echo $invoiceRow["customer_fname"]." ".$invoiceRow["customer_lname"];?></td>
                        <td><?php echo $invoiceRow["customer_nic"]?></td>
                        <td><?php echo $invoiceRow["customer_email"]?></td>
                        <td style="text-align: right"><?php echo number_format($invoiceRow["total_amount"],2)?></td>
                        <td style="text-align: center"><?php echo $invoiceRow["tours"]?></td>
                    </tr>
                    <?php }?>
                </tbody>
                <tfoot>
                    <tr>
                        <th style="white-space: nowrap">Page Total:</th>
                        <th style="white-space: nowrap"></th>
                        <th style="white-space: nowrap">Total Revenue:</th>
                        <th style="white-space: nowrap;text-align: right"></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
<script src="../js/datatable/jquery-3.5.1.js"></script>
<script src="../js/datatable/jquery.dataTables.min.js"></script>
<script src="../js/datatable/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        
        var dataTableOptions = {
            "pageLength": 5,
            "order": [
                [ 3, "desc" ] //Desc order amount
            ],
             "scrollX": true,
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api();
                
                // Calculate Total for the Current Page
                var pageTotal = 0;
                var pageData = api.column(3, { page: 'current' }).data(); // Get data for current page only
                
                for (var i = 0; i < pageData.length; i++) {
                    var amount = pageData[i];
                    var numericValue = parseFloat(String(amount).replace(/LKR /g, '').replace(/,/g, ''));
                    if (!isNaN(numericValue)) {
                       pageTotal += numericValue;
                    }
                }
                
                // Format and display the page total in the 2nd footer cell
                var formattedPageTotal = 'LKR ' + pageTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(api.column(1).footer()).html(formattedPageTotal);
                
                
                //Calculate Overall Total for All Filtered Pages
                
                var overallTotal = 0;
                var overallData = api.column(3, { search: 'applied' }).data(); // Get data for all filtered pages
                
                for (var i = 0; i < overallData.length; i++) {
                    
                    var amount = overallData[i];
                    var numericValue = parseFloat(String(amount).replace(/LKR /g, '').replace(/,/g, ''));
                    
                    if (!isNaN(numericValue)) {
                       overallTotal += numericValue;
                    }
                }
                
                // Format and display the overall total in the 4th footer cell
                var formattedOverallTotal = 'LKR ' + overallTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                $(api.column(3).footer()).html(formattedOverallTotal);
            }
        };

        $("#customer_revenue_table").DataTable(dataTableOptions);

    });
</script>
</html>