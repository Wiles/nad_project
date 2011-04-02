using System;
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
            OdbcCommand cmd;
            OdbcDataReader reader;
            string query = string.Empty;
            string profileid = (Request["id"] == null) ? (string)Session["user_id"] : (string)Request["id"];

            try
            {

                query = "SELECT profileid, userid, text, time, name, post.id FROM post LEFT JOIN (users) ON (post.userid=users.id) WHERE profileid='"
                    + profileid + "' AND parent IS NULL ORDER BY time desc";

                conn = new OdbcConnection(Shared.ConnectionString());
                cmd = new OdbcCommand(query);
                cmd.Connection = conn;
                conn.Open();

                reader = cmd.ExecuteReader();

               // PanelPosts.Controls.Add(new LiteralControl("<div class="));

                while (reader.Read())
                {
                    PanelPosts.Controls.Add(new LiteralControl("<a href=\"profile.aspx?id=" + reader.GetString(1) + "\">" + reader.GetString(4) + "</a>"));
                    PanelPosts.Controls.Add(new LiteralControl("<br/>"));
                    PanelPosts.Controls.Add(new LiteralControl("<div class=\"wrap\">"));
                    PanelPosts.Controls.Add(new LiteralControl(Server.HtmlEncode(reader.GetString(2))));
                    PanelPosts.Controls.Add(new LiteralControl(""));
                    PanelPosts.Controls.Add(new LiteralControl(""));
                    PanelPosts.Controls.Add(new LiteralControl(""));
                    PanelPosts.Controls.Add(new LiteralControl("</div>"));
                    PanelPosts.Controls.Add(new LiteralControl("<hr />"));
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
        }

    }
}