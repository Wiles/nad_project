function getPostCount()
    {
        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                document.getElementById("not").innerHTML= "Profile Page (" + xmlhttp.responseText + ")";
            }
        }
        xmlhttp.open("GET","/notification.aspx",true);
        xmlhttp.send();
	
	//update once every 60 seconds
        setTimeout("getPostCount()",60000);
    }
