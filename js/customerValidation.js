$(document).ready(function () {
    
    $("form").submit(function () {
        
        var fname = $("#fname").val();
        var lname = $("#lname").val();
        var email = $("#email").val();
        var nic = $("#nic").val();
        var mno = $("#mno").val();
        var lno = $("#lno").val();
        
        var patNic = /^([0-9]{9}[VvXx]|[0-9]{12})$/;
        var patMobile = /^07[0-9]{8}$/;
        var patLandline = /^0[0-9]{9}/;
        
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
        if (nic == "")
        {
            $("#msg").html("NIC Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if (!nic.match(patNic))
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
        if (!mno.match(patMobile))
        {
            $("#msg").html("Mobile Number is invalid!!!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        if(lno!="" && !lno.match(patLandline))
        {
            $("#msg").html("Landline is invalid!!!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
    });
    
});
