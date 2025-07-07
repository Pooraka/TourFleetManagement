<?php
require_once '../vendor/autoload.php';
include_once '../services/mailer.php';
include_once '../commons/db_connection.php';
include_once '../model/bus_model.php';
include_once '../model/user_model.php';
include_once '../model/reminder_model.php';
include_once '../model/tour_model.php';
include_once '../model/inspection_model.php';

$dbCon= new DbConnection();

class BackgroundTask{
    
    public function changeBusStatusToServiceDue(){
        
        $busObj = new Bus();
        $busResult = $busObj->getOperationalBuses();
        
        while($busRow = $busResult->fetch_assoc()){
            
            $busId = $busRow['bus_id'];
            $lastServiceMileage = (int) $busRow['last_service_mileage_km'];
            $lastServiceDate = $busRow['last_service_date'];
            $lastServiceTimestamp = strtotime($lastServiceDate);
            
            $serviceIntervalKM = (int) $busRow['service_interval_km'];
            $serviceIntervalMonths = (int) $busRow['service_interval_months'];
            $serviceDueTimestamp = strtotime("+".$serviceIntervalMonths." months",$lastServiceTimestamp);
            
            $currentMileage = (int) $busRow['current_mileage_km'];
            $todayTimestamp = strtotime('today');
            
            if($currentMileage>=$lastServiceMileage+$serviceIntervalKM){
                
                $busObj->changeBusStatus($busId, 2);
                
            }elseif($todayTimestamp>=$serviceDueTimestamp){
                
                $busObj->changeBusStatus($busId, 2);  
            }
        }
    }
    
    public function sendServiceDueEmail(){
        
        $userObj = new User();
        $userResult = $userObj->getUsersToSendBusServiceDueEmail();
        
        $busObj = new Bus();
        $busResult = $busObj->getServiceDueBuses();
        
        if($busResult->num_rows==0){
            return;
        }

        
        $reminderObj = new Reminder();
        $reminderResult = $reminderObj->getReminder(1);
        $reminderRow = $reminderResult->fetch_assoc();
        
        $today = date('Y-m-d',time());
        
        if($reminderRow['sent_date']<$today){
        
            $mailObj = new Mailer();
            $emailSent = $mailObj->sendServiceDueBusList($userResult,$busResult);
            
            if($emailSent){
                $reminderObj->updateReminderSent(1, $today);
            }
        }
        
        return;
    }
    
    public function scheduleBusInspectionsPreTour(){
        
        $tourObj = new Tour();
        $inspectionObj = new Inspection();
        
        $busIdArray = array();
        
        $tomorrowTourIdsResult = $tourObj->getToursForTomorow();
        
        while($tomorrowTourIdRow = $tomorrowTourIdsResult->fetch_assoc()){
        
            $tourId = $tomorrowTourIdRow['tour_id'];
            
            $busIdsResult = $tourObj->getBusListOfATour($tourId);
            
            while($busIdRow = $busIdsResult->fetch_assoc()){
                
                $busId = $busIdRow["bus_id"];
                array_push($busIdArray,$busId);
            }  
        }
              
        foreach($busIdArray as $busId){
            
            $inspectionObj->scheduleInspection($busId);
        }
        
    }
}