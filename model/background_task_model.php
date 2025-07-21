<?php
require_once '../vendor/autoload.php';
include_once '../services/mailer.php';
include_once '../commons/db_connection.php';
include_once '../model/bus_model.php';
include_once '../model/user_model.php';
include_once '../model/reminder_model.php';
include_once '../model/tour_model.php';
include_once '../model/inspection_model.php';
include_once '../model/sparepart_model.php';
include_once '../model/tender_model.php';

$dbCon= new DbConnection();

class BackgroundTask{
    
    public function changeBusStatusToServiceDue(){
        
        $busObj = new Bus();
        $busResult = $busObj->getOperationalBuses();
        
        $today = new DateTime();
        $today->setTime(0, 0, 0);
        
        while ($busRow = $busResult->fetch_assoc()) {
            
            //Mileage Check
            $currentMileage = (int)$busRow['current_mileage_km'];
            $nextServiceMileage = (int)$busRow['last_service_mileage_km'] + (int)$busRow['service_interval_km'];
            
            $mileageIsDue = ($currentMileage >= $nextServiceMileage);
              
            //Date Check
            $lastServiceDate = new DateTime($busRow['last_service_date']);
            $serviceIntervalMonths = (int)$busRow['service_interval_months'];
            
            $nextServiceDate = (clone $lastServiceDate)->add(new DateInterval("P".$serviceIntervalMonths."M"));
            
            $serviceDateisDue = ($today >= $nextServiceDate);
            
            if ($mileageIsDue || $serviceDateisDue) {
            
                $busObj->changeBusStatus($busRow['bus_id'], 2);
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
        
        $tomorrowTourIdsResult = $tourObj->getToursForTomorow();
        
        while($tomorrowTourIdRow = $tomorrowTourIdsResult->fetch_assoc()){
        
            $tourId = $tomorrowTourIdRow['tour_id'];
            
            $inspectionExists = $inspectionObj->checkIfInspectionExistsForTour($tourId);
            
            if(!$inspectionExists){
                
                $busIdsResult = $tourObj->getBusListOfATour($tourId);
                
                while($busIdRow = $busIdsResult->fetch_assoc()){
                    
                    $busId = $busIdRow["bus_id"];
                    
                    $inspectionObj->scheduleInspection($busId, $tourId);
                }
            }
        }   
    }
    
    public function sendSparePartBelowReorderLevelReminder(){
        
        $userObj = new User();
        $userResult = $userObj->getUsersToSendSparePartListBelowReorderLevel();
        
        $sparePartObj = new SparePart();
        $sparePartResult = $sparePartObj->getSparePartsBelowReorderLevel();
        
        if($sparePartResult->num_rows==0){
            return;
        }
        
        $reminderObj = new Reminder();
        $reminderResult = $reminderObj->getReminder(2);
        $reminderRow = $reminderResult->fetch_assoc();
        
        $today = date('Y-m-d',time());
        
        if($reminderRow['sent_date']<$today){
        
            $mailObj = new Mailer();
            $emailSent = $mailObj->sendSparePartListBelowReOrderLevel($userResult,$sparePartResult);
            
            if($emailSent){
                $reminderObj->updateReminderSent(2, $today);
            }
        }
        
        return;
    }
    
    
    public function closeTenders(){
        
        $tenderObj = new Tender();
        
        $tenderResult = $tenderObj->getTendersToClose();
        
        while($tenderRow = $tenderResult->fetch_assoc()){
            
            $tenderObj->changeTenderStatus($tenderRow["tender_id"],2);
        }
        
        return;
    }
}