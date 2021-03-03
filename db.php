<?php
    
    function getDB() 
    {

        $hostname = "localhost";
        $username = "root";
        $password = "";
        $dbname = "blogs";
        $conn = null;
        try 
        {
            $conn = new PDO("mysql:host=$hostname; dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Connected successfully";
        }
        catch(PDOException $e)
        {
            echo "Database connection failed";
        }
        return $conn;
    }
?>