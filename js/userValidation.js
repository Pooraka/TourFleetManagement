$(document).ready(function () {
    
    var pageName = $("#page_name").val();

    $("#user_role").change(function () {

        var role_id = $("#user_role").val();
        var url = "../controller/user_controller.php?status=load_functions";

        $.post(url, {role: role_id}, function (data) {

            $("#display_functions").html(data);

        });

    });
    
////    $(window).on('pageshow', function() {
//        
//        //checking if user access this using browser's back button to reset dropdown
////        if (event.originalEvent && event.originalEvent.persisted) {
//        
        if(pageName==="User Management - Add User"){
            
            $("#user_role").prop('selectedIndex', 0); // reset the drop down 

            $("#display_functions").html(''); //remove functions if displayed
        }
////        }
////    });

    $("form").submit(function () {

        var fname = $("#fname").val();
        var lname = $("#lname").val();
        var email = $("#email").val();
        var dob = $("#dob").val();
        var nic = $("#nic").val();
        var mno = $("#mno").val();
        var lno = $("#lno").val();
        var user_role = $("#user_role").val();

        var usernameInput = $("#username");

        var patNic = /^([0-9]{9}[VvXx]|[0-9]{12})$/;
        var patMobile = /^07[0-9]{8}$/;
        var patLandline = /^0[0-9]{9}$/;

        if (fname == "")
        {
            $("#msg").html("First Name Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if (lname == "")
        {
            $("#msg").html("Last Name Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if (email == "")
        {
            $("#msg").html("Email Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if (dob == "")
        {
            $("#msg").html("DOB Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if (nic == "")
        {
            $("#msg").html("NIC Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if (!patNic.test(nic))
        {
            $("#msg").html("NIC is invalid!!!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if (mno == "")
        {
            $("#msg").html("Mobile Number Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if (!patMobile.test(mno))
        {
            $("#msg").html("Mobile Number is invalid!!!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if (lno == "")
        {
            $("#msg").html("Landline Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if (!patLandline.test(lno))
        {
            $("#msg").html("Landline is invalid!!!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if (user_role == "")
        {
            $("#msg").html("User Role Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if(usernameInput.length>0)
        {
            var username = usernameInput.val();
            
            if(username=="")
            {
                $("#msg").html("Username Cannot Be Empty!");
                $("#msg").addClass("alert alert-danger");
                return false;
            }
        }
        
        var checkedFunctions = $("#display_functions input[type='checkbox'][name='function[]']:checked");
        
        if(checkedFunctions.length === 0){
            
            $("#msg").html("User functions are not selected");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
    });
});

function resetFunctions(user_id, role_id) {

    var url = "../controller/user_controller.php?status=reset_functions";

    $.post(url, {user_id: user_id, role: role_id}, function (data) {

        $("#display_functions").html(data).show;

    });
}

