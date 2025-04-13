$(document).ready(function(){
    
    var newPassword = $('#new_password');
    var confirmPassword = $('#confirm_password');
    var newPasswordGroup = $('#newPasswordGroup');
    var confirmPasswordGroup = $('#confirmPasswordGroup');
    var errorMessages =[];
    
    function checkPasswordMatch() {

        var pwdValue = newPassword.val();
        var confirmPwdValue = confirmPassword.val();
        
        if (confirmPwdValue.length > 0 && pwdValue !== confirmPwdValue) {
            errorMessages.push("Passwords do not match");
        }else if (confirmPwdValue.length==0 && pwdValue.length>0){
            errorMessages.push("Please confirm your password");
        }
    }
    
    function validateComplexity() {
        
        var pwdValue = newPassword.val();
        
        if(pwdValue===""){
            errorMessages.push("New password cannot be Empty");
        }
        else if (!/[a-zA-Z]/.test(pwdValue)){
            errorMessages.push("New password should contain a letter");
        }
        else if (!/\d/.test(pwdValue)){
            errorMessages.push("New password should contain a number");
        }
        else if(pwdValue.length <10){
            errorMessages.push("New password should be more than 10 characters");
        }
    }
    
    newPassword.on('input', function() {
        
        errorMessages =[];
        validateComplexity();
        if ($('#confirm_password').val().length>0){
            checkPasswordMatch();
        }
        
        if (errorMessages.length > 0) {
            var finalMessage = errorMessages.join(', ');

            newPasswordGroup.addClass('has-error').removeClass('has-success');
            $("#msg").html(finalMessage);
            $("#msg").addClass("alert alert-danger");
            errorMessages =[];  
        } 
        else {
            newPasswordGroup.addClass('has-success').removeClass('has-error');
            confirmPasswordGroup.addClass('has-success').removeClass('has-error');
            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
        }
    });
    
    confirmPassword.on('input', function(){
        
        errorMessages =[];
        checkPasswordMatch();
        
        if(!errorMessages.includes("Passwords do not match")){
            validateComplexity();
        }
    
        if (errorMessages.length > 0) {
            
            var finalMessage = errorMessages.join(', ');

            confirmPasswordGroup.addClass('has-error').removeClass('has-success');
            $("#msg").html(finalMessage);
            $("#msg").addClass("alert alert-danger");
            
        } 
        else {
            confirmPasswordGroup.addClass('has-success').removeClass('has-error');
            newPasswordGroup.addClass('has-success').removeClass('has-error');
            $("#msg").html("");
            $("#msg").removeClass("alert alert-danger");
        }
    });
    
    $("form").submit(function(){
        
        var pwdValue = newPassword.val();
        var confirmPwdValue = confirmPassword.val();
        
        if(pwdValue=="")
        {
            $("#msg").html("New password cannot be empty");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        
        if (pwdValue !== confirmPwdValue) {
            
            $("#msg").html("Passwords do not match");
            $("#msg").addClass("alert alert-danger");
            return false;
        }
        
    });
    
    
});