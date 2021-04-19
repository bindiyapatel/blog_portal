<?php
 // require_once "./db-production.php";
require_once "./db.php";

$conn = getDB();

// Response data holder
$response = array();

try 
{

    
    for ($i = 0; $i < 100; $i++)
    {
        $sql = "INSERT INTO `blogs` (`title`, `type`, `content`, `likes`, `comments`, `createdDate`) VALUES('New Title $i', 'type $i', 'New Description $i', 0, 0, '2021-02-26')";
        $statement = $conn->prepare($sql);
        $statement->execute();
    }
   


    if($conn->lastInsertId()) 
    {
        $response['status'] = true;
        $response['blog_id'] = $conn->lastInsertId();
        $response['message'] = "Blog  successfully created.";
    } 
    else {
        $response['status'] = false;
        $response['message'] = " getting a problem while creating a blog.";
    }

    echo json_encode($response);

  
}
catch(PDOException $e)
{
    echo "Error: ". $e->getMessage();
    $response['status'] = false;
    $response['message'] = "getting a problem while creating a blog.";

    echo json_encode($response);
}

?>