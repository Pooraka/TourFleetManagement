$(document).ready(function(){

    $("form").submit(function(){
        var username = $("#loginusername").val();
        var password = $("#loginpassword").val();

        if(username==""){
            $("#msg").addClass("alert alert-danger");
            $("#msg").html("<b>Username cannot be empty</b>");
            return false;
        }
        else if(password==""){
            $("#msg").addClass("alert alert-danger");
            $("#msg").html("<b>Password cannot be empty</b>");
            return false;
        }
    });


});