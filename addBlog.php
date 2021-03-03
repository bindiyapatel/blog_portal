<?php 
    include_once "header.php";
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
            <input type="text" class="form-control" id="title" placeholder="Title" name="title" required>
            <div class="text-danger" id="errorTitle" style="display:none;">Please fill out this field.</div>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea cols="50" rows="5" type="text" class="form-control" id="content" placeholder="Content"
                name="content" required> </textarea>
            <div class="text-danger" id="errorContent" style="display:none;">Please fill out this field.</div>
        </div>
        <button type="button" id="createPost" class="btn btn-primary"> <?php echo $_GET && $_GET['blog_id'] ? "Update" : "Create"  ?></button>
    </form>
</div>

<script src="blog.js"></script>
<?php
    include_once "footer.php"
?>