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

public partial class jtbEvent : System.Web.UI.Page
{
	private static string source = @"Server=10.107.1.40;uid=Log_User;pwd=fhrmdbwj;database=TAGTVDB";
	
	protected void Page_Load(object sender, EventArgs e)
	{
		/*Response.Write(Request["my_name"]);
		Response.Write("/");
		Response.Write(Request["my_phone"]);
		Response.Write("/");
		Response.Write(Request["friend_1_phone"]);
		Response.Write("/");
		Response.Write(Request["friend_2_phone"]);
		Response.Write("/");
		Response.Write(Request["friend_3_phone"]);*/
		
		if (Request["my_name"] != null && Request["my_phone"] != null && Request["friend_1_phone"] != null && Request["friend_2_phone"] != null && Request["friend_3_phone"] != null)
		{
			bool skip = false;
			string existsMyPhone = GetStringValue("EXEC TAGTVDB.dbo.uSP_IBK_JTBEvent_Get_Registered_Applicant @PHONE", "@PHONE", Request["my_phone"], 2);
			string existsFriend1Phone = GetStringValue("EXEC TAGTVDB.dbo.uSP_IBK_JTBEvent_Get_Registered_Friend @PHONE", "@PHONE", Request["friend_1_phone"], 2);
			string existsFriend2Phone = GetStringValue("EXEC TAGTVDB.dbo.uSP_IBK_JTBEvent_Get_Registered_Friend @PHONE", "@PHONE", Request["friend_2_phone"], 2);
			string existsFriend3Phone = GetStringValue("EXEC TAGTVDB.dbo.uSP_IBK_JTBEvent_Get_Registered_Friend @PHONE", "@PHONE", Request["friend_3_phone"], 2);
			
			if (existsMyPhone != "")
			{
				skip = true;
				Response.Write(Request["my_phone"]);
				Response.Write(",");
			}
			
			if (existsFriend1Phone != "")
			{
				skip = true;
				Response.Write(Request["friend_1_phone"]);
				Response.Write(",");
			}
			
			if (existsFriend2Phone != "")
			{
				skip = true;
				Response.Write(Request["friend_2_phone"]);
				Response.Write(",");
			}
			
			if (existsFriend3Phone != "")
			{
				skip = true;
				Response.Write(Request["friend_3_phone"]);
				Response.Write(",");
			}
			
			
			if (skip == false)
			{
				SqlConnection conn = new SqlConnection(source);
				conn.Open();
				
				SqlCommand command;
				
				command = new SqlCommand(@"EXEC uSP_IBK_JTBEvent_Register @MN, @MP, @F1, @F2, @F3;", conn);
				
				command.Parameters.Add("@MN", SqlDbType.VarChar);
				command.Parameters["@MN"].Value = Request["my_name"];
				
				command.Parameters.Add("@MP", SqlDbType.VarChar);
				command.Parameters["@MP"].Value = Request["my_phone"];
				
				command.Parameters.Add("@F1", SqlDbType.VarChar);
				command.Parameters["@F1"].Value = Request["friend_1_phone"];
				
				command.Parameters.Add("@F2", SqlDbType.VarChar);
				command.Parameters["@F2"].Value = Request["friend_2_phone"];
				
				command.Parameters.Add("@F3", SqlDbType.VarChar);
				command.Parameters["@F3"].Value = Request["friend_3_phone"];
				
				int result = command.ExecuteNonQuery();
				
				string smsBody = "IBK스마트일촌이벤트 응모만 해도 캔커피가 공짜래! http://bit.ly/el5m7A";
				
				SendSMS(Request["my_phone"], Request["friend_1_phone"], smsBody);
				SendSMS(Request["my_phone"], Request["friend_2_phone"], smsBody);
				SendSMS(Request["my_phone"], Request["friend_3_phone"], smsBody);
				
				Response.Write("SUCC");
			}
		}
	}
	
	public void SendSMS(string from, string to, string body)
	{

			SqlConnection conn = new SqlConnection(source);
			conn.Open();
			
			SqlCommand command;
			
			command = new SqlCommand(@"EXEC TAGTVDB.dbo.uSP_SMS_Send @FROM, @TO, @BODY;", conn);
						
			command.Parameters.Add("@FROM", SqlDbType.VarChar);
			command.Parameters["@FROM"].Value = from;
			
			command.Parameters.Add("@TO", SqlDbType.VarChar);
			command.Parameters["@TO"].Value = to;
			
			command.Parameters.Add("@BODY", SqlDbType.VarChar);
			command.Parameters["@BODY"].Value = body;
						
			int result = command.ExecuteNonQuery();
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