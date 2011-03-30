﻿<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="register.aspx.cs" Inherits="iis.register" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <form id="form1" runat="server">
    <!--#include FILE="public_header.html" --> 
    <div>
    <div class="error" id ="error">
        <asp:Label ID="lb_error" runat="server" Text=" "></asp:Label></div>

    Name:<br />
    <asp:TextBox ID="tb_name" runat="server"></asp:TextBox>   
    <asp:Label ID="lb_name" runat="server"></asp:Label>
    <br />
    E-mail:<br />
    <asp:TextBox ID="tb_email" runat="server"></asp:TextBox>   
    <asp:Label ID="lb_email" runat="server"></asp:Label>
    <br />
    Password:<br />
    <asp:TextBox ID="tb_password" textmode="password" runat="server"></asp:TextBox>   
    <asp:Label ID="lb_password" runat="server"></asp:Label>
   
        <br />
    Again:<br />
    <asp:TextBox ID="tb_password_again" textmode="password" runat="server"></asp:TextBox>
        <br />
        <br />
    Date of Birth:<br />
    Year:<asp:DropDownList ID="dd_year" runat="server">
        </asp:DropDownList>
    Month:<asp:DropDownList ID="dd_month" runat="server">
        </asp:DropDownList>
        Day:<asp:DropDownList ID="dd_day" runat="server">
        </asp:DropDownList><asp:Label ID="lb_date" runat="server"></asp:Label>
        <br />
        <br />
    <asp:Button ID="btn_register" runat="server" Text="Register" onclick="btn_login_Click" />
    </div>
    <!--#include FILE="public_footer.html" --> 
    </form>
</body>
</html>
