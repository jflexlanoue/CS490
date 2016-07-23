<html>
    <head>
        <title>Question Creation</title>
        <script>
            function ajax_get(url, callback) {
                var xhr;
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
                xhr.open('get', url, true);
                xhr.send();   
            }
            ajax_get('https://web.njit.edu/~glh4/question.php', function(xhr){
                var response = JSON.parse(xhr.responseText);          
                var list = document.getElementById('list');
                var results = Object.keys(response.result);
                for (var i = 1 ; i <= results.length; i++) {
                    list.innerHTML += '<li>' + response.result[i].question + ' --- ' + response.result[i].answer + '</li>';
                }
            });           
        </script>
    </head>
    <body>
        <a href="instructor.php">Return to main page</a><br>
        <center>
            <h1>Question Creation</h1>
            <br>
            <form method="post">
                <label for="question">Question</label><br>
                <textarea rows="10" cols="60" form="form" placeholder="Enter question..." autofocus></textarea><br><br>
                <label for="answer">Answer</label><br>
                <textarea rows="10" cols="60" form="form" placeholder="Enter answer..."></textarea><br><br>
                <input type = "submit" name="btn_post" value="Add" onclick="">
            </form>
            <!--
                <form method="POST">
                    <input id = 'questions' type="text" name="question" placeholder="Enter question..." autofocus>
                    <input type="text" name="answer" placeholder="Enter answer..."></input>
                    <input id = 'add' type="button" value="Add"></input>
                      -->
            <h2>Questions/Answers Bank</h2>
            <br>
            <ol id = 'list'></ol>
        </center>
    </body>
</html>
