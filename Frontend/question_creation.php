<html>
    <head>
        <title>Question Creation</title>
        <script>
            function ajax_get(url, callback) {
                var xhr;
                if(typeof XMLHttpRequest !== 'undefined') 
                    xhr = new XMLHttpRequest();
                else {
                var versions = ["Microsoft.XmlHttp",    // Support for older Internet Explorer versions (versions older than IE7)...
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
           
            function ajax_post(url, callback) {
                var xhr;
                var question = document.getElementById("question").value;
                var answer = document.getElementById("answer").value;
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
                xhr.send("question="+question+"&answer="+answer);
                document.getElementById("message").innerHTML = ".................";         // indication of processing        
            }             
        </script>
    </head>
    <body>
        <a href="instructor.php">Return to main page</a><br>
        <center>
            <h1>Question Creation</h1>
            <br>
                <label for="question">Question</label><br>
                <textarea id="question" rows="10" cols="60" form="form" placeholder="Enter question..." autofocus></textarea><br><br>
                <label for="answer">Answer</label><br>
                <textarea id = "answer" rows="10" cols="60" form="form" placeholder="Enter answer..."></textarea><br><br>
                <input type = "submit" name="btn" value="Add" onclick="

                ajax_post('question_creation_frontback.php', function(xhr){
                    response = xhr.responseText;
                        document.getElementById('message').innerHTML = response;

                });


                ajax_get('https://web.njit.edu/~glh4/question.php', function(xhr){
                    var response = JSON.parse(xhr.responseText);       
                    var list = document.getElementById('defaultList');
                    var results = Object.keys(response.result);
                    list.innerHTML = '';                                    // Clear list; otherwise, updated list appends to older list--needs testing
                    for (var i = 1 ; i <= results.length; i++) {
                        list.innerHTML += '<li>' + response.result[i].question + ' --- ' + response.result[i].answer + '</li>';
                    }      
                });"><br><br>
            <!--
                <form method="POST">
                    <input id = 'questions' type="text" name="question" placeholder="Enter question..." autofocus>
                    <input type="text" name="answer" placeholder="Enter answer..."></input>
                    <input id = 'add' type="button" value="Add"></input>
                      -->
            <div id="message"></div>          
            <h2>Questions/Answers Bank</h2>
            <ol id = 'defaultList'></ol>

        </center>
    </body>
</html>
