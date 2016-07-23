<!DOCTYPE html>
<html>
    <head>
    <title>CS 490</title>
    <script>
        function ajax_post(url, callback) {
            var xhr;
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            if(typeof XMLHttpRequest !== 'undefined') 
                xhr = new XMLHttpRequest();
            else {
                var versions = ["Microsoft.XmlHttp",    // Support for older Internet Explorer versions (older than IE7)...
                                "MSXML2.XmlHttp",
                                "MSXML2.XmlHttp.3.0",
                                "MSXML2.XmlHttp.4.0",
                                "MSXML2.XmlHttp.5.0"];
                for(var i = 0; i < versions.length; i++){
                    try {
                        xhr = new ActiveXObject(versions[i]);
                        break;
                    }
                    catch(e){}
                }
            }         
            xhr.onreadystatechange = function(){         
                if((xhr.status !== 200) || (xhr.readyState < 4)) 
                    return;
                callback(xhr);
            };         
            xhr.open('post', url, true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("username="+username+"&password="+password);
            document.getElementById("message").innerHTML = ".................";         // indication of processing        
        }             
    </script>
    </head>
    <body>
    <a href="create_user.php">Create an account</a><br>
    <center>
        <h1>CS 490</h1>
        <h2>Login</h2>
        <input id="username" type="text" name="username" placeholder="username" autofocus><br><br>
        <input id= "password" type="password" name="password" placeholder="password"><br><br>
        <input name="loginButton" type="submit" value="Login" onclick="
            ajax_post('login.php', function(xhr){
                // var response = JSON.parse(xhr.responseText);
                response = xhr.responseText;
                // alert(response);
                if (response == 'instructor')
                    document.location.replace('instructor.php');
                else if (response == 'student')
                    document.location.replace('student.php');
                else
                    document.getElementById('message').innerHTML = 'The username and password you entered do not match.';
            })"><br><br> 
        <div id="message"></div>
    </center>
    </body>
</html>