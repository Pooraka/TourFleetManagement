$(document).ready(function () {
    
    $("#addServiceStationForm").submit(function () {
        
        var stationName = $("#stationname").val();
        var address = $("#address").val();
        var mobile = $("#mobile").val();
        var landline = $("#landline").val();
        
        if (stationName == ""){
            $("#msg").html("Station Name Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        
        if (address == ""){
            $("#msg").html("Address Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        
        if (mobile == ""){
            $("#msg").html("Mobile Number Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        
        var mobilePattern = /^07[0-9]{8}$/;
        
        if (!mobilePattern.test(mobile)) {
            $("#msg").html("Mobile Number Is Invalid!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        
        if (landline == ""){
            $("#msg").html("Landline Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        
        var landlinePattern = /^0[0-9]{9}$/;
        
        if (!landlinePattern.test(landline)) {
            $("#msg").html("Landline Is Invalid!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
    });
    
    $("#addServiceStationForm").on("reset",function(){
        
        $("#msg").removeClass("alert alert-danger");
        $("#msg").html("");
    });
});
