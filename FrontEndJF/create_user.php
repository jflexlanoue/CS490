<html>
    <head>
        <title>Registration</title>
        <script>

            function ajax_post(url, callback) {
            var xhr;
            var username = document.getElementById("username").value;
            var password = document.getElementById("password").value;
            var permission;
            if (document.getElementById('instructor').checked)
                permission = "instructor";
            else
                permission = "student";
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
        <a href="index.php">Return to login page</a><br>
        <center>
            <h2>Registration</h2>
                <input id="username" type="text" name="username" placeholder="username" autofocus><br><br>
                <input id= "password" type="password" name="password" placeholder="password"><br><br>
                <input id="student" type="radio" name="accType" value="Student" checked> Student<br>
                <input id= "instructor" type="radio" name="accType" value="Instructor"> Instructor<br><br>
                <input name="loginButton" type="submit" value="Create new account" onclick="
                    ajax_post('create_user_frontback.php', function(xhr){
                    // var response = JSON.parse(xhr.responseText);
                        response = xhr.responseText;
                        document.getElementById('message').innerHTML = response;
                    })"><br><br>
                <div id="message"></div>
        </center>
    </body>
</html>