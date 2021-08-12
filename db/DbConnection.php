<?php

class DbConnection{

    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $database = "otrium_db";
    private $con;

    public function openConnection(){

        $this->con = null;
        try{
            $this->con = new PDO("mysql:host=".$this->host.";dbname=" . $this->database, $this->user, $this->password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //activating pdo exceptions catching
        }catch(PDOException $exception){
            echo "<pre>Database Connection Error : ".$exception->getMessage()."</pre>";
        }
        return $this->con;

    }

    public function closeConnection(){
        if(isset($this->con)){
            $this->con = null;
        }
    }

}

?>