<?php

    

    // Function to Add blog 
    function addBlog($data)
    {
        require_once "./db.php";

        $conn = getDB();

        // Response data holder
        $response = array();

        try 
        {
        
            // Taking user input 
            $title = $data['title'];
            $content = $data['content'];
            $createdDate=date("Y-m-d");
            // Sql Prepare statement
            $statement = $conn->prepare("INSERT INTO `blogs` (`title`, `content`,`createdDate`) VALUES ('$title', '$content','$createdDate')");

            // Execute statement
            $statement->execute();

        
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
    }

    function  getAllBlogs($data)
    {
        require_once "./db.php";

        $conn = getDB();

        $skip = $data['skip'] ? $data['skip'] : 0;

        // Response data holder
        $response = array();

        try 
        {
            // Sql Prepare statement
            $statement = $conn->prepare("SELECT * FROM `blogs` ORDER BY `blog_id` DESC");
            $statement->execute();
            $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result = $statement->fetchAll();
          
            if(count($result) > 0)
            {
                $response['status'] = true;
                $response['blogs'] = $result;
            } else {
                $response['status'] = false;
                $response['message'] = "There are no blogs";
            }

            echo json_encode($response);
        }
        catch(PDOException $e)
        {
            echo "Error: ". $e->getMessage();
            $response['status'] = false;
            $response['message'] = "You are getting a problem while creating a blog.";
            echo json_encode($response);
        }
    }

    function getBlog($id)
    {
        require_once "./db.php";

        $conn = getDB();

        // Response data holder
        $response = array();

        try 
        {
            // Sql Prepare statement
            $statement = $conn->prepare("SELECT * FROM `blogs` WHERE `blog_id` = $id  ");
            $statement->execute();
            $result = $statement->fetch();
                        
            if(count($result) > 0)
            {
                $response['status'] = true;
                $response['blog'] = $result;
            } else {
                $response['status'] = false;
                $response['message'] = "There are no blogs";
            }
            echo json_encode($response);
        }
        catch(PDOException $e)
        {
            // echo "Error: ". $e->getMessage();
            $response['status'] = false;
            $response['message'] = "getting a problem while fetching a blog.";
            echo json_encode($response);
        }   
    }

    function updateBlog($data)
    {
        require_once "./db.php";

        $conn = getDB();

        // Response data holder
        $response = array();

        $id = $data['blog_id'];
        $title = $data['title'];
        $content = $data['content'];
        
        try 
        {
            // Sql Prepare statement
            $sql = "UPDATE `blogs` SET title = '$title', content = '$content' where `blog_id` = $id";
            $statement = $conn->prepare($sql);
            $statement->execute();
            
            if($statement->rowCount() > 0)
            {
                $response['status'] = true;
                $response['message'] = "Blog Updated successfully";
            } else {
                $response['status'] = false;
                $response['message'] = "You are getting a problem while updating a blog.";
            }
            echo json_encode($response);
        }
        catch(PDOException $e)
        {
            echo "Error: ". $e->getMessage();
            $response['status'] = false;
            $response['message'] = "You are getting a problem while updating a blog.";
            echo json_encode($response);
        }   
    }

        
    function deleteBlog($id)
    {
        require_once "./db.php";
        $conn = getDB();

        // Response data holder
        $response = array();

        try 
        {
            // Sql Prepare statement
            $statement = $conn->prepare("DELETE FROM `blogs` WHERE `blog_id` = '$id'  ");
            $statement->execute();
            if($statement->rowCount() > 0)
            {
                $response['status'] = true;
                $response['message'] = "Blog Deleted Successfully";
            } else {
                $response['status'] = false;
                $response['message'] = "There are no blogs";
            }

            echo json_encode($response);
        }
        catch(PDOException $e)
        {
            //echo "Error: ". $e->getMessage();
            $response['status'] = false;
            $response['message'] = "You are getting a problem while Deleting a blog.";
            echo json_encode($response);
        }   
    }

?>