<?php

class DbConnection{

    private $hostname="localhost";
    private $dbusername="root";
    private $dbpassword="";
    private $dbname="tour_fleet_management_system_db";
    public $con;

    public function __construct(){

        $this->con = new mysqli($this->hostname,
                                $this->dbusername,
                                $this->dbpassword,
                                $this->dbname);

        if(!$this->con->connect_error){
            
            $GLOBALS["con"]=$this->con;

        } 
    }
}
