using System;
using System.Collections;
using System.Configuration;
using System.Data;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.HtmlControls;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Data.SqlClient;
using System.Data.SqlTypes;

public partial class jtbEventConfirm : System.Web.UI.Page
{
	private static string source = @"Server=10.107.1.40;uid=Log_User;pwd=fhrmdbwj;database=TAGTVDB";
	
	protected void Page_Load(object sender, EventArgs e)
	{
		if (Request["my_name"] != null && Request["my_phone"] != null)
		{
			string fieldPhone = "";
			string fieldName = "";
			
			string existsMyPhone1 = GetStringValue("EXEC TAGTVDB.dbo.uSP_IBK_JTBEvent_Get_Registered_Friend @PHONE", "@PHONE", Request["my_phone"], 6);
			string existsMyPhone2 = GetStringValue("EXEC TAGTVDB.dbo.uSP_IBK_JTBEvent_Get_Registered_Friend @PHONE", "@PHONE", Request["my_phone"], 7);
			string existsMyPhone3 = GetStringValue("EXEC TAGTVDB.dbo.uSP_IBK_JTBEvent_Get_Registered_Friend @PHONE", "@PHONE", Request["my_phone"], 8);
			
			if (existsMyPhone1 == Request["my_phone"])
			{
				fieldPhone = "FRIEND_1_PHONE";
				fieldName = "FRIEND_1_NAME";
			}
			else if (existsMyPhone2 == Request["my_phone"])
			{
				fieldPhone = "FRIEND_2_PHONE";
				fieldName = "FRIEND_2_NAME";
			}
			else if (existsMyPhone3 == Request["my_phone"])
			{	
				fieldPhone = "FRIEND_3_PHONE";
				fieldName = "FRIEND_3_NAME";
			}
			else
			{
				Response.Write("FAIL");
			}
			
			if (fieldPhone != "")
			{
				SqlConnection conn = new SqlConnection(source);
				conn.Open();
				
				SqlCommand command;
				
				command = new SqlCommand(@"EXEC TAGTVDB.dbo.uSP_IBK_JTBEvent_Confirm @NAME, @PHONE;", conn);
				
				command.Parameters.Add("@PHONE", SqlDbType.VarChar);
				command.Parameters["@PHONE"].Value = Request["my_phone"];
				
				command.Parameters.Add("@NAME", SqlDbType.VarChar);
				command.Parameters["@NAME"].Value = Request["my_name"];
				
				int result = command.ExecuteNonQuery();
				
				Response.Write("SUCC");
			}
		}
	}
	
	public string GetStringValue(string query, string parameter, string parameterValue, int fieldIndex)
	{
		SqlConnection conn = new SqlConnection(source);
		conn.Open();
		
		SqlCommand command;
		
		command = new SqlCommand(query, conn);
		command.Parameters.Add(parameter, SqlDbType.VarChar);
		command.Parameters[parameter].Value = parameterValue;
		SqlDataReader reader = command.ExecuteReader();

		
		string output = "";
		
		if (reader.Read())
		{
			output = reader.GetString(fieldIndex);
		}
		else
		{
		}
		
		conn.Close();
		
		return output;
	}
}