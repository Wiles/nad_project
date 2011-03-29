using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

using System.Web.UI.WebControls;
using System.Security.Cryptography;
using System.Data.Odbc;

using System.Text.RegularExpressions;

namespace iis
{
    class Shared
    {
        OdbcConnection conn;
        OdbcDataReader reader;
        OdbcCommand cmd;

        private static string db_host = "localhost";
        private static string db_username = "nad_admin";
        private static string db_password = "admin";
        private static string db_port = "3306";

        public static string HashPassword(string password)
        {
            MD5Cng hasher = new MD5Cng();

            byte[] bytePassword = System.Text.ASCIIEncoding.ASCII.GetBytes(password);

            return BitConverter.ToString(hasher.ComputeHash(bytePassword, 0, bytePassword.Length)).Replace("-", string.Empty).ToLower();
        }

        public static bool ValidatePassword(string password)
        {
            if (password.Length == 0)
            {
                return false;
            }
            return true;
        }

        public static bool ValidateEmail(string email)
        {
            // Return true if strIn is in valid e-mail format.
            return Regex.IsMatch(email, 
                    @"[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?"); 
        }


        public static string ConnectionString()
        {
            return "DRIVER={MySQL ODBC 5.1 Driver};SERVER=" + db_host + ";PORT=" + db_port + ";DATABASE=nadproject;UID=" + db_username + ";PWD=" + db_password + ";";
        }

        public static  void GenerateNumberList(ListItemCollection list, Int32 start, Int32 end)
        {
            for (int i = start; i <= end; ++i)
            {
                list.Add(i.ToString());
            }
        }
    }
}
