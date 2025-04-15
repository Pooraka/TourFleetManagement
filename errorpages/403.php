<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="refresh" content="3"> -->
    <title>Access Denied</title>
    <?php include_once __DIR__."/../includes/bootstrap_css_includes.php"?>
</head>
<body>
    <div class="container">
        <div class="row" style="height:110px">
            <div style="text-align: center">
                <img src="/tourfleetmanagement/images/resizedlogo.png" style="height:100px"/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 col-md-offset-1" style="height:400px">
                <div class="row">
                    <div class="col-md-6" style="color:#34496e">
                        </br>
                        </br>
                        <h1><b>403 - Security Checkpoint!</b></h1>
                        </br>
                        <h3>Whoa there! This service station requires special clearance
                            </br>
                            </br>
                            Please ensure you're logged in with the appropriate permissions</h3>
                        </br>
                        <h4>If this seems incorrect, please contact support. Otherwise, please return to an authorized area.</h4>
                        </br>
                        <button type="button" style="background-color:#0d778a" class="btn btn-primary" onclick="window.location.href='/tourfleetmanagement/view/login.php';">
                            Go to login page
                        </button>
                    </div>
                    <div class="col-md-6">
                        <img src="/tourfleetmanagement/images/errorimages/403error.png" style="height:400px"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
