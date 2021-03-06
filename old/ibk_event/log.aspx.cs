﻿using System;
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

public partial class log : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        string source = @"Server=192.168.20.106;uid=Log_User;pwd=fhrmdbwj;database=ANALYTIC";
        string p = Request["p"];
        string p_name = "";
        string ua = Request.UserAgent;
        string strOs = "";
		
        if (ua.IndexOf("Windows") != -1) strOs = "Windows";
        else if (ua.IndexOf("Linux") != -1) strOs = "Linux";
        else if (ua.IndexOf("iPhone") != -1) strOs = "iPhone";
        else strOs = "";
		
		int wcode = 0;

        if (p == "main")			wcode = 112; //p_name = "메인페이지";
        else if (p == "event")		wcode = 113; //p_name = "Event Page";
        else if (p == "evt_confirm")wcode = 114; //p_name = "Event Confirm Page";
        else if (p == "info")		wcode = 129; //p_name = "IBK상품정보";
        else if (p == "info_1")		wcode = 115; //p_name = "IBK상품정보(핸드폰결제통장)";
        else if (p == "info_2")		wcode = 116; //p_name = "IBK상품정보(급여통장)";
        else if (p == "info_3")		wcode = 117; //p_name = "IBK상품정보(Style+ Card)";
        else if (p == "logic")		wcode = 118; //p_name = "Product which is suit for me";
		else if (p == "app")		wcode = 119; //p_name = "스마트뱅킹 APP";
        else if (p == "cf")			wcode = 120; //p_name = "CF보기";
        else if (p == "cf_1")		wcode = 121; //p_name = "CF보기(급여통장)";
        else if (p == "cf_2")		wcode = 122; //p_name = "CF보기(핸드폰결제통장)";
        else if (p == "cf_3")		wcode = 123; //p_name = "CF보기(핸드폰결제통장)";
		else if (p == "char")		wcode = 128; //p_name = "캐릭터애니메이션";
        else if (p == "char_1")		wcode = 124; //p_name = "에니메이션에피소드1";
        else if (p == "char_2")		wcode = 125; //p_name = "에니메이션에피소드2";
        else if (p == "char_3")		wcode = 126; //p_name = "에니메이션에피소드3";
        else if (p == "char_4")		wcode = 127; //p_name = "에니메이션에피소드4";
		else if (p == "evt_f_confirm")wcode = 130; // p_name = "이벤트 참여 확인"

		else if (p == "lotto")		wcode = 0; //p_name = "스마트복권이벤트";
		else if (p == "lotto_pop")	wcode = 0; //p_name = "복권팝업";
        else if (p == "ani")		wcode = 0; //p_name = "직장인 TalkTalk";
        else if (p == "ani_1")		wcode = 0; //p_name = "사보1";
        else if (p == "ani_2")		wcode = 0; //p_name = "사보2";
        else if (p == "ani_3")		wcode = 0; //p_name = "사보3";
        else if (p == "ani_4")		wcode = 0; //p_name = "사보4";
        else if (p == "ani_5")		wcode = 0; //p_name = "사보5";
		else if (p == "angel")		wcode = 0; //p_name = "스마트 엔젤 이벤트";
        else if (p == "twit")		wcode = 0; //p_name = "리트윗";
        else if (p == "twit_phone")	wcode = 0; //p_name = "리트윗(휴대폰)";
        else if (p == "twit_pay")	wcode = 0; //p_name = "리트윗(급여)";
        else						wcode = 0; //p_name = "기타";
		
		if (wcode > 0)
		{
			Response.Write(Request.UserHostAddress + "<br>");
			Response.Write(strOs + "<br>");
			Response.Write(Request.Browser.Browser + "<br>");
			Response.Write(Request.UserAgent + "<br>");
			
			SqlConnection conn = new SqlConnection(source);
			conn.Open();
			
			SqlCommand command;
			
			command = new SqlCommand(@"EXEC ANALYTIC.dbo.TAGTV_WEBSCAN_WRITE @WCODE, @IP, @OS, @BROWSER, @AGENT;", conn);
			
			command.Parameters.Add("@WCODE", SqlDbType.Int);
			command.Parameters["@WCODE"].Value = wcode;
			
			command.Parameters.Add("@IP", SqlDbType.VarChar);
			command.Parameters["@IP"].Value = Request.UserHostAddress.ToString();
			
			command.Parameters.Add("@OS", SqlDbType.VarChar);
			command.Parameters["@OS"].Value = strOs;
			
			command.Parameters.Add("@BROWSER", SqlDbType.VarChar);
			command.Parameters["@BROWSER"].Value = Request.Browser.Browser.ToString();
			
			command.Parameters.Add("@AGENT", SqlDbType.VarChar);
			command.Parameters["@AGENT"].Value = Request.UserAgent.ToString();
			
			int result = command.ExecuteNonQuery();
		}
		
		//p_int
		
		/*

        if (ua.IndexOf("Windows") != -1) strOs = "Windows";
        else if (ua.IndexOf("Linux") != -1) strOs = "Linux";
        else if (ua.IndexOf("iPhone") != -1) strOs = "iPhone";
        else strOs = "";

        SqlConnection conn = new SqlConnection(source);
        conn.Open();

        //Response.Write(Request.Browser.Browser + "<br>");
        //Response.Write(Request.UserHostAddress + "<br>");
        //Response.Write(Request.UserAgent + "<br>");

        string sql = "insert into IBK_PAGE_LOG(LOG_DATE, IP_ADDR, OS, BROWSER, USER_AGENT, PAGE_CODE, REG_DATE, REG_TIME)";
        sql += "values ('" + DateTime.Now.ToString("yyyy-MM-dd") + "', ";
        sql += "'" + Request.UserHostAddress + "', ";
        sql += "'" + strOs + "', ";
        sql += "'" + Request.Browser.Browser + "', ";
        sql += "'" + ua + "', ";
        sql += "'" + p_name + "', ";
        sql += "'" + DateTime.Now.ToString("yyyy-MM-dd") + "', ";
        sql += "'" + DateTime.Now.ToLongTimeString() + "' )";

        //Response.Write(sql);
        SqlCommand cmd = new SqlCommand(sql, conn);
        cmd.ExecuteNonQuery();

        conn.Close();
		*/
    }
}
