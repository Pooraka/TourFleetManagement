<?php

include_once '../commons/db_connection.php';

$dbCon= new DbConnection();

class Reminder{
    
    public function updateReminderSent($reminderId,$sentDate){
        
        $con = $GLOBALS["con"];
        
        $sql = "UPDATE reminder SET sent_date = ? WHERE reminder_id = ?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("si", $sentDate, $reminderId); 

        $stmt->execute();

        $stmt->close();
    }
    
    public function getReminder($reminderId){
        
        $con = $GLOBALS["con"];
        
        $sql = "SELECT * FROM reminder WHERE reminder_id=?";
    
        $stmt = $con->prepare($sql);

        $stmt->bind_param("i", $reminderId); 

        $stmt->execute();

        $result = $stmt->get_result();

        $stmt->close();

        return $result;
    }
}
    