using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

using System.Data.Odbc;

namespace iis
{
    public partial class Friends : System.Web.UI.Page
    {
        OdbcConnection conn;
        OdbcDataReader reader;
        OdbcCommand cmd;

        protected void Page_Load(object sender, EventArgs e)
        {
            if (Session["user_id"] == null)
            {
                Response.Redirect("login.aspx");
            }
            Page.Header.Title = "SetBook - Login";

            lb_friends.Text = get_friends();
            lb_invited.Text = get_invited();
            lb_invites.Text = get_invites();


            //get list of friends form server
        }

        protected string get_invites()
        {
            string invite = "";
            OdbcDataReader reader2;
            OdbcCommand cmd2;

            int count = 0;
            string query = "SELECT firstfriend, secondfriend, status FROM friends WHERE (status = 'pending' AND (firstFriend='" + Session["user_id"] + "' OR secondFriend='" + Session["user_id"] + "'))";
            string query2 = "";

            conn = new OdbcConnection(Shared.ConnectionString());
            cmd = new OdbcCommand(query);
            cmd.Connection = conn;
            conn.Open();

            reader = cmd.ExecuteReader();
            while (reader.Read())
            {
                string read = reader[1].ToString();
                string id = Session["user_id"].ToString();

                //user id is firstfriend it means they made request
                if (read == id)
                {
                    query2 = "SELECT name, email, dateOfBirth FROM users WHERE id= '" + reader[0] + "' LIMIT 1";

                    cmd2 = new OdbcCommand(query2);
                    cmd2.Connection = conn;

                    reader2 = cmd2.ExecuteReader();

                    //if name cannot be retrieved
                    if (reader2.HasRows == false)
                    {
                        invite += "Failed to get username for user" + reader[1] + "<br\\>";
                    }

                    //name found
                    else
                    {
                        invite +=
                            "<form action = '/friends.php' method = 'POST' />" + 
                            "<fieldset >" + 
                            "<legend> <a href = \"profile.aspx?id=" + reader[0] + 
                            "\" >"+ reader2[0] +"</a></legend>" + reader2[1] + "<br \\>Birth date:" + reader2[2] +
                            "<br \\>" + "<input type=\"hidden\"value='" + reader[0] +
                            "' name='action_id'/>" + "<input type=\"submit\"value=\"Accept Request\" name='accept'/>" +
                            "<input type=\"submit\" value=\"Decline Request\"name='decline' />" + 
                            "</fieldset> <br \\>" + "</form>";
                    }
                }
            }
            return invite;
        }

        protected string get_invited()
        {
            string invite = "";
            OdbcDataReader reader2;
            OdbcCommand cmd2;

            int count = 0;
            string query = "SELECT firstfriend, secondfriend, status FROM friends WHERE (status = 'pending' AND (firstFriend='" + Session["user_id"] + "' OR secondFriend='" + Session["user_id"] + "'))";
            string query2 = "";

            conn = new OdbcConnection(Shared.ConnectionString());
            cmd = new OdbcCommand(query);
            cmd.Connection = conn;
            conn.Open();

            reader = cmd.ExecuteReader();
            while (reader.Read())
            {
                string read = reader[0].ToString();
                string id = Session["user_id"].ToString();

                //user id is firstfriend it means they made request
                if (read == id)
                {
                    query2 = "SELECT name, email, dateOfBirth FROM users WHERE id= '" + reader[1] + "' LIMIT 1";

                    cmd2 = new OdbcCommand(query2);
                    cmd2.Connection = conn;

                    reader2 = cmd2.ExecuteReader();

                    //if name cannot be retrieved
                    if (reader2.HasRows == false)
                    {
                        invite += "Failed to get username for user" + reader[1] + "<br\\>";
                    }

                    //name found
                    else
                    {
                        invite += "<fieldset ><legend> <a href = \"profile.aspx?id=" + reader[1] + "\" >" + reader2[0] + "</a></legend> </fieldset> <br \\>";
                    }
                }
            }

            
            return invite;
        }

        protected string get_friends()
        {
            OdbcDataReader reader2;
            OdbcCommand cmd2;

            int count = 0;
            string query = "SELECT firstfriend, secondfriend, status FROM friends WHERE (status = 'friends' AND (firstFriend='"+ Session["user_id"] + "' OR secondFriend='" + Session["user_id"] + "'))";
            string query2 = "";
            string friend = "";

            conn = new OdbcConnection(Shared.ConnectionString());
            cmd = new OdbcCommand(query);
            cmd.Connection = conn;
            conn.Open();

            reader = cmd.ExecuteReader();
            while (reader.Read())
            {
                //int id = Convert.ToInt32( Session["user_id"].ToString());
                string read = reader[0].ToString();
                string id = Session["user_id"].ToString();

                //user id is firstfriend
                if (read == id)
                {
                    query2 = "SELECT name, email, dateOfBirth FROM users WHERE id= '" + reader[1] + "' LIMIT 1";

                    cmd2 = new OdbcCommand(query2);
                    cmd2.Connection = conn;

                    reader2 = cmd2.ExecuteReader();

                    //if name cannot be retrieved
                    if (reader2.HasRows == false)
                    {
                        friend += "Failed to get username for user" + reader[1] + "<br\\>";
                    }

                    //name found
                    else
                    {
                        friend += "<fieldset ><legend> <a href = \"profile.aspx?id=" + reader[1] + "\" >" + reader2[0] + "</a></legend>" + reader2[1] + "<br \\> Birth date:" + reader2[2] + "</fieldset> <br \\>";

                    }


                }

                //user id is second friend
                else
                {
                    query2 = "SELECT name, email, dateOfBirth FROM users WHERE id= '" + reader[0] + "' LIMIT 1";

                    cmd2 = new OdbcCommand(query2);
                    cmd2.Connection = conn;

                    reader2 = cmd2.ExecuteReader();

                    //if name cannot be retrieved
                    if (reader2.HasRows == false)
                    {
                        friend += "Failed to get username for user" + reader[0] + "<br\\>";
                    }

                    //name found
                    else
                    {
                        friend += "<fieldset ><legend> <a href = \"profile.aspx?id=" + reader[0] + "\" >" + reader2[0] + "</a></legend>" + reader2[1] + "<br \\> Birth date:" + reader2[2] + "</fieldset> <br \\>";

                    }
                }
                count++;
            }
            if (count == 0)
            {
                friend = "No confirmed Friends <br/>";
            }
            return friend;
        }
    }
}