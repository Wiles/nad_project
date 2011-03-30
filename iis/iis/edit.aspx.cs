using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

using System.Data.Odbc;

namespace iis
{
    public partial class edit : System.Web.UI.Page
    {
        OdbcConnection conn = null;
        OdbcCommand cmd;
        OdbcDataReader reader;

        protected void Page_Load(object sender, EventArgs e)
        {
            //Check that user is logged in
            if (Session["user_id"] == null)
            {
                Response.Redirect("index.aspx");
            }

            Page.Header.Title = "SetBook - Preferences";

            if (!this.IsPostBack)
            {                
                InitializeValues();
            }
        }

        private void InitializeValues()
        {
            Shared.GenerateNumberList(dd_year.Items, 1900, 2100);
            Shared.GenerateNumberList(dd_month.Items, 1, 12);
            Shared.GenerateNumberList(dd_day.Items, 1, 31);

            string query = "SELECT name,email,dateOfBirth FROM users WHERE id='" + Session["user_id"] + "';";

            try
            {
                conn = new OdbcConnection(Shared.ConnectionString());
                conn.Open();

                cmd = new OdbcCommand(query);
                cmd.Connection = conn;
                reader = cmd.ExecuteReader();

                if (reader.HasRows)
                {
                    tb_email.Text = (string)reader["email"];
                    tb_name.Text = (string)reader["name"];

                    string str = reader["dateOfBirth"].ToString();

                    DateTime date = DateTime.Parse(reader["dateOfBirth"].ToString());

                    dd_year.SelectedIndex = dd_year.Items.IndexOf(new ListItem(date.Year.ToString()));
                    dd_month.SelectedIndex = dd_month.Items.IndexOf(new ListItem(date.Month.ToString()));
                    dd_day.SelectedIndex = dd_day.Items.IndexOf(new ListItem(date.Day.ToString()));
                }
            }
            catch(Exception ex)
            {
                if (conn != null)
                {
                    conn.Close();
                }
                lb_error.Text = ex.Message;
                return;
            }
        }

        protected void btn_register_Click(object sender, EventArgs e)
        {
            string name = tb_name.Text;
            string email = tb_email.Text;
            string dateOfBirth = "";
            string oldPassword = tb_old_password.Text;
            string firstPassword = tb_new_password.Text;
            string secondPassword = tb_new_password_again.Text;

            string query = "UPDATE users SET ";

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

            query += "dateOfBirth='" + dateOfBirth + "', ";

            if (name.Length == 0)
            {
                valid = false;
                lb_name.Text = "Name must not be blank.";
            }
            else
            {
                query += "name='" + name + "', ";
            }

            if (firstPassword.Length != 0)
            {
                if (firstPassword != secondPassword)
                {
                    valid = false;
                    lb_new_password.Text = "Passwords must match.";
                }
                else
                {
                    query += "password='" + Shared.HashPassword(firstPassword) + "', ";
                }
            }

            if (!Shared.ValidateEmail(email))
            {
                valid = false;
                lb_email.Text = "Email is invalid.";
            }
            else
            {
                query += "email='" + email + "' ";
            }

            if (valid)
            {
                query += "WHERE id='" + Session["user_id"] + "' AND password='" + Shared.HashPassword(oldPassword) + "'";
                OdbcConnection conn = null;
                OdbcCommand cmd;

                try
                {
                    conn = new OdbcConnection(Shared.ConnectionString());
                    cmd = new OdbcCommand(query);
                    cmd.Connection = conn;
                    conn.Open();

                    if (cmd.ExecuteNonQuery() == 0)
                    {
                        conn.Close();
                        lb_old_password.Text = "Incorrect Password.";
                        return;
                    }
                }
                catch (Exception ex)
                {
                    if (conn != null)
                    {
                        conn.Close();
                    }
                    lb_error.Text = ex.Message;
                    return;
                }

                conn.Close();
            }
        }

        protected void btn_edit_Click(object sender, EventArgs e)
        {
            string name = tb_name.Text;
            string email = tb_email.Text;
            string dateOfBirth = "";
            string oldPassword = tb_old_password.Text;
            string firstPassword = tb_new_password.Text;
            string secondPassword = tb_new_password_again.Text;

            string query = "UPDATE users SET ";

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

            query += "dateOfBirth='" + dateOfBirth + "', ";

            if (name.Length == 0)
            {
                valid = false;
                lb_name.Text = "Name must not be blank.";
            }
            else
            {
                query += "name='" + name + "', ";
            }

            if (firstPassword.Length != 0)
            {
                if (firstPassword != secondPassword)
                {
                    valid = false;
                    lb_new_password.Text = "Passwords must match.";
                }
                else
                {
                    query += "password='" + Shared.HashPassword(firstPassword) + "', ";
                }
            }

            if (!Shared.ValidateEmail(email))
            {
                valid = false;
                lb_email.Text = "Email is invalid.";
            }
            else
            {
                query += "email='" + email + "' ";
            }

            if (valid)
            {
                query += "WHERE id='" + Session["user_id"] + "' AND password='" + Shared.HashPassword(oldPassword) + "';";
                OdbcConnection conn = null;
                OdbcCommand cmd;

                try
                {
                    conn = new OdbcConnection(Shared.ConnectionString());
                    cmd = new OdbcCommand(query);
                    cmd.Connection = conn;
                    conn.Open();
                    cmd.ExecuteNonQuery();
                    //TODO - password check
                    conn.Close();
                    return;
                }
                catch (Exception ex)
                {
                    if (conn != null)
                    {
                        conn.Close();
                    }
                    lb_error.Text = ex.Message;
                    return;
                }

                conn.Close();
            }
        }

        
    }
}