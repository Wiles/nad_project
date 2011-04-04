<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="edit.aspx.cs" Inherits="iis.edit" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="notify.js"></script>
</head>
<body>   
    <!--#include FILE="private_header.html" --> 
    <form id="form1" runat="server">
    <div>
    <div class="error" id ="error">
        <asp:Label ID="lb_error" runat="server" Text=" "></asp:Label></div>
        
    old Password*:<br />
    <asp:TextBox ID="tb_old_password" textmode="password" runat="server"></asp:TextBox>   
    <asp:Label ID="lb_old_password" runat="server"></asp:Label>
    <br />
    Name*:<br />
    <asp:TextBox ID="tb_name" runat="server"></asp:TextBox>   
    <asp:Label ID="lb_name" runat="server"></asp:Label>
    <br />
    E-mail*:<br />
    <asp:TextBox ID="tb_email" runat="server"></asp:TextBox>   
    <asp:Label ID="lb_email" runat="server"></asp:Label>
    <br />
    New Password:<br />
    <asp:TextBox ID="tb_new_password" textmode="password" runat="server"></asp:TextBox>   
    <asp:Label ID="lb_new_password" runat="server"></asp:Label>
   
        <br />
    Again:<br />
    <asp:TextBox ID="tb_new_password_again" textmode="password" runat="server"></asp:TextBox>
        <br />
    Date of Birth*:<br />
    Year:<asp:DropDownList ID="dd_year" runat="server">
        </asp:DropDownList>
    Month:<asp:DropDownList ID="dd_month" runat="server">
        </asp:DropDownList>
        Day:<asp:DropDownList ID="dd_day" runat="server">
        </asp:DropDownList><asp:Label ID="lb_date" runat="server"></asp:Label>
           <br />
           <br />
    <asp:Button ID="btn_edit" runat="server" Text="Update" onclick="btn_edit_Click" />
    </div>
    </form>
    <!--#include FILE="private_footer.html" --> 
</body>
</html>
