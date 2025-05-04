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
<hr style="border:1px solid lightsteelblue"/>
<div class="row">
    <div class="col-md-3 col-xs-3 col-sm-3" style="vertical-align: middle;margin-top:5px">
        <b><?php echo ucwords($userSession["user_fname"]." ".$userSession["user_lname"]); ?></b>
    </div>
    <div class="col-md-6 col-xs-6 col-sm-6" style="text-align:center;margin-top:5px">
        <b id="page_name"><?php echo $pageName; ?></b> 
    </div>
    <div class="col-md-1 col-md-offset-2 col-xs-1 col-sm-offset-1 col-sm-1">
        <a href="../controller/login_controller.php?status=logout" class="btn btn-danger">Logout</a>
    </div>
</div>
<hr style="border:1px solid lightsteelblue"/>
