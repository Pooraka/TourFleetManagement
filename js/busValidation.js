$(document).ready(function () {

    
    $("form").submit(function () {
        
        var vehicleNo = $("#vehicleno").val();
        var make = $("#make").val();
        var model = $("#model").val();
        var year = $("#year").val();
        var capacity = $("#capacity").val();
        var serviceIntervalKM = $("#serviceintervalkm").val();
        var currentMileage = $("#currentmileage").val();
        var lastServiceKM = $("#lastservicekm").val();
        var serviceIntervalMonths = $("#serviceintervalmonths").val();
        var lastServiceDate = $("#lastservicedate").val();
        var ac = $("#ac").val();
        var category = $("#category").val();

        if (vehicleNo == ""){

            $("#msg").html("Vehicle No Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        var vehicleNoPattern = /^([A-Z]{3}[-][0-9]{4}|[A-Z]{2}[-][0-9]{4}|[0-9]{3}[-][0-9]{4}|[0-9]{2}[-][0-9]{4})$/;

        if (!vehicleNoPattern.test(vehicleNo)) {
            $("#msg").html("Vehicle No format is invalid!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (make == ""){
            $("#msg").html("Make Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (model == ""){
            $("#msg").html("Model Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (year == "") {
            $("#msg").html("Year Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        /*
        var yearPattern = /^(19|20)\d{2}$/;

        // Check if the year is a valid 4-digit number between 1900 and 2099
        if (!yearPattern.test(year)) {
            $("#msg").html("Year format is invalid!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        */

        if (capacity == "") {
            $("#msg").html("Passenger Capacity Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (isNaN(capacity) || capacity <= 0){
            $("#msg").html("Passenger Capacity must be a positive number!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (serviceIntervalKM == "") {
            $("#msg").html("Service Interval Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        
        if (isNaN(serviceIntervalKM) || serviceIntervalKM <= 0){
            $("#msg").html("Service Interval must be a positive number!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (currentMileage == "") {
            $("#msg").html("Current Mileage Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (isNaN(currentMileage) || currentMileage < 0){
            $("#msg").html("Current Mileage must be 0 Km or Above!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (lastServiceKM == "") {
            $("#msg").html("Last Service Mileage Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (isNaN(lastServiceKM) || lastServiceKM < 0){
            $("#msg").html("Last Service Mileage must be 0 Km or Above!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (serviceIntervalMonths == "") {
            $("#msg").html("Service Interval (Months) Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (isNaN(serviceIntervalMonths) || serviceIntervalMonths <= 0){
            $("#msg").html("Service Interval (Months) must be a positive number!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (lastServiceDate == "") {
            $("#msg").html("Last Service Date Cannot Be Empty!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (ac == "") {
            $("#msg").html("Please select if AC is available or not!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if (category == "") {
            $("#msg").html("Please select a category!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

        if(parseInt(lastServiceKM,10) > parseInt(currentMileage,10)){
            $("#msg").html("Last Service Mileage cannot be greater than Current Mileage!");
            $("#msg").addClass("alert alert-danger");
            return false;
        }

    });
    
    
});

