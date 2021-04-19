
$(document).ready(function(){

    var skip = 0, search = '', blogImage= null;

    function deleteBlog(id) {
        let data = {"blog_id": id, "deleteBlog":true};
        $.post("blog.php", data,
            function(data,status){
                let response = JSON.parse(data);
                if(response.status)
                {   
                    $('#responseAlert').removeClass("alert-danger");
                    $('#responseAlert').addClass("alert-success");
                    $('#responseAlert').html(`<strong>${response.message}</strong>`);
                    $('#responseAlert').show();
                    $(`#blogDiv-${id}`).remove();
                           
                    setTimeout(() => {
                        $('#responseAlert').hide();
                        window.location.href = "./";
                    }, 2000);
                }
                else {
                    $('#responseAlert').removeClass("alert-success");
                    $('#responseAlert').addClass("alert-danger");
                    $('#responseAlert').html(`<strong>${response.message}</strong>`);
                    $('#responseAlert').show();
                }
            });
    }

    
    function createBlog(data, index) 
    {      
        let html =  `<div class="card bg-warning text-dark blogDiv p-1" id="blogDiv-${data['blog_id']}" style='padding-left:15px'>`;
        

        html += `
            <div class="card-body p-1 cardBlog">
                <h3>Author: ${data['user']} </h3>
                <h4 class="card-title">${data['title']}</h4>
                <p class="card-text">${data['content']}</p>
        `;

        if(data['image'] && data['image'] !== "")
        {
            html += `<img class='card-img-top' src='${data['image']}' height='100px' width='100px' alt='Card image'>`
        }
       

        if(localStorage.getItem("loggedIn"))
        {
            html += `
                <p style='margin-top:15px'>
                    <span class='text-primary'> 
                        ${data["likes"]}  
                        <a class='text-primary ${!data["likeFlag"] && "disable" }' id='like-${data['blog_id']}'> Like </a>
                    </span>
                    <span class='text-primary'>
                        ${data["comments"]} 
                        <a  href='javascript:void()' class='text-primary' id='comment-${data['blog_id']}'> Comment </a>
                    </span>
                </p>
            `;

            if(data.user_id === localStorage.getItem("user_id"))
            {   
                html += `
                <a href="addBlog.php?blog_id=${data['blog_id']}" class="btn btn-sm btn-info" id="editButton">Edit</a>
                <a href="#" id="deleteButton-${data['blog_id']}" name="${data['blog_id']}" class="btn btn-sm btn-danger deleteButton" >Delete</a>`;
            }
        }

        html += `
                <p class="card-text pull-right pr-1">Created At: ${new Date(data['createdDate']).toDateString()}</p>

            </div>
        `;
        html += `</div>`;
        $('#listBlogs').append(html);

        if(localStorage.getItem("loggedIn"))
        {
            $(`#like-${data['blog_id']}`).click(function(){
                likeBlog(data['blog_id']);
            });

            $(`#comment-${data['blog_id']}`).click(function(){
                var comment = prompt("Enter Comment");
                if(comment !== null && comment !== "")
                {
                    commentBlog(data["blog_id"], comment);
                }
            });
            
            if(data.user_id === localStorage.getItem("user_id"))
            {
                $(`#deleteButton-${data['blog_id']}`).click(function(){
                    deleteBlog(data['blog_id']);
                });
            }
        }
    }

    

    function loadBlogs() {
        let data = {skip: skip, getAll: true, search: search };
        
        // if(search != '')
        //     data['search'] = search;

        $('#loader').show();
        $.post("blog.php", data,
        function(data,status){
            let response = JSON.parse(data);
            let blogs = response.blogs;

            $('#loader').hide();
            if(response.status)
            {
                $('#responseAlert').hide();
                if(blogs.length > 0){
                    var count = 0;
                    for (const blog of blogs) {
                        createBlog(blog, count);
                        count++;
                    }
                    skip += 10;
                }
                
                if(blogs.length < 10)
                {
                    $('#view_more').hide();
                    $('#loader').hide();
                }
            } else {
                // if(skip == 0)
                //     $('#listBlogs').html(`<h2>${response.message}</h2>`);

              
                $('#responseAlert').removeClass("alert-success");
                $('#responseAlert').addClass("alert-danger");
                $('#responseAlert').html(`<strong>${response.message}</strong>`);
                $('#responseAlert').show();

                $('#view_more').hide();
            }    
        });
    }

    $("#view_more").click(function(){
        loadBlogs();
    });

    $("#blogForm").ready(function(){
        if($('#blog_id').val() === "all")
            loadBlogs();
    });

    function validateData() 
    {
        let title = $('#title').val().trim();
        let content = $('#content').val().trim();
        let result = true;

        if(title.length <= 4)
        {
            $('#errorTitle').html('Title must have atleast 5 character.');
            $('#errorTitle').show();
            result = false;
        }
        else {
            $('#errorTitle').hide();  
        }
        
        if(content.length <= 10)
        {
            $('#errorContent').html('Title must have atleast 10 character.');
            $('#errorContent').show();
            result = false;
        } else {
            $('#errorContent').hide();
        }


        return result;
    }

    $("#blogImage").change(function(e) {
        
        if(e.target.files)
        {   
            let image = `<img src = '${URL.createObjectURL(e.target.files[0])}' height='100px' width='100px' />`;
            $("#imagePreview").html(image);
        }
    });

    $("#createPost").click(function(){
        if(validateData())
        {
            let title = $('#title');
            let content = $('#content');

            let data = new FormData();
            data.append("title", title.val().trim());
            data.append("content", content.val().trim());
            data.append("blogImage",  $('#blogImage').prop('files')[0]);
            if( $('#blog_id').val() != "")
            {
                data.append("updateBlog", true);
                data.append("blog_id", $('#blog_id').val());
            }
            else
            {
                data.append("addBlog", true);
            }

            $.ajax({
                url: "blog.php",
                type: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    let response = JSON.parse(data);
                    if (response.status) {
                        $('#responseAlert').removeClass("alert-danger");
                        $('#responseAlert').addClass("alert-success");
                        $('#responseAlert').html(`<strong>${response.message}</strong>`);
                        $('#responseAlert').show();
                        title.val('');
                        content.val('');
                        setTimeout(() => {
                            window.location.href = "./";
                        }, 2000);
                    } else {
                        $('#responseAlert').removeClass("alert-success");
                        $('#responseAlert').addClass("alert-danger");
                        $('#responseAlert').html(`<strong>${response.message}</strong>`);
                        $('#responseAlert').show();
                    }
                },
                error: function (e) {
                    // alert(e);
                }
            });

            
        }
    });

    
    
    function getBlog(id)
    {
        $.get(`blog.php?blog_id=${id}`,
        function(data,status){
            let response = JSON.parse(data);
            let comments = response.comments;
            $('#loader').hide();
            if(response.status)
            {
                $('#title').val(response.blog.title);
                $('#content').val(response.blog.content);

                if(response.blog.image && response.blog.image !== "")
                {
                    let image = `<img src = '${response.blog.image}' height='100px' width='100px' />`;
                    $("#imagePreview").html(image);
                }
                
                for (const comment of comments) {
                    let html =  `<div class="card bg-warning text-dark blogDiv p-1" id="blogDiv-${comment['comment_id']}" style='padding-left:15px'>`;
                    html += `
                        <div class="card-body p-1 cardBlog">
                            <p class="card-text">
                                <h3>${comment['user']}  : ${comment['content']} </h4>
                            </p>
                    `;
                    html += "</div> </div>";
                    $("#listComments").append(html);
                }
            } 
        });
    }

     

    $("#blogForm2").ready(function(){
        const id = $('#blog_id').val();
        if(id !== '' && id !== null && id !== "all" )
        {
            getBlog(id);
        }
    });
    
    $("#searchFilter").on("keyup", function(e) {
        e.stopPropagation();
        
        if(search !== $(this).val().trim())
        {
            search = $(this).val().trim();
            skip = 0;
            $('#listBlogs').html('');
            loadBlogs();
        }
     });

     $("#logoutButton").click(function() {
        $.post("auth.php", {logout: true},
        function(data,status){
            localStorage.removeItem("loggedIn");
            localStorage.removeItem("user_id");
            setTimeout(() => {
                window.location.href = "./";
            }, 2000);

        });
     });

     function likeBlog(blog_id)
     {
        $.post("blog.php", {
            likeBlog: true,
            blog_id: blog_id,
            user_id: localStorage.getItem("user_id")
        },
        function(data,status){

            const result = JSON.parse(data);

            if(result.status)
            {
                $('#responseAlert').removeClass("alert-danger");
                $('#responseAlert').addClass("alert-success");
                $('#responseAlert').html(`<strong>${result.message}</strong>`);
                $('#responseAlert').show();

            
                skip = 0;
                setTimeout(() => {

                    $('#listBlogs').html('');
                    loadBlogs();
                }, 2000);
            }
            else {
                $('#responseAlert').removeClass("alert-success");
                $('#responseAlert').addClass("alert-danger");
                $('#responseAlert').html(`<strong>${result.message}</strong>`);
                $('#responseAlert').show();
          
            }
        });   
     }

     
     function commentBlog(blog_id, comment)
     {
        $.post("blog.php", {
            commentBlog: true,
            blog_id: blog_id,
            user_id: localStorage.getItem("user_id"),
            content: comment
        },
        function(data,status){

            const result = JSON.parse(data);

            if(result.status)
            {
                $('#responseAlert').removeClass("alert-danger");
                $('#responseAlert').addClass("alert-success");
                $('#responseAlert').html(`<strong>${result.message}</strong>`);
                $('#responseAlert').show();

                skip = 0;
                setTimeout(() => {

                    $('#listBlogs').html('');
                    loadBlogs();
                }, 2000);
            }
            else {
                $('#responseAlert').removeClass("alert-success");
                $('#responseAlert').addClass("alert-danger");
                $('#responseAlert').html(`<strong>${result.message}</strong>`);
                $('#responseAlert').show();
          
            }
        });   
     }


});