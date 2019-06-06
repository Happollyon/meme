function checkUser(user)
{
    let ajaxRequest;
    ajaxRequest = new   XMLHttpRequest();
    ajaxRequest.onreadystatechange = function ()
    { if(ajaxRequest.readyState == 4)
        { let ajaxDisplay = Document.getElementById('signup_flex') ;
            ajaxDisplay.innerHTML =ajaxRequest.responseText;

        }
        
    }

    let queryString =  "&user=" + user.value;
    ajaxRequest.open("GET", "register.php" + queryString, true);
    ajaxRequest.send(null);
}
