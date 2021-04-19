<?php 
    include_once "header.php";
    session_start();
    if(!isset($_SESSION["isLoggedIn"]) || !isset($_SESSION["user_id"]) || $_SESSION["user_id"] == "" )
    {
        header('Location:login.php');
    }
?>

<div class="container-fluid" id="blogForm2">
    <h3 class="text-center"> <?php echo $_GET && $_GET['blog_id'] ? "Update" : "Create New"  ?>  Blog </h3>
    <div class="alert" id="responseAlert" style="display:none;">
        <!-- <strong>Success!</strong> Indicates a successful or positive action. -->
    </div>
    <input type="hidden" id="blog_id" value="<?php echo $_GET && $_GET['blog_id'] ? $_GET['blog_id'] : ""  ?>" />
    <form action="#" class="needs-validation" novalidate>
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" id="title" placeholder="Title" name="title"  required>
            <div class="text-danger" id="errorTitle" style="display:none;">Please fill out this field.</div>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea cols="50" rows="5" type="text" class="form-control" id="content" placeholder="Content"
                name="content" required> </textarea>
            <div class="text-danger" id="errorContent" style="display:none;">Please fill out this field.</div>
        </div>
        <div class="form-group">
            <label for="content">Image:</label>
            <input type="file" class="form-control" id="blogImage" placeholder="Blog Image" name="blogImage" accept="image/*" required>
            <div id="imagePreview" name="imagePreview"></div>
        </div>
        <button type="button" id="createPost" class="btn btn-primary"> <?php echo $_GET && $_GET['blog_id'] ? "Update" : "Create"  ?></button>
    </form>

    <?php
        if($_GET && $_GET['blog_id'])
        {
    ?>
    <div style="overflowy: auto; max-height: 400px; margin-top:50px">
        <h3> Comments </h3>
        <div id="listComments" >
        </div>
    </div>
    <?php
        }
    ?>
</div>

<script src="blog.js"></script>
<?php
    include_once "footer.php"
?>