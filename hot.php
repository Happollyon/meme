<?php
require_once 'header.php';
require_once 'functions.php';
echo "<div id='selector-container'><div class='$user' id='selector'>";
?>
<link rel="stylesheet" href="style/hot.css">

<button id="hour" onclick="posts(1)" class="1">Hour</button>
<button id="day" onclick="posts(24)" class="24">Day</button>
<button id="week" onclick="posts(168)" class="168">Week</button>
</div></div>
<div id="posts">
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script>

    {

            function posts(hour) {
                let number = 0
                 $('div').remove('#post2')


                let user = $('#selector').attr('class')
                let url = 'posts.php?number=' + number + '&user=' + user + '&hour=' + hour;
                $.ajax(
                    {
                        url: url,
                        tyep: 'GET',
                        success: function (data) {
                            $('#posts').append(data);
                        }
                    }
                )


        number = number + 6;
        $(window).scroll(function () {
            let url = 'posts.php?number=' + number + '&user=' + user + '&hour='+ hour;  // url to be passed is created
            if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                $.ajax(
                    {
                        url: url,
                        type: 'GET',
                        success: function (data) {
                            $("#posts").append(data); // response is appended to page
                        }
                    })

                number = number + 6; //number of posts to be ignored so isn't loaded again is increased
            }
        })

    }}


    function like(like, user, post_id)        //function on click with user, post id and like value parammters
    {

        let urrl = 'likes.php?like='+like+'&user='+user+'&post_id='+post_id   // creates the url GET
        $.ajax({    // jquery ajax fuction
            url: urrl,
            type: 'GET',


            success: function(data) // on success it receives a json file
            {   var data = JSON.parse(data)  // parses it
                let post_like  = data.post_id; // each post has a div id = the post id

                $('#'+post_like).text(data.likes); // access and updates numb o likes by div =id
                $("#D"+post_like).text(data.dislikes); // access and updates numb o dislikes by div =id
            }
        })
    }


    function like_comment(like, user, comment_id)        //function on click with user, post id and like value parammters
    {

        let urrl = 'like_comment.php?like='+like+'&user='+user+'&post_id='+comment_id   // creates the url GET
        $.ajax({    // jquery ajax fuction
            url: urrl,
            type: 'GET',


            success: function(data) // on success it receives a json file
            {   var data = JSON.parse(data)  // parses it
                let comment_like  = data.post_id; // each comment has a div class = the post id
                $("#L"+comment_like).text(data.likes); // access and updates numb o likes by div =class
                $("#D"+comment_like).text(data.dislikes); // access and updates numb o dislikes by div =class
            }
        })
    }

    let  click = 0;


    function open_coments(post_id,user) // passes the post id and the user viewing the post
    {

        if (click==1 ) // if icon has being clicked once
        {
            $('div').remove('.comments'); //closes
            $('div').remove('.commenter');//closes

            click=0; // set to zero
            return ;
        }


        let form = // this variable holds the form used to make new comments

            "   <div class='commenter'> <input id='comment"+post_id+"' autocomplete=\"off\" type=\"text\" name=\"comment\" placeholder=\"comente esse meme\" maxlength=\"100\">\n" +
            "    <input type='submit' onclick='make_comment("+post_id+","+ "\""+user+"\""+")'value='commentar'><div><br>\n";

        $('.'+post_id).append(form); // when the comment section opens the form is inserted
        let number = 0; // number of posts loaded
        let url = 'comments.php?post_id=' +post_id+'&user='+user +'&number='+number+"&limit=6";  // url for ajax call
        $.ajax(
            {
                url:url,
                type:'GET',  // type of request
                success: function (data)// on success
                {
                    $('.'+post_id).append(data); //response (comments) is appended to the post
                }
            }
        )

        let comments_left=0;  //variable used to stop request
        number = number + 6; // number of posts loaded
        $(this).on('scroll', function () // every time page is scrolled function is called
        {
            let url = 'comments.php?post_id=' +post_id+'&user='+user +'&number='+number+"&limit=6"; // url to be passed is created
            if ($('.'+post_id).scrollTop() + $('.'+ post_id).innerHeight()>= $('.'+ post_id)[0].scrollHeight) // if pixels scrolled + post hight > hte
            {
                if(comments_left == 1) // if there is no more comments to be loaded
                {
                    return;
                }else
                {


                    $.ajax(
                        {
                            url: url,
                            type: 'GET',
                            success: function (data) {
                                $('.'+post_id).append(data); // response is appended to post
                                if (data=='')
                                {
                                    comments_left = 1;
                                }
                            }

                        }) }

                number = number + 6; //number of posts to be ignored so isn't loaded again is increased
            }

        })
        click =1;
    }

    function delete_post(post_id) // function used to delete posts using ajax
    {
        let url = "deletepost.php?delete=" +post_id; //url

        // ajax call
        $.ajax( {
            url: url,
            type: 'GET',
            success: function()
            {
                $('.' + post_id).css({'display':'none'}); // on success posts disapears
            }
        })

    }
    function delete_comment(comment_id) // function used to delete comments using ajax
    {
        let url = "delete_comment.php?delete_comment=" +comment_id; //url

        $.ajax(  // ajax call
            {
                url: url,
                type: 'GET',
                success: function()
                {
                    $('#' + comment_id).css({'display':'none'}); // on success comment disapears
                }
            })

    }
    function make_comment(post_id,user) // function used to make camment using ajax
    {  let comment = $('#comment'+post_id).val(); // takes value of input "the comment"
        $('#comment' + post_id).val(''); // sets input back to blank
        let url = "comments.php?post_id=" +post_id+ "&user=" +user+ "&comment=" +comment+"&limit=1&number=0"; // creates a url

        $.ajax( // makes ajax call
            {
                url: url,
                type: 'GET',
                success: function(data)
                {

                    if($('div').is('.comments')) // if post already has comments
                    {
                        $(data).insertBefore('.comments:first'); // inserts it before 1st comment
                    }else
                    {
                        $('.' + post_id).append(data); // if not appends it post
                    }
                }
            })


    }

</script>
</div>
</body>


