using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

using System.Data.Odbc;

namespace iis
{
    public partial class Notification : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            Response.Clear();

            OdbcConnection conn = null;
            OdbcCommand cmd;
            OdbcDataReader reader;

            string newPosts = "0";

            if (Session["User_ID"] != null)
            {
                string query = "SELECT lastactive FROM users where id='" + Session["user_id"] + "'";
                try
                {
                    conn = new OdbcConnection(Shared.ConnectionString());
                    conn.Open();

                    cmd = new OdbcCommand(query);
                    cmd.Connection = conn;
                    reader = cmd.ExecuteReader();

                    if (reader.HasRows)
                    {
                        query = "SELECT count(*) FROM post WHERE parent IS NULL AND time > '" + reader[0] + "' AND profileid='" + Session["user_id"] + "'";

                        cmd = new OdbcCommand(query);
                        cmd.Connection = conn;
                        reader = cmd.ExecuteReader();
                        if (reader.HasRows)
                        {
                            newPosts = reader[0].ToString();
                        }
                    }
                }
                catch
                {
                }
            }
            Response.Write(newPosts);
            Response.End();
        }
    }
}