function $(e) {
    return document.getElementById(e);
}

var handler = function (event) {
    try {
        event.preventDefault();
        event.stopPropagation();
        var fileIn = document.getElementById('fileIn');
        var data = new FormData();
        data.append('ajax', true);
        for (var i = 0; i < fileIn.files.length; i++) {
            //console.log(fileIn.files[i].name + " --> " + fileIn.files[i].size);
            data.append('file[]', fileIn.files[i]);
        }

        var req = null;

        if (window.XMLHttpRequest)
        {
            req = new XMLHttpRequest();
        }
        else
        {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }

        req.upload.addEventListener('progress', function (event) {
            if (event.lengthComputable) {
                var pr = event.loaded / event.total;
                var progress = document.getElementById('progress_bar');
                progress.innerHTML = "";
                progress.style.opacity = "1";
                progress.innerHTML = Math.round(pr * 100) + "%";
            }

        });

        req.addEventListener('readystatechange', function () {
            if (this.readyState == 4 && this.status == 200) {
                var resp = eval(this.response);
                console.log(resp.toString());
                var x = $('uploaded');
                var folder = $('folder');
                //$('log_header').style.display = 'block';
                for (var i = 0; i < resp.length; i++) {
                    console.log(resp[i]);
                    if(resp[i]!="already_here"){
                        var y = document.createElement("img");
                        var div = document.createElement('div');
                        div.classList.add('file_cont');
                        var rm = document.createElement('span');
                        rm.innerHTML = "&#10005;";
                        rm.classList.add('rm');
                        var link = document.createElement("a");
                        link.setAttribute('href','./files/' + resp[i].toString());
                        link.classList.add('file_ls');
                        link.innerHTML = resp[i].toString();
                        div.appendChild(link);
                        div.appendChild(rm);
                        folder.appendChild(div);
                        y.setAttribute('src', './files/' + resp[i].toString());
                        y.classList.add('shownfile');
                        x.appendChild(y);
                        //$("log").innerHTML += "<div class='filename'>" + resp[i] + "</div>";
                        $('count_holder').innerHTML = parseInt($('count_holder').innerHTML) + 1;
                    }else{
                        $('progress_bar').innerHTML="same file can't be uploaded twice";
                        $('progress_bar').style.opacity = "1"
                        setTimeout(function () {
                            $("progress_bar").style.opacity = '0';
                        },1000);
                    }

                }

            }

        });

        req.upload.addEventListener('load', function (e) {
            $('progress_bar').style.opacity = '0';
        });

        req.upload.addEventListener('error', function () {
            alert("error uploading file");
        });

        req.open('POST', 'index.php', true);
        //req.setRequestHeader("Cache-Control", "no-cache");
        req.send(data);
    } catch (e) {
        alert(e.which.toString());
    }
}
function clearCookie() {
    document.cookie.split(";").forEach(function (c) {
        document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
    });
    alert("all cookies cleared");
    location.reload();
}
function clearDir() {
    var dlt = new XMLHttpRequest();
    var data = new FormData();
    data.append("deleteFiles",true);
    dlt.open("POST","index.php",true);
    dlt.send(data);
    document.cookie.split(";").forEach(function (c) {
        document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
    });
    location.reload();
}
window.addEventListener('load', function (event) {
    var submit_btn = document.getElementById('upload');
    submit_btn.addEventListener('click', handler);
    $("clrck").addEventListener('click', clearCookie);
    $("filedlt").addEventListener('click', clearDir);

    /*$("clearLog").onclick= function () {
        var l = document.getElementById("log");
        while(l.hasChildNodes()){
            l.removeChild(l.firstChild);
        }
    }*/
});
/**
 * Created by Sudipta on 3/28/2017.
 */








