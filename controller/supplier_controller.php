<?php
include_once '../commons/session.php';
include_once '../model/supplier_model.php';

//get user information from session
$userSession=$_SESSION["user"];
$userId = $userSession['user_id'];

$supplierObj = new Supplier();


if(!isset($_GET["status"])){
    ?>
    <script>
        window.location= "../view/login.php";
    </script>
    <?php
}

$status= $_GET["status"];

switch ($status){
    
    case "add_supplier":
        
        try{
            
            $supplierName = $_POST["name"];
            
            if($supplierName==""){
                throw new Exception("Supplier Name Cannot Be Empty");
            }
            
            $supplierContact = $_POST["contact"];
            
            if($supplierContact==""){
                throw new Exception("Supplier Contact Cannot Be Empty");
            }
            
            $patContact = "/^(07[0-9]{8}|0[0-9]{9})$/";
            
            if(!preg_match($patContact, $supplierContact)){
                 throw new Exception("Invalid Contact Number. Enter Only 1 Contact Number (Mobile or Landline)");
            }
            
            $supplierEmail = $_POST["email"];
            
            if($supplierEmail==""){
                throw new Exception("Supplier Email Cannot Be Empty");
            }
            
            $supplierObj->addSupplier($supplierName, $supplierContact, $supplierEmail);
            
            $msg = "Supplier $supplierName Added Successfully";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/view-suppliers.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
        
        }
        catch(Exception $e){
            
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/add-supplier.php?msg=<?php echo $msg;?>";
            </script>
            <?php
            
        }
    break;
    
    case "edit_supplier":
        
        try{
            
            $supplierId = $_POST["supplier_id"];
            $supplierName = $_POST["name"];
            
            if($supplierName==""){
                throw new Exception("Supplier Name Cannot Be Empty");
            }
            
            $supplierContact = $_POST["contact"];
            
            if($supplierContact==""){
                throw new Exception("Supplier Contact Cannot Be Empty");
            }
            
            $patContact = "/^(07[0-9]{8}|0[0-9]{9})$/";
            
            if(!preg_match($patContact, $supplierContact)){
                 throw new Exception("Invalid Contact Number. Enter Only 1 Contact Number (Mobile or Landline)");
            }
            
            $supplierEmail = $_POST["email"];
            
            if($supplierEmail==""){
                throw new Exception("Supplier Email Cannot Be Empty");
            }
            
            $supplierObj->updateSupplier($supplierId, $supplierName, $supplierContact, $supplierEmail);
            
            $msg = "Supplier $supplierName Updated Successfully";
            $msg = base64_encode($msg);

            ?>

            <script>
                window.location="../view/view-suppliers.php?msg=<?php echo $msg;?>&success=true";
            </script>

            <?php
        
        }
        catch(Exception $e){
        
            $msg= $e->getMessage();
            $msg= base64_encode($msg);
            ?>
    
            <script>
                window.location="../view/edit-supplier.php?msg=<?php echo $msg;?>";
            </script>
            <?php
            
        }
        
    break;
    
    case "deactivate_supplier":
        
        $supplierId = base64_decode($_GET["supplier_id"]);
        
        $supplierObj->changeSupplierStatus($supplierId,0);
        
        $msg = "Supplier Deactivated Successfully";
        $msg = base64_encode($msg);
        
        ?>
            <script>
                window.location="../view/view-suppliers.php?msg=<?php echo $msg;?>&success=true";
            </script>
        <?php
        
    break;

    case "activate_supplier":
        
        $supplierId = base64_decode($_GET["supplier_id"]);
        
        $supplierObj->changeSupplierStatus($supplierId,1);
        
        $msg = "Supplier Activated Successfully";
        $msg = base64_encode($msg);
        
        ?>
            <script>
                window.location="../view/view-suppliers.php?msg=<?php echo $msg;?>&success=true";
            </script>
        <?php
        
    break;

    case "remove_supplier":
        
        $supplierId = base64_decode($_GET["supplier_id"]);
        
        $supplierObj->changeSupplierStatus($supplierId,-1);
        
        $msg = "Supplier Removed Successfully";
        $msg = base64_encode($msg);
        
        ?>
            <script>
                window.location="../view/view-suppliers.php?msg=<?php echo $msg;?>&success=true";
            </script>
        <?php
        
    break;
}