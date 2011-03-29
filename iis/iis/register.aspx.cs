using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

using System.Data.Odbc;

namespace iis
{
    public partial class register : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            Page.Header.Title = "SetBook - Register";

            if (!this.IsPostBack)
            {
                Shared.GenerateNumberList(dd_year.Items, 1900, 2100);
                Shared.GenerateNumberList(dd_month.Items, 1, 12);
                Shared.GenerateNumberList(dd_day.Items, 1, 31);
            }
        }

        protected void btn_login_Click(object sender, EventArgs e)
        {
            string name = tb_name.Text;
            string email = tb_email.Text;
            string dateOfBirth = "";
            string firstPassword = tb_password.Text;
            string secondPassword = tb_password_again.Text;

            bool valid = true;

            try
            {
                dateOfBirth = (new DateTime(Int32.Parse(dd_year.SelectedValue), Int32.Parse(dd_month.SelectedValue), Int32.Parse(dd_day.SelectedValue))).ToString("yyyy-MM-dd");

            }
            catch
            {
                valid = false;
                lb_date.Text = "Invalid date.";
            }

            if (name.Length == 0)
            {
                valid = false;
                lb_name.Text = "Name must not be blank.";
            }

            if (firstPassword.Length == 0)
            {
                valid = false;
                lb_password.Text = "Password must not be blank.";
            }
            else if (firstPassword != secondPassword)
            {
                valid = false;
                lb_password.Text = "Passwords must match.";
            }

            if( !Shared.ValidateEmail(email ) )
            {
                valid = false;
                lb_email.Text = "Email is invalid.";
            }

            if( valid )
            {
                string query = "INSERT INTO USERS (name,email, password,dateOfBirth,lastActive) VALUES ( '" + name + "','" + email + "','" + Shared.HashPassword(firstPassword) + "','" + dateOfBirth + "','" + DateTime.Now.ToString("yyyy-MM-dd") + "' );";

                OdbcConnection conn = null;
                OdbcCommand cmd;
                OdbcDataReader reader;

                try
                {
                    conn = new OdbcConnection(Shared.ConnectionString());
                    cmd = new OdbcCommand(query);
                    cmd.Connection = conn;
                    conn.Open();

                    if (cmd.ExecuteNonQuery() == 0)
                    {
                        conn.Close();
                        lb_error.Text = "Failed to add user.";
                        return;
                    }

                    query = "SELECT id FROM users WHERE email='"+email+"' AND password='"+Shared.HashPassword(firstPassword)+"' LIMIT 1";
                    cmd = new OdbcCommand(query);
                    cmd.Connection = conn;
                    reader = cmd.ExecuteReader();

                    if( reader.HasRows )
                    {
                        Session["user_id"] = reader["id"];
                        Response.Redirect("profile.aspx");
                    }
                    else
                    {
                        Response.Redirect("index.aspx");
                    }                    
                }
                catch( Exception ex )
                {
                    if( conn != null )
                    {
                        conn.Close();
                    }
                    lb_error.Text = ex.Message;
                    return;
                }

                Response.Redirect("profile.aspx");

                conn.Close();
            }

        }
    }
}