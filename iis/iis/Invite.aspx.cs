using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Net.Mail;

namespace iis
{
    public partial class Invite : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {

            Page.Header.Title = "SetBook - Invite";

            lb_error.Text = "";
            String email = Request.Form["inviteBox"];
            if (String.IsNullOrEmpty(email) == false)
            {
                try
                {
                    SendInvite(email);
                    lb_message.Text = "Invite was sent to " + email + "!";
                }
                catch (Exception ex)
                {
                    lb_error.Text = ex.Message.ToString();
                }
            }
            else
            {
                lb_error.Text = "Invite address must not be blank!";
            }
        }

        protected bool SendInvite(string email_addr)
        {
            try
            {
                
                //Set up mail server connection
                SmtpClient smtpServer = new SmtpClient("smtp.gmail.com");
                smtpServer.Port = 587;
                smtpServer.EnableSsl = true;
                smtpServer.DeliveryMethod = SmtpDeliveryMethod.Network;
                smtpServer.Credentials = new System.Net.NetworkCredential("SET.Book.Mail@gmail.com", "setbookpassword");
                                
                //Set up mail object
                MailMessage inviteMail = new MailMessage();
                inviteMail.To.Add(email_addr);
                inviteMail.From = new MailAddress("SET.Book.Mail@gmail.com");
                inviteMail.Subject = "You have been invited to SETBook!";
                inviteMail.IsBodyHtml = true;
                inviteMail.Body = "<html><body><p>You have been invited to SETBook.com by " + Session["user_id"] + ".<br /><br />To register an account on SETBook, please go to <a href=\"http://127.0.0.1/register.aspx\" >SETBook Registration</a> to register a new account.</p></body></html>";
                smtpServer.Send(inviteMail);
                return true;
            }
            catch (Exception ex)
            {
                throw;
            }
        }
    }
}


using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace iis
{
    public partial class Invite : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {

        }
    }
}