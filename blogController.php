<?php

    session_start();

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
            $user = isset($_SESSION['user_id']) ?  (int) $_SESSION['user_id'] :"";
            $path = "";
            if($_FILES["blogImage"])
            {
                $valid_extensions = array('jpeg', 'jpg', 'png');
                $path = 'uploads/';
        
                $img = $_FILES["blogImage"]['name'];
                $tmp = $_FILES["blogImage"]['tmp_name'];
        
                $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                $final_image = rand(1000,1000000).$img;
                if(in_array($ext, $valid_extensions)) 
                { 
                    $path = $path.strtolower($final_image); 
                    // echo $path;
                    move_uploaded_file($tmp,$path);
                    chmod($path, 0755);
                } 
            
            }

            // Sql Prepare statement
            $statement = $conn->prepare("INSERT INTO `blogs` (`user_id`, `title`, `content`, `image`,  `createdDate`) VALUES ('$user','$title', '$content', '$path', '$createdDate')");

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

        // getting request body parameter
        $skip = $data['skip'] ? $data['skip'] : 0;
        $search = $data['search'];
        
        // base query
        $sql = "SELECT * FROM `blogs`";

        // Where clause for search
        if($search != '')
            $sql .= " WHERE `title` LIKE '%".$search."%' OR `content` LIKE '%".$search."%'";

        // order by f
         $sql .= " ORDER BY `blog_id` DESC";

        //limit and Skip records
        $sql .= " LIMIT 10 OFFSET $skip";

        // Response data holder
        $response = array();
        // echo $sql;
        try 
        {
            // Sql Prepare statement
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
            $result = $statement->fetchAll();
            if(count($result) > 0)
            {
                for ($i = 0; $i < count($result); $i++)
                {
                    $user = null;
                    if(isset($result[$i]['user_id'] ))
                    {
                        $user_id = $result[$i]['user_id'];
                        $statement = $conn->prepare("SELECT * FROM `users` WHERE `user_id` = '$user_id'");
                        $statement->execute();
                        $user = $statement->setFetchMode(PDO::FETCH_ASSOC);
                        $user = $statement->fetch();
                    }

                    if(isset($_SESSION['user_id']))
                    {
                        $loggedUser =  $_SESSION['user_id'];
                        $blog_id = $result[$i]["blog_id"];
                    $liked = $conn->prepare("SELECT * FROM `likes` WHERE `user_id` = '$loggedUser' AND  `blog_id` = '$blog_id'");
                    $liked->execute();
                    // $result = $liked->setFetchMode(PDO::FETCH_ASSOC);
                    $liked = $liked->fetchAll();
                        
                    if(count($liked) > 0)
                    $result[$i]["likeFlag"] = false;
                else 
                    $result[$i]["likeFlag"] = true;

                    }
                    

                    if($user)
                        $result[$i]['user'] = $user['name'];
                    else
                        $result[$i]['user'] = "Guest";
                }

                $response['status'] = true;
                $response['blogs'] = $result;
            } else {
                $response['status'] = false;
                $response['message'] = "There are no blogs";

                if($search != "")
                    $response['message'] .= " With term '$search'";                
            }

            $response['query'] = $sql;

            echo json_encode($response);
        }
        catch(PDOException $e)
        {
            echo "Error: ". $e->getMessage();
            $response['status'] = false;
            $response['message'] = "You are getting a problem while getting a blog.";
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
                
        // base query
                $sql = "SELECT * FROM `comments` WHERE `blog_id` = '$id'";
                $statement = $conn->prepare($sql);
                $statement->execute();
                $comments = $statement->setFetchMode(PDO::FETCH_ASSOC);
                $comments = $statement->fetchAll();

                for ($i = 0; $i < count($comments); $i++)
                {
                    $user = null;
                    if(isset($comments[$i]['user_id'] ))
                    {
                        $user_id = $comments[$i]['user_id'];
                        $statement = $conn->prepare("SELECT * FROM `users` WHERE `user_id` = '$user_id'");
                        $statement->execute();
                        $user = $statement->setFetchMode(PDO::FETCH_ASSOC);
                        $user = $statement->fetch();
                    }
                    if($user)
                        $comments[$i]['user'] = $user['name'];
                    else
                        $comments[$i]['user'] = "Guest";
                }
                

                $response['status'] = true;
                $response['blog'] = $result;
                $response['comments'] = $comments;
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
        if($_FILES["blogImage"])
            {
                $valid_extensions = array('jpeg', 'jpg', 'png');
                $path = 'uploads/';
        
                $img = $_FILES["blogImage"]['name'];
                $tmp = $_FILES["blogImage"]['tmp_name'];
        
                $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                $final_image = rand(1000,1000000).$img;
                if(in_array($ext, $valid_extensions)) 
                { 
                    $path = $path.strtolower($final_image); 
                    move_uploaded_file($tmp,$path);
                    chmod($path, 0755);
                    
                    $statement = $conn->prepare("SELECT * FROM `blogs` WHERE `blog_id` = '$id'");
                    $statement->execute();
                    $blog = $statement->setFetchMode(PDO::FETCH_ASSOC);
                    $blog = $statement->fetch();
                    // Delete old blog image
                    if($blog["image"] && $blog["image"] != "")
                    {
                        unlink($blog["image"]);
                    }
                }             
            }

        try 
        {
            $fields = "title = '$title', content = '$content'";
            if($path != "")
                $fields .= ", image = '$path'";
          
            $sql = "UPDATE `blogs` SET ".$fields." WHERE `blog_id` = '$id'";
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


    function likeBlog($data)
    {
        require_once "./db.php";
        $conn = getDB();

        $id = $data['blog_id'];
        $user = $data['user_id'];
        // Response data holder
        $response = array();

        try 
        {
            // Sql Prepare statement
            $statement = $conn->prepare("UPDATE `blogs` set `likes` = `likes` + 1  WHERE `blog_id` = '$id'  ");
            $statement->execute();

            if($statement->rowCount() > 0)
            {

                $statement = $conn->prepare("INSERT INTO `likes` (`user_id`, `blog_id`) VALUES ('$user','$id')");
                $statement->execute();
    

                $response['status'] = true;
                $response['message'] = "Blog Liked Successfully";
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
            $response['message'] = "You are getting a problem while Liking a blog.";
            echo json_encode($response);
        }   
    }

    
    function commentBlog($data)
    {
        require_once "./db.php";
        $conn = getDB();

        $id = $data['blog_id'];
        $user = $data['user_id'];
        $content = $data['content'];
        // Response data holder
        $response = array();

        try 
        {
            // Sql Prepare statement
            $statement = $conn->prepare("UPDATE `blogs` set `comments` = `comments` + 1  WHERE `blog_id` = '$id'  ");
            $statement->execute();

            if($statement->rowCount() > 0)
            {

                $statement = $conn->prepare("INSERT INTO `comments` (`blog_id`, `user_id`, `content`) VALUES ('$id','$user', '$content' )");
                $statement->execute();
    
                $response['status'] = true;
                $response['message'] = "Commented Successfully";
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
            $response['message'] = "You are getting a problem while Commenting.";
            echo json_encode($response);
        }   
    }
?>