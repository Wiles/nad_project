﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

using System.Data.Odbc;

namespace iis
{
    public partial class profile : System.Web.UI.Page
    {
        /// <summary>
        /// Page_Load
        /// Handles:
        /// -Profile Name
        /// -Friend status
        /// -
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        protected void Page_Load(object sender, EventArgs e)
        {
            OdbcConnection conn = null;
            OdbcCommand cmd;
            OdbcDataReader reader;
            string query = string.Empty;

            Page.Header.Title = "SetBook - Profile";
            if (Session["user_id"] == null)
            {
                Response.Redirect("index.aspx");
            }
            else if (string.Compare((string)Session["user_id"], (string)Request["id"]) == 0)
            {
                Response.Redirect("profile.aspx");
            }
            else
            {
                string id = string.Empty;
                string profileid = (Request["id"] == null) ? (string)Session["user_id"] : (string)Request["id"];

                // TODO: use parameters
                query = "SELECT name FROM users WHERE id='" +  profileid + "';";
                try
                {
                    conn = new OdbcConnection(Shared.ConnectionString());
                    cmd = new OdbcCommand(query);
                    cmd.Connection = conn;
                    conn.Open();

                    id = (string)cmd.ExecuteScalar();
                }
                catch
                {
                    //TODO
                    conn.Close();
                    //lb_error.Text = "Invalid Email or Password.";
                    return;
                }
                lb_userid.Text = (id);
            }


            if (!this.IsPostBack)
            {
                GetPosts();
            }
        }

        /// <summary>
        /// Post Button
        /// Handles:
        /// -posting on main page
        /// -
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        protected void Button1_Click(object sender, EventArgs e)
        {
            OdbcConnection conn = null;
            OdbcCommand cmd;
            OdbcDataReader reader;
            string query = string.Empty;

            string profileid = (Request["id"] == null) ? (string)Session["user_id"] : (string)Request["id"] ;

            // TODO: use parameters
            query = "INSERT INTO post (parent, time, text, userid, profileid) VALUES ( NULL, NOW(), '"
                + TextBoxPost.Text + "', '"
                + Session["user_id"].ToString() + "', '"
                + profileid.ToString() + "' )";

            try
            {
                conn = new OdbcConnection(Shared.ConnectionString());
                cmd = new OdbcCommand(query);
                cmd.Connection = conn;
                conn.Open();

                cmd.ExecuteNonQuery();
            }
            catch
            {
                //TODO
                conn.Close();
                //lb_error.Text = "Invalid Email or Password.";
                return;
            }

            // clear post box
            TextBoxPost.Text = "";
            GetPosts();
        }

