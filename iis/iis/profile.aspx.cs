using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace iis
{
    public partial class profile : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            Page.Header.Title = "SetBook - Profile";
            if (Session["user_id"] == null)
            {
                Response.Redirect("index.aspx");
            }
            else
            {
                lb_userid.Text = ((string)Session["user_id"]);
            }
        }
    }
}