
function getSelectedBox(checkForm) {
    var selectedBox = [];

    var inputFields = checkForm.getElementsByTagName('input');

    for(var i=0; i<inputFields.length; i++) {
        if(inputFields[i].type == 'checkbox' && inputFields[i].checked == true)
            selectedBox.push(inputFields[i].value);
    }

    return selectedBox;
}

function newxhr() {
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
    return xhr;
}

function ajax_get(url, callback) {
    var xhr = newxhr();
    xhr.onreadystatechange = function(){
        if((xhr.status !== 200) || (xhr.readyState < 4))
            return;
        callback(xhr);
    };
    xhr.open('get', url, true);
    xhr.send();
}

function ajax_post(url, callback) {
    var xhr = newxhr();
    xhr.onreadystatechange = function(){
        if((xhr.status !== 200) || (xhr.readyState < 4))
            return;
        callback(xhr);
    };
    xhr.open('post', url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("selectedBox="+selectedBox);
    document.getElementById("message").innerHTML = ".................";         // indication of processing
}