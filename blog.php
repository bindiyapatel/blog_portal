<?php

    require_once "./blogController.php";


    if(isset($_POST['getAll'])) 
    {
        getAllBlogs($_POST);
    }
    
    else if(isset($_POST['addBlog']) && $_POST['addBlog'] == true) 
    {
        addBlog($_POST);
    }

    else if(isset($_POST) && isset($_POST['blog_id']) && isset($_POST['deleteBlog']) && $_POST['deleteBlog'] == true) 
    {   
        deleteBlog($_POST['blog_id']);
    }

    else if(isset($_GET) && isset($_GET['blog_id'])) 
    {   
        getBlog($_GET['blog_id']);
    }

    else if(isset($_POST) && isset($_POST['blog_id']) && isset($_POST['updateBlog']) && $_POST['updateBlog'] == true) 
    {   
        updateBlog($_POST);
    }
?>