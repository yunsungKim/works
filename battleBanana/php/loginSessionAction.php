<?//로그인했을 때 생기는 세션 관리
	session_start(); 

	if($_SESSION["A"] == 'yes'){//관리자일경우
		$QuickMenu_style = "";
		$menu1Button = "<a href='../php/logout_action.php'><img src='../img/btn_logout.gif' name='btn_logout' id='btn_logout' onmouseover=javascript:btn_logout.src='../img/btn_logout_o.gif'; onmouseout=javascript:btn_logout.src='../img/btn_logout.gif'; border='0'/></a>";
		$menu2Button = "<a href='../admin/main.html' target='_blank'><img src='../img/btn_admin.gif' border='0'/></a>";
		$battleButton = "<a onclick='goBattle();' style='cursor:pointer'><img src='../img/sub/btn_battle.gif' name='btn_battle' id='btn_battle' onmouseover=javascript:btn_battle.src='../img/sub/btn_battle_c.gif'; onmouseup=javascript:btn_battle.src='../img/sub/btn_battle_o.gif'; onmouseout=javascript:btn_battle.src='../img/sub/btn_battle.gif'; onmousedown=javascript:btn_battle.src='../img/sub/btn_battle_c.gif'; border='0'/></a><a onclick=openDialogByDom('#dialog_auto') style='cursor:pointer'><img src='../img/sub/btn_auto.gif' name='btn_auto' id='btn_auto' onmouseover=javascript:btn_auto.src='../img/sub/btn_auto_c.gif'; onmouseup=javascript:btn_auto.src='../img/sub/btn_auto_o.gif'; onmouseout=javascript:btn_auto.src='../img/sub/btn_auto.gif'; onmousedown=javascript:btn_auto.src='../img/sub/btn_auto_c.gif'; border='0'/></a><span id='battleButton_tooltip'><table border='0' cellspacing='0' cellpadding='0'><tbody><tr><td class='leftTop'>&nbsp;</td><td><table class='arrowSet' border='0' cellspacing='0' cellpadding='0' style='width: 150px; '><tbody><tr><td class='arrow'>&nbsp;</td><td class='top' style='width: 90px; height: 20px; '>&nbsp;</td></tr></tbody></table></td><td class='rightTop'> </td></tr><tr><td class='left'>&nbsp;</td><td class='tip' style='width: 150px; height: 0px; '><img src='../img/sub/ico_save.gif' border=0 style='float:left'/><div class='content'></div></td><td class='right'>&nbsp;</td></tr><tr><td class='leftBottom'>&nbsp;</td><td><table class='arrowSetBottom' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td class='bottom' style='width: 150px; height: 20px; '>&nbsp;</td></tr></tbody></table></td><td class='rightBottom'></td></tr></tbody></table><div class='close' style='display: none; '>&nbsp;</div></span>";
		$battleEndButton = "<img src='../img/sub/btn_end.gif' border=0 onmouseover=$('.tooltip_end').show(); onmouseout=$('.tooltip_end').hide();>";
		
		$se_str = "SELECT * from `BBanana_ships` WHERE user_id = '".$_SESSION['ID']."' and item_id = '".$sid."'";
		$se_sql = mysql_query($se_str) or die(mysql_error()); 
		$se_row = mysql_fetch_array($se_sql);

		if($se_row)
			$rewardButton = "<a onclick='openDialogByDom(\"#dialog_orderlist\")' style='cursor:pointer'><img src='../img/sub/btn_orderlist.gif' border='0'/></a>";
		else
			$rewardButton = "<a onclick=openDialogByDom('#dialog_deli') style='cursor:pointer'><img src='../img/sub/btn_reward.gif' name='btn_reward' id='btn_reward' onmouseover=javascript:btn_reward.src='../img/sub/btn_reward_o.gif'; onmouseup=javascript:btn_reward.src='../img/sub/btn_reward_o.gif'; onmouseout=javascript:btn_reward.src='../img/sub/btn_reward.gif'; onmousedown=javascript:btn_reward.src='../img/sub/btn_reward_c.gif'; border='0'/></a>";
	}else if($_SESSION["ID"]){//일반회원일경우
		$isLoged = $_SESSION["ID"];
		$QuickMenu_style = "";
		$menu1Button = "<a href='../php/logout_action.php'><img src='../img/btn_logout.gif' name='btn_logout' id='btn_logout' onmouseover=javascript:btn_logout.src='../img/btn_logout_o.gif'; onmouseout=javascript:btn_logout.src='../img/btn_logout.gif'; border='0'/></a>";
		$menu2Button = "<a onclick=openDialogByDom('#dialog_modify') style='cursor:pointer'><img src='../img/btn_modify.gif' name='btn_modify' id='btn_modify' onmouseover=javascript:btn_modify.src='../img/btn_modify_o.gif'; onmouseout=javascript:btn_modify.src='../img/btn_modify.gif'; border='0'/></a>";
		$battleButton = "<a onclick='goBattle();' style='cursor:pointer'><img src='../img/sub/btn_battle.gif' name='btn_battle' id='btn_battle' onmouseover=javascript:btn_battle.src='../img/sub/btn_battle_c.gif'; onmouseup=javascript:btn_battle.src='../img/sub/btn_battle_o.gif'; onmouseout=javascript:btn_battle.src='../img/sub/btn_battle.gif'; onmousedown=javascript:btn_battle.src='../img/sub/btn_battle_c.gif'; border='0'/></a><a onclick=openDialogByDom('#dialog_auto') style='cursor:pointer'><img src='../img/sub/btn_auto.gif' name='btn_auto' id='btn_auto' onmouseover=javascript:btn_auto.src='../img/sub/btn_auto_c.gif'; onmouseup=javascript:btn_auto.src='../img/sub/btn_auto_o.gif'; onmouseout=javascript:btn_auto.src='../img/sub/btn_auto.gif'; onmousedown=javascript:btn_auto.src='../img/sub/btn_auto_c.gif'; border='0'/></a><span id='battleButton_tooltip'><table border='0' cellspacing='0' cellpadding='0'><tbody><tr><td class='leftTop'>&nbsp;</td><td><table class='arrowSet' border='0' cellspacing='0' cellpadding='0' style='width: 150px; '><tbody><tr><td class='arrow'>&nbsp;</td><td class='top' style='width: 90px; height: 20px; '>&nbsp;</td></tr></tbody></table></td><td class='rightTop'> </td></tr><tr><td class='left'>&nbsp;</td><td class='tip' style='width: 150px; height: 0px; '><img src='../img/sub/ico_save.gif' border=0 style='float:left'/><div class='content'></div></td><td class='right'>&nbsp;</td></tr><tr><td class='leftBottom'>&nbsp;</td><td><table class='arrowSetBottom' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td class='bottom' style='width: 150px; height: 20px; '>&nbsp;</td></tr></tbody></table></td><td class='rightBottom'></td></tr></tbody></table><div class='close' style='display: none; '>&nbsp;</div></span>";
		$battleEndButton = "<img src='../img/sub/btn_end.gif' border=0  onmouseover=$('.tooltip_end').show(); onmouseout=$('.tooltip_end').hide();>";
	
		$se_str = "SELECT * from `BBanana_ships` WHERE user_id = '".$_SESSION['ID']."' and item_id = '".$sid."'";
		$se_sql = mysql_query($se_str) or die(mysql_error()); 
		$se_row = mysql_fetch_array($se_sql);

		if($se_row)
			$rewardButton = "<a onclick='openDialogByDom(\"#dialog_orderlist\")' style='cursor:pointer'><img src='../img/sub/btn_orderlist.gif' border='0'/></a>";
		else
			$rewardButton = "<a onclick=openDialogByDom('#dialog_deli') style='cursor:pointer'><img src='../img/sub/btn_reward.gif' name='btn_reward' id='btn_reward' onmouseover=javascript:btn_reward.src='../img/sub/btn_reward_o.gif'; onmouseup=javascript:btn_reward.src='../img/sub/btn_reward_o.gif'; onmouseout=javascript:btn_reward.src='../img/sub/btn_reward.gif'; onmousedown=javascript:btn_reward.src='../img/sub/btn_reward_c.gif'; border='0'/></a>";
	}else{
		$isLoged = "로긴안됨";
		$QuickMenu_style = "display:none";
		$menu1Button = "<a href='#dialog_login' name='modal'><img src='../img/btn_login.gif' name='btn_login' id='btn_login' onmouseover=javascript:btn_login.src='../img/btn_login_o.gif'; onmouseout=javascript:btn_login.src='../img/btn_login.gif'; border='0'/></a>";
		$menu2Button = "<a href='#dialog_signup' name='modal'><img src='../img/btn_signin.gif' name='btn_signin' id='btn_signin' onmouseover=javascript:btn_signin.src='../img/btn_signin_o.gif'; onmouseout=javascript:btn_signin.src='../img/btn_signin.gif'; border='0'/></a>";
		$battleButton = "<a href='#dialog_login' name='modal'><img src='../img/sub/btn_battle.gif' name='btn_battle' id='btn_battle' onmouseover=javascript:btn_battle.src='../img/sub/btn_battle_c.gif'; onmouseup=javascript:btn_battle.src='../img/sub/btn_battle_o.gif'; onmouseout=javascript:btn_battle.src='../img/sub/btn_battle.gif'; onmousedown=javascript:btn_battle.src='../img/sub/btn_battle_c.gif'; border='0'/></a><a href='#dialog_login' name='modal'><img src='../img/sub/btn_auto.gif' name='btn_auto' id='btn_auto' onmouseover=javascript:btn_auto.src='../img/sub/btn_auto_c.gif'; onmouseup=javascript:btn_auto.src='../img/sub/btn_auto_o.gif'; onmouseout=javascript:btn_auto.src='../img/sub/btn_auto.gif'; onmousedown=javascript:btn_auto.src='../img/sub/btn_auto_c.gif'; border='0'/></a><span id='battleButton_tooltip'><table border='0' cellspacing='0' cellpadding='0'><tbody><tr><td class='leftTop'>&nbsp;</td><td><table class='arrowSet' border='0' cellspacing='0' cellpadding='0' style='width: 150px; '><tbody><tr><td class='arrow'>&nbsp;</td><td class='top' style='width: 90px; height: 20px; '>&nbsp;</td></tr></tbody></table></td><td class='rightTop'> </td></tr><tr><td class='left'>&nbsp;</td><td class='tip' style='width: 150px; height: 0px; '><img src='../img/sub/ico_save.gif' border=0 style='float:left'/><div class='content'></div></td><td class='right'>&nbsp;</td></tr><tr><td class='leftBottom'>&nbsp;</td><td><table class='arrowSetBottom' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td class='bottom' style='width: 150px; height: 20px; '>&nbsp;</td></tr></tbody></table></td><td class='rightBottom'></td></tr></tbody></table><div class='close' style='display: none; '>&nbsp;</div></span>";
		$battleEndButton = "<img src='../img/sub/btn_end.gif' border=0  onmouseover=$('.tooltip_end').show(); onmouseout=$('.tooltip_end').hide();>";
		$rewardButton = "<a href='#dialog_login' name='modal'><img src='../img/sub/btn_reward.gif' name='btn_reward' id='btn_reward' onmouseover=javascript:btn_reward.src='../img/sub/btn_reward_o.gif'; onmouseup=javascript:btn_reward.src='../img/sub/btn_reward_o.gif'; onmouseout=javascript:btn_reward.src='../img/sub/btn_reward.gif'; onmousedown=javascript:btn_reward.src='../img/sub/btn_reward_c.gif'; border='0'/></a>";
	}
?>