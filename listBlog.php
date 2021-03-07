

<div class="alert alert-dismissible" id="responseAlert" style="display:none;">
        <!-- <strong>Success!</strong> Indicates a successful or positive action. -->
</div>
    
<div class="container-fluid" id="blogForm">
  
  <div class="card bg-light text-dark blogDiv p-1"> 

    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-3">
      <input type="text" class="form-control" id="searchFilter" name="searchFilter" placeholder="Search by Title, Content" />
    </div>

    <div class="card-body p-1 pull-right">
      <a href="addBlog.php" class="btn btn-success mr-1">New Blog</a>
    </div>
  </div>
  
  <input type="hidden" id="blog_id" value="all" />
  <div>
    <div id="listBlogs" style="overflowy: auto; max-height: 400px; margin-top:50px">
    </div>
  </div>
  
</div>

<div>
    <span class="btn" id="view_more">View more</span>
  </div>

<div id="loader">
    Loading...
</div>
<script src="blog.js"></script>

