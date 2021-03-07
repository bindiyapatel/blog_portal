
$(document).ready(function(){

    var skip = 0, search = '';

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

    
    function createBlog(data) 
    {      
        let html =  `<div class="card bg-warning text-dark blogDiv p-1" id="blogDiv-${data['blog_id']}">`;
        if(data['image'] && data['image'] !== "")
        {
            html += `<img class='card-img-top' src='${data['image']}' alt='Card image'>`
        }
        html += `
            <div class="card-body p-1 cardBlog">
                <h4 class="card-title">${data['title']}</h4>
                <p class="card-text">${data['content']}</p>

                <a href="addBlog.php?blog_id=${data['blog_id']}" class="btn btn-sm btn-info" id="editButton">Edit</a>
                <a href="#" id="deleteButton-${data['blog_id']}" name="${data['blog_id']}" class="btn btn-sm btn-danger deleteButton" >Delete</a>
                <p class="card-text pull-right pr-1">Created At: ${new Date(data['createdDate']).toDateString()}</p>

            </div>
        `;
        html += `</div>`;
        $('#listBlogs').append(html);

        $(`#deleteButton-${data['blog_id']}`).click(function(){
            deleteBlog(data['blog_id']);
        });
    }

    

    function loadBlogs() {
        let data = {skip: skip, getAll: true, search: search };
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
                    for (const blog of blogs) {
                        createBlog(blog);
                    }
                    skip += 10;
                }
                
                if(blogs.length < 10)
                {
                    $('#view_more').hide();
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

    $("#createPost").click(function(){
        if(validateData())
        {
            let title = $('#title');
            let content = $('#content');

            let data = {
                title: title.val().trim(),
                content: content.val().trim(),
            };

            if( $('#blog_id').val() != "")
            {
                data['updateBlog'] = true;
                data['blog_id'] = $('#blog_id').val();
            }
            else
            {
                data['addBlog'] = true;
            }

            $.post("blog.php", data,
            function(data,status){
                let response = JSON.parse(data);
                if(response.status)
                {   
                    $('#responseAlert').removeClass("alert-danger");
                    $('#responseAlert').addClass("alert-success");
                    $('#responseAlert').html(`<strong>${response.message}</strong>`);
                    $('#responseAlert').show();
                    title.val('');
                    content.val('');
                    setTimeout(() => {
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
    });

    
    
    function getBlog(id)
    {
        $.get(`blog.php?blog_id=${id}`,
        function(data,status){
            let response = JSON.parse(data);
            let blogs = response.blogs;
            $('#loader').hide();
            if(response.status)
            {
                $('#title').val(response.blog.title);
                $('#content').val(response.blog.content);
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

});