        /// <summary>
        /// GetPosts
        /// Handles:
        /// -writing wall posts to page
        /// </summary>
        private void GetPosts()
        {
            OdbcConnection conn = null;
            OdbcCommand cmd, cmd2, cmd3;
            OdbcDataReader reader, reader2, reader3;
            string query = string.Empty;
            string profileid = (Request["id"] == null) ? (string)Session["user_id"] : (string)Request["id"];

            try
            {
                // TODO: use parameters
                query = "SELECT profileid, userid, text, time, name, post.id FROM post LEFT JOIN (users) ON (post.userid=users.id) WHERE profileid='"
                    + profileid + "' AND parent IS NULL ORDER BY time desc";

                conn = new OdbcConnection(Shared.ConnectionString());
                cmd = new OdbcCommand(query);
                cmd.Connection = conn;
                conn.Open();

                reader = cmd.ExecuteReader();

               // PanelPosts.Controls.Add(new LiteralControl("<div class=\"\""));

                while (reader.Read())
                {

                    query = "SELECT COUNT(*) FROM vote WHERE postid='" + reader.GetInt32(5).ToString() + "' AND type='like'";
                    cmd2 = new OdbcCommand(query);
                    cmd2.Connection = conn;

                    reader2 = cmd2.ExecuteReader();
                    reader2.Read();

                    PanelPosts.Controls.Add(new LiteralControl("<a href=\"profile.aspx?id=" + reader.GetInt32(1).ToString() + "\">" + reader.GetString(4) + "</a>"));
                    PanelPosts.Controls.Add(new LiteralControl("<br/>"));
                    PanelPosts.Controls.Add(new LiteralControl("<div class=\"wrap\">"));
                    PanelPosts.Controls.Add(new LiteralControl(Server.HtmlEncode(reader.GetString(2))));
                    PanelPosts.Controls.Add(new LiteralControl("<p class=\"postfoot\">" + Server.HtmlEncode(reader.GetDateTime(3).ToString()) + "</p>"));
                    PanelPosts.Controls.Add(new LiteralControl("<p class=\"postfoot\">"));
                    PanelPosts.Controls.Add(new LiteralControl("<a href=\"\">" + reader2.GetInt32(0).ToString() + "&nbsp;Likes<a> - "));

                    query = "SELECT COUNT(*) FROM vote WHERE postid='" + reader.GetInt32(5).ToString() + "' AND type='dislike'";
                    cmd2 = new OdbcCommand(query);
                    cmd2.Connection = conn;

                    reader2 = cmd2.ExecuteReader();
                    reader2.Read();

                    PanelPosts.Controls.Add(new LiteralControl("<a href=\"\">" + reader2.GetInt32(0).ToString() + "&nbsp;Dislikes<a> - "));
                    PanelPosts.Controls.Add(new LiteralControl("<a href=\"\">" + "Show Comments<a>"));
                    PanelPosts.Controls.Add(new LiteralControl("</p>"));
                    PanelPosts.Controls.Add(new LiteralControl("</div>"));
                    PanelPosts.Controls.Add(new LiteralControl("<hr />"));

                    PanelPosts.Controls.Add(new LiteralControl("<div class=\"comments\">"));

                    query = "SELECT profileid, userid, text, time, name, post.id FROM post LEFT JOIN (users) ON (post.userid=users.id) WHERE profileid='"
                    + profileid + "' AND parent='" + reader.GetInt32(5).ToString() + "' ORDER BY time desc";
                    cmd3 = new OdbcCommand(query);
                    cmd3.Connection = conn;

                    reader3 = cmd3.ExecuteReader();

                    while (reader3.Read())
                    {
                        query = "SELECT COUNT(*) FROM vote WHERE postid='" + reader.GetInt32(5).ToString() + "' AND type='like'";
                        cmd2 = new OdbcCommand(query);
                        cmd2.Connection = conn;

                        reader2 = cmd2.ExecuteReader();
                        reader2.Read();

                        PanelPosts.Controls.Add(new LiteralControl("<a href=\"profile.aspx?id=" + reader3.GetInt32(1).ToString() + "\">" + reader3.GetString(4) + "</a>"));
                        PanelPosts.Controls.Add(new LiteralControl("<br/>"));
                        PanelPosts.Controls.Add(new LiteralControl("<div class=\"wrap\">"));
                        PanelPosts.Controls.Add(new LiteralControl(Server.HtmlEncode(reader3.GetString(2))));
                        PanelPosts.Controls.Add(new LiteralControl("<p class=\"postfoot\">" + Server.HtmlEncode(reader3.GetDateTime(3).ToString()) + "</p>"));
                        PanelPosts.Controls.Add(new LiteralControl("<p class=\"postfoot\">"));
                        PanelPosts.Controls.Add(new LiteralControl("<a href=\"\">" + reader2.GetInt32(0).ToString() + "&nbsp;Likes<a> - "));

                        query = "SELECT COUNT(*) FROM vote WHERE postid='" + reader3.GetInt32(5).ToString() + "' AND type='dislike'";
                        cmd2 = new OdbcCommand(query);
                        cmd2.Connection = conn;

                        reader2 = cmd2.ExecuteReader();
                        reader2.Read();

                        PanelPosts.Controls.Add(new LiteralControl("<a href=\"\">" + reader2.GetInt32(0).ToString() + "&nbsp;Dislikes<a> - "));
                        PanelPosts.Controls.Add(new LiteralControl("<a href=\"\">" + "Show Comments<a>"));
                        PanelPosts.Controls.Add(new LiteralControl("</p>"));
                        PanelPosts.Controls.Add(new LiteralControl("</div>"));
                        PanelPosts.Controls.Add(new LiteralControl("<hr />"));
                    }

                    PanelPosts.Controls.Add(new LiteralControl("</div>"));
                }

                //PanelPosts.Controls.Add(new LiteralControl(""));
            }
            catch
            {
                //TODO
                conn.Close();
                //lb_error.Text = "Invalid Email or Password.";
                return;
            }
            conn.Close();
        }

    }
}