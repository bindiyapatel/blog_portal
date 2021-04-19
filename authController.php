<?php

    function checkEmailExists($email)
    {
        
        require_once "./db.php";        
        $conn = getDB();

        // try 
        // {
            $sql = "SELECT * FROM `users` WHERE `email_id` = '$email'";   

            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result = $statement->fetchAll();
            return count($result) == 0 ? false : true;
        // }
        // catch(PDOException $e)
        // {
        //     echo "Error: ". $e->getMessage();
        //     // $response['status'] = false;
        //     // $response['message'] = "Server Error.";

        //     // echo json_encode($response);
        // }
    
    }

    function login($data)
    {

        require_once "./db.php";

        
        $conn = getDB();

        // Response data holder
        $response = array();

        try 
        {
    
            $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
            $password = $data['password'];

            $sql = "SELECT * FROM `users` WHERE `email_id` = '$email'";
            
            $statement = $conn->prepare($sql);
            $statement->execute();
            
            $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result = $statement->fetchAll();
            
            if(!checkEmailExists($email))
            {
                $response['status'] = false;
                $response['message'] = "User does not exists";
                echo json_encode($response);
            } 
            else 
            {
                if($result[0]['password'] !== md5($password))
                {
                    $response['status'] = false;
                    $response['message'] = "Wrong Password !!!";  
                    echo json_encode($response);
                } 
                else
                {
                    unset($result[0]['password']);
                    $response['status'] = true;
                    $response['user'] = $result[0];
                    $response['message'] = "Login Successfully";

                    session_start();

                    $_SESSION['isLoggedIn'] = true;
                    $_SESSION['user_id'] = $result[0]['user_id'];
                    echo json_encode($response);
                }
            }
            // echo json_encode($response);
        }
        catch(PDOException $e)
        {
            echo "Error: ". $e->getMessage();
            $response['status'] = false;
            $response['message'] = "Server Error.";

            echo json_encode($response);
        }
    }

    function register($data)
    {
        require_once "./db.php";

        
        $conn = getDB();

        $response = array();

        try 
        {
            // print_r($data);
        
            $name = $data['name'];
            $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
            $password = md5($data['password']);

            if($email == false)
            {
                $response['status'] = false;
                $response['message'] = "Invalid email";   
            }
            else if(checkEmailExists($email))
            {
                $response['status'] = false;
                $response['message'] = "User with that email Id already exists.";      
            }
            else 
            {
                
                $sql = "INSERT INTO `users` (`name`, `email_id`, `password`) VALUES ('$name', '$email', '$password')";
                
                $statement = $conn->prepare($sql);
                $statement->execute();
            
                if($conn->lastInsertId() > -1) 
                {
                    $response['status'] = true;
                    $response['user_id'] = $conn->lastInsertId();
                    $response['message'] = "Registration Successfully.";
                } 
                else {
                    $response['status'] = false;
                    $response['message'] = "Server Error.";
                }
            }
            echo json_encode($response);
        }
        catch(PDOException $e)
        {
            echo "Error: ". $e->getMessage();
            $response['status'] = false;
            $response['message'] = "Server Error.";

            echo json_encode($response);
        }
    }

    $data = array(
        'name' => 'kamlesh',
        'password' => '12345678',
        'email' => 'kamlesh@gmail.com'
    );

    // register($data);
    
    function logout()
    {
        // try{
            session_start();

            unset($_SESSION['isLoggedIn']);
            unset($_SESSION['user']);
            session_destroy();
            session_unset();

            header("location=index.php;");
        // }
        // catch(PDOException $e)
        // {
        //     echo $e;
        //     header("location=index.php");
          
        // }
    }
    

 
?>
