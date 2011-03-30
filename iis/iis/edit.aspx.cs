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
                Shared.GenerateNumberList(dd_year.Items, 1900, 2100);
                Shared.GenerateNumberList(dd_month.Items, 1, 12);
                Shared.GenerateNumberList(dd_day.Items, 1, 31);
            }
            InitializeValues();
        }

        private void InitializeValues()
        {
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

    }
}