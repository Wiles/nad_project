using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

using System.Data.Odbc;

namespace iis
{
    public partial class index : System.Web.UI.Page
    {
        OdbcConnection conn;
        OdbcDataReader reader;
        OdbcCommand cmd;

        protected void Page_Load(object sender, EventArgs e)
        {
            if (Session["user_id"] != null)
            {
                Response.Redirect("profile.aspx");
            }
            Page.Header.Title = "SetBook - Login";
        }

        protected void btn_login_Click(object sender, EventArgs e)
        {
            string password = Shared.HashPassword(tb_password.Text);
            string email = tb_username.Text;

            string query = "SELECT id from Users WHERE email='" + email + "' AND password='" + password + "'";
            
            try
            {
                string s = Shared.ConnectionString();
                conn = new OdbcConnection(Shared.ConnectionString());
                cmd = new OdbcCommand(query);
                cmd.Connection = conn;
                conn.Open();

                reader = cmd.ExecuteReader();
            }
            catch
            {
                //TODO
                conn.Close();
                lb_error.Text = "Invalid Email or Password.";
                return;
            }

            if (reader.HasRows == true)
            {
                conn.Close();
                Session["user_id"] = reader["id"];
                Response.Redirect("profile.aspx");
            }
            else
            {
                lb_error.Text = "Invalid Email or Password.";
                return;
            }
        }
    }
}