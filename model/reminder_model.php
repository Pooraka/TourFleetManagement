<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Reminder{
    
    public function updateReminderSent($reminderId,$sentDate){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE reminder SET sent_date = '$sentDate' WHERE reminder_id = '$reminderId'";
        
        $con->query($sql) or die ($con->error);
    }
    
    public function getReminder($reminderId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM reminder WHERE reminder_id='$reminderId'";
        
        $result = $con->query($sql) or die ($con->error);
        return $result;
    }
}
    