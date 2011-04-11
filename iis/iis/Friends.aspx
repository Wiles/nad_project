<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="Friends.aspx.cs" Inherits="iis.Friends" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
     <!--<title>SetBook - <?php echo $user; ?> - Friends</title>-->

    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="notify.js">
     function load_profile(id)
        {
            document.getElementsByName(profileload).value = id;
        }
    </script>
</head>
<body onload="getPostCount()">
    <!--#include FILE="private_header.html" --> 
    <form id="form1" runat="server">
    <div>

    <input type="hidden" name="profileload" value="0"/>
    <asp:Label ID="lb_error" runat="server" ></asp:Label>

    Friend Requests:<br />
    <asp:Label ID="lb_invited" runat="server" ></asp:Label>

    Requested Friends:<br />
    <asp:Label ID="lb_invites" runat="server" ></asp:Label>

    Friends:<br />
    <asp:Label ID="lb_friends" runat="server" ></asp:Label>
 
    
    </div>
    </form>    
    <!--#include FILE="private_footer.html" --> 
</body>
</html>
