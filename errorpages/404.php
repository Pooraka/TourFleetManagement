<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <meta http-equiv="refresh" content="3"> -->
    <title>Page Not Found</title>
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
                        <h1><b>Lost Bus Alert! </br> 404 Page Not Found</b></h1>
                        </br>
                        <h3>It seems like the page you are looking for does not exist.
                            </br>
                            </br>
                            Try taking a different route</h3>
                        </br>
                        <h4>If you are lost, click the button to return to login page</h4>
                        </br>
                        <button type="button" style="background-color:#0d778a" class="btn btn-primary" onclick="window.location.href='/tourfleetmanagement/view/login.php';">
                            Go to login page
                        </button>
                    </div>
                    <div class="col-md-6 hidden-xs">
                        <img src="/tourfleetmanagement/images/errorimages/404error.png" style="height:400px"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
