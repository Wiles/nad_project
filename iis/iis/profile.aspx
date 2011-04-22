<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="profile.aspx.cs" Inherits="iis.profile" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head id="Head1" runat="server">
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="notify.js"></script>
    <script type="text/javascript" >
        function toggleComments(id) {
            if (document.getElementById("c" + id).style.display == "block") {
                document.getElementById("c" + id).style.display = "none";
                document.getElementById("a" + id).innerHTML = "Show Comments";
            }
            else {
                document.getElementById("c" + id).style.display = "block";
                document.getElementById("a" + id).innerHTML = "Hide Comments";
            }
        }

        function submitVote(user, post, type) {
            document.getElementById("cuserid").value = user;
            document.getElementById("cpostid").value = post;
            document.getElementById("ctype").value = type;
            document.voteForm.submit();
        }
    </script>
</head>

<body onload="getPostCount()">
    <!--#include FILE="private_header.html" -->
    <form id="form1" runat="server">
    <h2><asp:Label ID="lb_userid" runat="server" Text="Label"></asp:Label></h2>
    <asp:Panel ID="Panel1" runat="server">
        <asp:TextBox ID="TextBoxPost" runat="server" Height="81px" Width="311px" 
            MaxLength="1024" TextMode="MultiLine"></asp:TextBox>
        <br />
        <asp:Button ID="ButtonPost" runat="server" Text="Post" Width="104px" 
            onclick="Button1_Click" />
    </asp:Panel>
    <asp:HiddenField ID="HiddenProfileID" runat="server" value ="" />
    <asp:HiddenField ID="HiddenParentID" runat="server" value="" />
    <asp:Panel ID="PanelPosts" runat="server">
    </asp:Panel>
    </form>
    <!--#include FILE="private_footer.html" -->
    </body>
</html>
