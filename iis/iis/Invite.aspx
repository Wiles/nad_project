<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="invite.aspx.cs" Inherits="iis.invite1" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="refresh" content="5;url=/profile.aspx">
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="notify.js"></script>
</head>
<bodyonload="getPostCount()">>
    <!--#include FILE="private_header.html" --> 
    <form id="form1" runat="server">
    <div>
        <div class="error" id ="error">
        <asp:Label ID="lb_error" runat="server" Text=" "></asp:Label></div><br />
        <asp:Label ID="lb_message" runat="server"></asp:Label><br />
        You should be redirected back to your profile page in 5 seconds.
    </div>
    </form>
    <!--#include FILE="private_footer.html" --> 
</body>
</html>
