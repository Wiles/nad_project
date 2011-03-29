<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="index.aspx.cs" Inherits="iis.index" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <form id="form1" runat="server">
    <!--#include FILE="public_header.html" --> 
    <div>
    <div class="error" id ="error">
        <asp:Label ID="lb_error" runat="server" Text=" "></asp:Label></div>
    E-mail:<br />
    <asp:TextBox ID="tb_username" runat="server"></asp:TextBox><br />
    Password:<br />
    <asp:TextBox ID="tb_password" textmode="password" runat="server"></asp:TextBox>
        <br />
        <br />
    <asp:Button ID="btn_login" runat="server" Text="Login" onclick="btn_login_Click" />
    </div>
    <!--#include FILE="public_footer.html" --> 
    </form>
</body>
</html>
