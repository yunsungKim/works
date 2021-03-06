<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?
include './php/connect.php';
include 'xml_parser.php';
/*
┌---- 업체약어 ----------┐
│01.쇼킹온----------sk1,2│
│02.티켓몬스터------tm1,2│
│03.슈거딜----------sg   │
│04.원데이플레이스--op   │
│05.데일리픽--------dp   │
│06.쿠펀------------kf   │
│07.키위------------qw   │
│08.파티윈----------pw   │
│10.할인의추억------cm   │
│11.kupon-----------kp123│
│12.딜즈온----------do(X)│
│13.반토막티켓------bt(X)│
│14.트윗폰----------tp   │
│15.쿠팡------------cp   │
│16.위폰------------wp   │
└------------------------┘
*/



function urlutfchr($text){ 
	return rawurldecode(preg_replace_callback('/%u([[:alnum:]]{4})/', 'tostring', $text)); 
}
function tostring($text) { 
	return iconv('UTF-16LE', 'UHC', chr(hexdec(substr($text[1], 2, 2))).chr(hexdec(substr($text[1], 0, 2)))); 
}

function storeDB($company_name, $isStore){
	$getURL["sk1"] = 'http://showkingon.com/changeArea.php?Aid=07';//강남
	$getURL["sk2"] = 'http://showkingon.com/changeArea.php?Aid=08';//강북
	$getURL["tm1"] = 'http://ticketmonster.co.kr/html/?area=28';//강남
	$getURL["tm2"] = 'http://ticketmonster.co.kr/html/?area=29';//강북
	$getURL["sg"] = 'http://www.sugardeal.co.kr';
	$getURL["op"] = 'http://www.onedayplace.com';
	$getURL["dp"] = 'http://www.dailypick.co.kr';
	$getURL["kf"] = 'http://www.koofun.co.kr';
	$getURL["qw"] = 'http://www.qiwi.co.kr';
	$getURL["pw"] = 'http://www.partywin.co.kr';
	$getURL["cm"] = 'http://www.couponmemory.com/index.php';
	$getURL["kp1"] = 'http://www.kupon.co.kr/index.kupon?kuponNo=5';//1
	$getURL["kp2"] = 'http://www.kupon.co.kr/index.kupon?kuponNo=6';//2
	$getURL["kp3"] = 'http://www.kupon.co.kr/index.kupon?kuponNo=7';//3
	$getURL["do"] = 'http://www.dealson.co.kr';
	$getURL["bt"] = 'http://www.bantomakticket.co.kr';
	$getURL["tp"] = 'http://www.tweetpon.com';
	$getURL["cp"] = 'http://coupang.com';
	$getURL["wp"] = 'http://www.wipon.co.kr';
	if(date('D') == 'Fri') $add_time = mktime(00,00,00,date('m'),date('d')+3,date('Y'));
	else $add_time = mktime(00,00,00,date('m'),date('d')+1,date('Y'));
	
	switch($company_name){
		case 'sk1':{//쇼킹온 강남
			$html = file_get_contents($getURL[$company_name]);

			preg_match_all('/inVar=(.*)\"/U', $html, $get_original_price);
			preg_match_all('/inVar1=(.*)\&/U', $html, $get_nowcnt);
			preg_match_all('/<img src="(.*)" width="573" height="314" \/>/U', $html, $get_img);
			preg_match_all('/javascript:sendTwitter\(\'(.*)\'/U', $html, $get_title);
			
			$get_original_price[1][2] = preg_replace("/,/","", trim($get_original_price[1][2]));
			$get_original_price[1][4] = preg_replace("/,/","", trim($get_original_price[1][4]));

			$insert_title = $get_title[1][0];
			$insert_rate = $get_original_price[1][0];
			$insert_oriprice = $get_original_price[1][2];
			$insert_price = $get_original_price[1][4];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = 'http://showkingon.com'.$get_img[1][0];
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'sk2':{//쇼킹온 강북
			$html = file_get_contents($getURL[$company_name]);

			preg_match_all('/inVar=(.*)\"/U', $html, $get_original_price);
			preg_match_all('/inVar1=(.*)\&/U', $html, $get_nowcnt);
			preg_match_all('/<img src="(.*)" width="573" height="314" \/>/U', $html, $get_img);
			preg_match_all('/javascript:sendTwitter\(\'(.*)\'/U', $html, $get_title);

			$get_original_price[1][2] = preg_replace("/,/","", trim($get_original_price[1][2]));
			$get_original_price[1][4] = preg_replace("/,/","", trim($get_original_price[1][4]));

			$insert_title = $get_title[1][0];
			$insert_rate = $get_original_price[1][0];
			$insert_oriprice = $get_original_price[1][2];
			$insert_price = $get_original_price[1][4];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = 'http://showkingon.com'.$get_img[1][0];
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'tm1':{//티켓몬스터 강남
			$html = file_get_contents($getURL[$company_name]);
			$html = iconv('euckr', 'utf-8', urlutfchr($html));

			preg_match_all('/javascript:goTwitter\(\'(.*)\',/U', $html, $get_title);
			//preg_match_all('/&amp;q=(.*)\">/U', $html, $get_map);
			preg_match_all('/p_no=(.*)\">/U', $html, $get_no);

			$xml = file_get_contents('http://ticketmonster.co.kr/html/data/mainXml.php?&p_no='.$get_no[1][0]);
			$parser = new XMLParser(trim($xml));
			$parser->Parse();

			$left_hour = trim($parser->document->lefthour[0]->tagData)*3600;
			$left_min = trim($parser->document->leftminute[0]->tagData)*60;
			$left_sec = trim($parser->document->leftsecond[0]->tagData);

			$left_time = mktime()+$left_hour+$left_min+$left_sec;

			$get_original_price = preg_replace("/,/","", trim($parser->document->orginal[0]->tagData));
			$get_price = preg_replace("/,/","", trim($parser->document->price[0]->tagData));
			
			$insert_title = $get_title[1][0];
			$insert_rate = $parser->document->discount[0]->tagData;
			$insert_oriprice = $get_original_price;
			$insert_price = $get_price;
			$insert_cnt = $parser->document->nowcnt[0]->tagData;
			$insert_img = 'http://ticketmonster.co.kr'.$parser->document->photo[0]->img[0]->tagData;
			$insert_time = $left_time;
			$insert_comment = 'none';

		}break;
		case 'tm2':{//티켓몬스터 강북
			$html = file_get_contents($getURL[$company_name]);
			$html = iconv('euckr', 'utf-8', urlutfchr($html));

			preg_match_all('/javascript:goTwitter\(\'(.*)\',/U', $html, $get_title);
			//preg_match_all('/&amp;q=(.*)\">/U', $html, $get_map);
			preg_match_all('/p_no=(.*)\">/U', $html, $get_no);

			$xml = file_get_contents('http://ticketmonster.co.kr/html/data/mainXml.php?&p_no='.$get_no[1][0]);
			$parser = new XMLParser(trim($xml));
			$parser->Parse();

			$left_hour = trim($parser->document->lefthour[0]->tagData)*3600;
			$left_min = trim($parser->document->leftminute[0]->tagData)*60;
			$left_sec = trim($parser->document->leftsecond[0]->tagData);

			$left_time = mktime()+$left_hour+$left_min+$left_sec;

			$get_original_price = preg_replace("/,/","", trim($parser->document->orginal[0]->tagData));
			$get_price = preg_replace("/,/","", trim($parser->document->price[0]->tagData));
			
			$insert_title = $get_title[1][0];
			$insert_rate = $parser->document->discount[0]->tagData;
			$insert_oriprice = $get_original_price;
			$insert_price = $get_price;
			$insert_cnt = $parser->document->nowcnt[0]->tagData;
			$insert_img = 'http://ticketmonster.co.kr'.$parser->document->photo[0]->img[0]->tagData;
			$insert_time = $left_time;
			$insert_comment = 'none';

		}break;
		case 'sg':{//슈가딜
			$html = file_get_contents($getURL[$company_name]);

			preg_match_all('/alt=\"현재\">(.*)</U', $html, $get_nowcnt);
			preg_match_all('/var tcounter =(.*);/U', $html, $get_lefthour);
			preg_match_all('/bbs\/data\/item\/(.*)\'/U', $html, $get_img);

			$insert_title = 'none';
			$insert_rate = '50';
			$insert_oriprice = 'none';
			$insert_price = 'none';
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = 'http://www.sugardeal.co.kr/bbs/data/item/'.$get_img[1][1];
			$insert_time = mktime() + trim($get_lefthour[1][0]);
			$insert_comment = 'none';

		}break;
		case 'op':{//원데이플레이스
			$html = file_get_contents($getURL[$company_name]);

			preg_match_all('/var now_value = \"(.*)\"/U', $html, $get_nowcnt);
			preg_match_all('/var sale = \"(.*)\"/U', $html, $get_rate);
			preg_match_all('/var price = \"(.*)\"/U', $html, $get_price);
			preg_match_all('/var sale_price =\"(.*)\"/U', $html, $get_sale_price);
			
			$get_price[1][0] = preg_replace("/,/","", trim($get_price[1][0]));
			$get_sale_price[1][0] = preg_replace("/,/","", trim($get_sale_price[1][0]));

			$insert_title = 'none';
			$insert_rate = $get_rate[1][0];
			$insert_oriprice = $get_price[1][0];
			$insert_price = $get_sale_price[1][0];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = 'http://onedayplace.com/images/contents/'.date('Ymd',mktime()).'/main_1.jpg';
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'dp':{//데일리픽
			$html = file_get_contents($getURL[$company_name]);

			preg_match_all('/<li class=\"buyer\"><strong>(.*)명</U', $html, $get_nowcnt);
			preg_match_all('/var g_remainTime =(.*);/U', $html, $get_lefthour);
			preg_match_all('/javascript:share_twitter\(\'(.*)\'/U', $html, $get_title);
			preg_match_all('/<img src=\"\/mall\/updir\/products\/(.*)\" style/U', $html, $get_img);

			$insert_title = $get_title[1][0];
			$insert_rate = '50';
			$insert_oriprice = 'none';
			$insert_price = 'none';
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = 'http://www.dailypick.co.kr/mall/updir/products/'.$get_img[1][0];
			$insert_time = mktime() + trim($get_lefthour[1][0]);
			$insert_comment = 'none';

		}break;
		case 'kf':{//쿠펀
			$html = file_get_contents($getURL[$company_name]);

			preg_match_all('/no_1.gif align=absmiddle> <b>(.*)</U', $html, $get_nowcnt);
			preg_match_all('/encodeURIComponent\(\"(.*)\"/U', $html, $get_title);
			preg_match_all('/style=\'padding:0px 0px 15px 0px\'><img src=\"(.*)\"/U', $html, $get_img);
			preg_match_all('/<img src=\"\/mall\/images\/w2.gif\" align=absmiddle \/> <s>(.*)</U', $html, $get_price);
			preg_match_all('/id=\'priceHTML\'>(.*)</U', $html, $get_sale_price);

			$get_price[1][0] = preg_replace("/,/","", trim($get_price[1][0]));
			$get_sale_price[1][0] = preg_replace("/,/","", trim($get_sale_price[1][0]));

			$insert_title = $get_title[1][0];
			if($get_price[1][0] == null || $get_price[1][0] == 0) $insert_rate = 'none';
			else $insert_rate = (1-$get_sale_price[1][0]/$get_price[1][0])*100;
			$insert_oriprice = $get_price[1][0];
			$insert_price = $get_sale_price[1][0];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = 'http://www.koofun.co.kr'.$get_img[1][0];
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'qw':{//키위
			$html = file_get_contents($getURL[$company_name]);
			$html = preg_replace("/[\r\n\t]+/","", trim($html));
			$html = preg_replace("/\s+/","", trim($html));

			preg_match_all('/style=\"margin-bottom:4px;\"><\/img><imgalt="(.*)\"/U', $html, $get_nowcnt);
			preg_match_all('/<!--메인큰이미지--><divclass=\"mainImg\"style=\"\"><imgsrc=\"(.*)\"/U', $html, $get_img);
			preg_match_all('/alt=\"정가\"\/><\/th><td><imgalt=\"(.*)\"/U', $html, $get_price);
			preg_match_all('/alt=\"DC가격\"\/><\/th><td><imgalt=\"(.*)\"/U', $html, $get_sale_price);

			$get_price[1][0] = preg_replace("/,/","", trim($get_price[1][0]));
			$get_sale_price[1][0] = preg_replace("/,/","", trim($get_sale_price[1][0]));

			$insert_title = 'none';
			if($get_price[1][0] == null || $get_price[1][0] == 0) $insert_rate = 'none';
			else $insert_rate = (1-$get_sale_price[1][0]/$get_price[1][0])*100;
			$insert_oriprice = $get_price[1][0];
			$insert_price = $get_sale_price[1][0];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = 'http://www.qiwi.co.kr/'.$get_img[1][0];
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'pw':{//파티윈
			$html = file_get_contents($getURL[$company_name]);
			$html = preg_replace("/[\r\n\t]+/","", trim($html));
			$html = preg_replace("/\s+/","", trim($html));
			$html = iconv('euckr', 'utf-8', urlutfchr($html));

			preg_match_all('/<TDclass=\"no_t1_b\"><divalign=\"right\"><FONTCOLOR=\"black\"><B>(.*)<\/B>/U', $html, $get_nowcnt);
			preg_match_all('/<FONTCOLOR=\"#0284CF\"><B>(.*)<\/B>/U', $html, $get_title);
			preg_match_all('/<divalign=\"right\"><imgsrc=\"(.*)\"/U', $html, $get_img);
			preg_match_all('/<TDwidth=\"65\"class=\"no_t1_b\"><divalign=\"right\"><B><s>(.*)<\/s>/U', $html, $get_price);
			preg_match_all('/<TDclass=\"no_t1_b\"><divalign=\"right\"><FONTCOLOR=\"#0073A9\"><B>(.*)<\/B>/U', $html, $get_sale_price);

			$get_price[1][0] = preg_replace("/,/","", trim($get_price[1][0]));
			$get_sale_price[1][0] = preg_replace("/,/","", trim($get_sale_price[1][0]));

			$insert_title = $get_title[1][0];
			if($get_price[1][0] == null || $get_price[1][0] == 0) $insert_rate = 'none';
			else $insert_rate = (1-$get_sale_price[1][0]/$get_price[1][0])*100;
			$insert_oriprice = $get_price[1][0];
			$insert_price = $get_sale_price[1][0];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = 'http://www.partywin.co.kr'.$get_img[1][0];
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'cm':{//할인의 추억
			$html = file_get_contents($getURL[$company_name]);
			$html = preg_replace("/[\r\n\t]+/","", trim($html));
			$html = preg_replace("/\s+/","", trim($html));
			$html = iconv('euckr', 'utf-8', urlutfchr($html));
			preg_match_all('/main.php\?id=(.*)\"/U', $html, $get_id);

			$html = file_get_contents('http://www.couponmemory.com/main.php?id='.$get_id[1][0]);
			$html = preg_replace("/[\r\n\t]+/","", trim($html));
			$html = preg_replace("/\s+/","", trim($html));
			$html = iconv('euckr', 'utf-8', urlutfchr($html));

			preg_match_all('/&now=(.*)명/U', $html, $get_nowcnt);
			preg_match_all('/javascript:share4_0\(\'(.*)\'/U', $html, $get_title);
			preg_match_all('/<divalign=\"right\"><imgsrc=\"(.*)\"/U', $html, $get_img);
			preg_match_all('/\?before=(.*)&/U', $html, $get_price);
			preg_match_all('/&after=(.*)\"/U', $html, $get_sale_price);

			$get_price[1][0] = preg_replace("/,/","", trim($get_price[1][0]));
			$get_sale_price[1][0] = preg_replace("/,/","", trim($get_sale_price[1][0]));

			$insert_title = $get_title[1][0];
			if($get_price[1][0] == null || $get_price[1][0] == 0) $insert_rate = 'none';
			else $insert_rate = (1-$get_sale_price[1][0]/$get_price[1][0])*100;
			$insert_oriprice = $get_price[1][0];
			$insert_price = $get_sale_price[1][0];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = 'http://www.couponmemory.com/cms/data/product/'.$get_id[1][0].'/1.jpg';
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'kp1':{//kupon1
			$html_before = file_get_contents($getURL[$company_name]);
			$html = preg_replace("/[\r\n\t]+/","", trim($html_before));
			$html = preg_replace("/\s+/","", trim($html));

			preg_match_all('/\"description\" content=\"(.*)\"/U', $html_before, $get_title);
			preg_match_all('/alt=\"현재\"\/><span>(.*)<\/span></U', $html, $get_nowcnt);
			preg_match_all('/<pclass=\"rate\">(.*)%<\/p>/U', $html, $get_rate);
			preg_match_all('/<pclass=\"normal\">￦(.*)<\/p>/U', $html, $get_price);
			preg_match_all('/<pclass=\"discounted\">￦(.*)<\/p>/U', $html, $get_sale_price);
			preg_match_all('/<linkrel=\"image_src\"href=\"(.*)\"/U', $html, $get_img);

			$get_price[1][0] = preg_replace("/,/","", trim($get_price[1][0]));
			$get_sale_price[1][0] = preg_replace("/,/","", trim($get_sale_price[1][0]));

			$insert_title = $get_title[1][0];
			if($get_price[1][0] == null || $get_price[1][0] == 0) $insert_rate = 'none';
			else $insert_rate = (1-$get_sale_price[1][0]/$get_price[1][0])*100;
			$insert_oriprice = $get_price[1][0];
			$insert_price = $get_sale_price[1][0];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = $get_img[1][0];
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'kp2':{//kupon2
			$html_before = file_get_contents($getURL[$company_name]);
			$html = preg_replace("/[\r\n\t]+/","", trim($html_before));
			$html = preg_replace("/\s+/","", trim($html));

			preg_match_all('/\"description\" content=\"(.*)\"/U', $html_before, $get_title);
			preg_match_all('/alt=\"현재\"\/><span>(.*)<\/span></U', $html, $get_nowcnt);
			preg_match_all('/<pclass=\"rate\">(.*)%<\/p>/U', $html, $get_rate);
			preg_match_all('/<pclass=\"normal\">￦(.*)<\/p>/U', $html, $get_price);
			preg_match_all('/<pclass=\"discounted\">￦(.*)<\/p>/U', $html, $get_sale_price);
			preg_match_all('/<linkrel=\"image_src\"href=\"(.*)\"/U', $html, $get_img);

			$get_price[1][0] = preg_replace("/,/","", trim($get_price[1][0]));
			$get_sale_price[1][0] = preg_replace("/,/","", trim($get_sale_price[1][0]));

			$insert_title = $get_title[1][0];
			if($get_price[1][0] == null || $get_price[1][0] == 0) $insert_rate = 'none';
			else $insert_rate = (1-$get_sale_price[1][0]/$get_price[1][0])*100;
			$insert_oriprice = $get_price[1][0];
			$insert_price = $get_sale_price[1][0];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = $get_img[1][0];
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'kp3':{//kupon3
			$html_before = file_get_contents($getURL[$company_name]);
			$html = preg_replace("/[\r\n\t]+/","", trim($html_before));
			$html = preg_replace("/\s+/","", trim($html));

			preg_match_all('/\"description\" content=\"(.*)\"/U', $html_before, $get_title);
			preg_match_all('/alt=\"현재\"\/><span>(.*)<\/span></U', $html, $get_nowcnt);
			preg_match_all('/<pclass=\"rate\">(.*)%<\/p>/U', $html, $get_rate);
			preg_match_all('/<pclass=\"normal\">￦(.*)<\/p>/U', $html, $get_price);
			preg_match_all('/<pclass=\"discounted\">￦(.*)<\/p>/U', $html, $get_sale_price);
			preg_match_all('/<linkrel=\"image_src\"href=\"(.*)\"/U', $html, $get_img);

			$get_price[1][0] = preg_replace("/,/","", trim($get_price[1][0]));
			$get_sale_price[1][0] = preg_replace("/,/","", trim($get_sale_price[1][0]));

			$insert_title = $get_title[1][0];
			if($get_price[1][0] == null || $get_price[1][0] == 0) $insert_rate = 'none';
			else $insert_rate = (1-$get_sale_price[1][0]/$get_price[1][0])*100;
			$insert_oriprice = $get_price[1][0];
			$insert_price = $get_sale_price[1][0];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = $get_img[1][0];
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'tp':{//트윗폰
			$html_before = file_get_contents($getURL[$company_name]);
			$html_before2 = preg_replace("/[\r\n\t]+/","", trim($html_before));
			$html = preg_replace("/\s+/","", trim($html_before2));
			$html = preg_replace("/\'/","\\'", trim($html));

			preg_match_all('/class=\"num\">(.*)</U', $html, $get_nowcnt);
			preg_match_all('/class=\"info\"><li>(.*)<\/li/U', $html_before2, $get_title);
			preg_match_all('/id=\"s0\"><imgsrc=\"(.*)\"/U', $html, $get_img);
			preg_match_all('/class=\"ori_price\">(.*)</U', $html, $get_price);
			preg_match_all('/class=\"sale_price\">(.*)</U', $html, $get_sale_price);

			$get_price[1][0] = preg_replace("/,/","", trim($get_price[1][0]));
			$get_sale_price[1][0] = preg_replace("/,/","", trim($get_sale_price[1][0]));

			$insert_title = $get_title[1][0];
			if($get_price[1][0] == null || $get_price[1][0] == 0) $insert_rate = 'none';
			else $insert_rate = (1-$get_sale_price[1][0]/$get_price[1][0])*100;
			$insert_oriprice = $get_price[1][0];
			$insert_price = $get_sale_price[1][0];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = $get_img[1][0];
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'cp':{//쿠팡
			$html_before = file_get_contents($getURL[$company_name]);
			$html = preg_replace("/[\r\n\t]+/","", trim($html_before));
			$html = preg_replace("/\s+/","", trim($html));

			preg_match_all('/alt=\"구매인원\"\/><\/dt><dd>(.*)</U', $html, $get_nowcnt);
			preg_match_all('/alt=\"(.*)\" \/><\/h2>/U', $html_before, $get_title);
			preg_match_all('/<imgid=\"mimage\"src=\"(.*)\"/U', $html, $get_img);
			preg_match_all('/class=\"price1\">(.*)</U', $html, $get_price);
			preg_match_all('/class=\"price3\">-(.*)</U', $html, $get_sale_price);

			$get_price[1][0] = preg_replace("/,/","", trim($get_price[1][0]));
			$get_sale_price[1][0] = preg_replace("/,/","", trim($get_sale_price[1][0]));

			$insert_title = $get_title[1][0];
			if($get_price[1][0] == null || $get_price[1][0] == 0) $insert_rate = 'none';
			else $insert_rate = (1-($get_price[1][0]-$get_sale_price[1][0])/$get_price[1][0])*100;
			$insert_oriprice = $get_price[1][0];
			$insert_price = $get_price[1][0]-$get_sale_price[1][0];
			$insert_cnt = $get_nowcnt[1][0];
			$insert_img = 'http://coupang.com'.$get_img[1][0];
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		case 'wp':{//위폰
			$html2 = file_get_contents('http://m.wipon.co.kr/wipon/wipon.php');

			preg_match_all('/<title>(.*)<\/title>/U', $html2, $get_title);
			preg_match_all('/<div><img src=\"..(.*)\"/U', $html2, $get_img);
			preg_match_all('/<strike>(.*)<\/strike>/U', $html2, $get_price);
			preg_match_all('/class=\"sale\">(.*)</U', $html2, $get_sale_price);

			$get_price[1][0] = preg_replace("/,/","", trim($get_price[1][0]));
			$get_sale_price[1][0] = preg_replace("/,/","", trim($get_sale_price[1][0]));

			$insert_title = $get_title[1][0];
			if($get_price[1][0] == null || $get_price[1][0] == 0) $insert_rate = 'none';
			else $insert_rate = (1-$get_sale_price[1][0]/$get_price[1][0])*100;
			$insert_oriprice = $get_price[1][0];
			$insert_price = $get_sale_price[1][0];
			$insert_cnt = '0';
			$insert_img = 'http://m.wipon.co.kr'.$get_img[1][1];
			$insert_time = $add_time;
			$insert_comment = 'none';

		}break;
		default: return 0;
	}

	if($isStore == '1'){
		$now_date = mktime(00,00,00,date('m'),date('d'),date('Y'));
		$result = @mysql_query("SELECT * FROM C_datalist where c_date = '".$now_date."' and c_name = '".$company_name."'");
		$row = mysql_fetch_array($result);

		if(!$row){
			$query = "INSERT INTO `C_datalist`(`c_name`, `c_url`, `c_title`, `c_ori_price`, `c_price`, `c_rate`, `c_img`, `c_people`, `c_time`, `c_comment`, `c_date`) 
						VALUES(
							'".$company_name."',
							'".$getURL[$company_name]."',
							'".$insert_title."',
							'".$insert_oriprice."',
							'".$insert_price."',
							'".$insert_rate."',
							'".$insert_img."',
							'".$insert_cnt."',
							'".$insert_time."',
							'".$insert_comment."',
							'".$now_date."'
						);";
			echo $query;
			$result = @mysql_query($query);
		}else{
			$query = "UPDATE C_datalist SET c_people = '".$insert_cnt."' where c_date = '".$now_date."' and c_name = '".$company_name."';";
			echo $query;
			$sql=mysql_query($query) or die(mysql_error()); 
		}
	}else $result = 0;
	return $result;
}
/*
┌---- 업체약어 ----------┐
│01.쇼킹온----------sk1,2│
│02.티켓몬스터------tm1,2│
│03.슈거딜----------sg   │
│04.원데이플레이스--op   │
│05.데일리픽--------dp   │
│06.쿠펀------------kf   │
│07.키위------------qw   │
│08.파티윈----------pw   │
│10.할인의추억------cm   │
│11.kupon-----------kp123│
│12.딜즈온----------do(X)│
│13.반토막티켓------bt(X)│
│14.트윗폰----------tp   │
│15.쿠팡------------cp   │
│16.위폰------------wp   │
└------------------------┘
*/
storeDB('sk1', 1);
storeDB('sk2', 1);
storeDB('tm1', 1);
storeDB('tm2', 1);
storeDB('sg', 1);
storeDB('op', 1);
storeDB('dp', 1);
storeDB('kf', 1);
storeDB('qw', 1);
storeDB('pw', 1);
storeDB('cm', 1);
storeDB('kp1', 1);
storeDB('kp2', 1);
storeDB('kp3', 1);
storeDB('tp', 1);
storeDB('cp', 1);
storeDB('wp', 1);
/*
//티몬(압구정/신사/강남) xml페이지    http://ticketmonster.co.kr/html/data/mainXml.php?&p_no=186

$html = file_get_contents('http://ticketmonster.co.kr/html/?area=28');
$html = iconv('euckr', 'utf-8', urlutfchr($html));

preg_match_all('/javascript:goTwitter\(\'(.*)\',/U', $html, $get_title);
preg_match_all('/&amp;q=(.*)\">/U', $html, $get_map);
preg_match_all('/p_no=(.*)\">/U', $html, $get_no);

$xml = file_get_contents('http://ticketmonster.co.kr/html/data/mainXml.php?&p_no='.$get_no[1][0]);
$parser = new XMLParser(trim($xml));
$parser->Parse();

echo $get_title[1][0].'<br>';
echo '주소 : '.urlutfchr($get_map[1][0]).'<br>';
echo '원래가격 : '.$parser->document->orginal[0]->tagData.'<br>';
echo '할인율 : '.$parser->document->discount[0]->tagData.'%<br>';
echo '현재가격 : '.$parser->document->price[0]->tagData.'<br>';
echo '구입인원 : '.$parser->document->nowcnt[0]->tagData.'<br>';
echo '남은시간 : '.$parser->document->lefthour[0]->tagData.':'.$parser->document->leftminute[0]->tagData.':'.$parser->document->leftsecond[0]->tagData.'<br>';
echo '이미지 : <img src="http://ticketmonster.co.kr'.$parser->document->photo[0]->img[0]->tagData.'"/><br><br>';


$query = "INSERT INTO `C_datalist`(`c_name`, `c_url`, `c_title`, `c_ori_price`, `c_price`, `c_rate`, `c_img`, `c_people`, `c_time`, `c_comment`) 
				VALUES(
					'티켓몬스터',
					'http://ticketmonster.co.kr/html/?area=28',
					'".$get_title[1][0]."',
					'".$parser->document->orginal[0]->tagData."',
					'".$parser->document->price[0]->tagData."',
					'".$parser->document->discount[0]->tagData."',
					'http://ticketmonster.co.kr".$parser->document->photo[0]->img[0]->tagData."',
					'".$parser->document->nowcnt[0]->tagData."',
					'',
					'쭈꾸미'
				);";
$result = @mysql_query($query);

$html = file_get_contents('http://ticketmonster.co.kr/html/?area=29');
$html = iconv('euckr', 'utf-8', urlutfchr($html));

preg_match_all('/javascript:goTwitter\(\'(.*)\',/U', $html, $get_title);
preg_match_all('/&q=(.*)\">/U', $html, $get_map);
preg_match_all('/p_no=(.*)\">/U', $html, $get_no);
$xml = file_get_contents('http://ticketmonster.co.kr/html/data/mainXml.php?&p_no='.$get_no[1][0]);
$parser = new XMLParser(trim($xml));
$parser->Parse();

echo $get_title[1][0].'<br>';
echo '주소 : '.urlutfchr($get_map[1][0]).'<br>';
echo '원래가격 : '.$parser->document->orginal[0]->tagData.'<br>';
echo '할인율 : '.$parser->document->discount[0]->tagData.'%<br>';
echo '현재가격 : '.$parser->document->price[0]->tagData.'<br>';
echo '구입인원 : '.$parser->document->nowcnt[0]->tagData.'<br>';
echo '남은시간 : '.$parser->document->lefthour[0]->tagData.':'.$parser->document->leftminute[0]->tagData.':'.$parser->document->leftsecond[0]->tagData.'<br>';
echo '이미지 : <img src="http://ticketmonster.co.kr'.$parser->document->photo[0]->img[0]->tagData.'"/><br><br>';

$query = "INSERT INTO `C_datalist`(`c_name`, `c_url`, `c_title`, `c_ori_price`, `c_price`, `c_rate`, `c_img`, `c_people`, `c_time`, `c_comment`) 
				VALUES(
					'티켓몬스터',
					'http://ticketmonster.co.kr/html/?area=28',
					'".$get_title[1][0]."',
					'".$parser->document->orginal[0]->tagData."',
					'".$parser->document->price[0]->tagData."',
					'".$parser->document->discount[0]->tagData."',
					'http://ticketmonster.co.kr".$parser->document->photo[0]->img[0]->tagData."',
					'".$parser->document->nowcnt[0]->tagData."',
					'',
					'쭈꾸미'
				);";
$result = @mysql_query($query);


//쇼킹온
$html = file_get_contents('http://showkingon.com/changeArea.php?Aid=07');
preg_match_all('/inVar=(.*)\"/U', $html, $get_original_price);
preg_match_all('/inVar1=(.*)\&/U', $html, $get_nowcnt);
preg_match_all('/<img src="(.*)" width="573" height="314" \/>/U', $html, $get_img);
preg_match_all('/javascript:sendTwitter\(\'(.*)\'/U', $html, $get_title);
echo $get_title[1][0].'<br>';
echo '할인율 : '.$get_original_price[1][0].'<br>';
echo '원래가격 : '.$get_original_price[1][2].'<br>';
echo '현재가격 : '.$get_original_price[1][4].'<br>';
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '이미지 : <img src="http://showkingon.com'.$get_img[1][0].'"/><br>';

$query = "INSERT INTO `C_datalist`(`c_name`, `c_url`, `c_title`, `c_ori_price`, `c_price`, `c_rate`, `c_img`, `c_people`, `c_time`, `c_comment`) 
				VALUES(
					'쇼킹온',
					'http://showkingon.com/changeArea.php?Aid=07',
					'".$get_title[1][0]."',
					'".$get_original_price[1][2]."',
					'".$get_original_price[1][4]."',
					'".$get_original_price[1][0]."',
					'http://showkingon.com".$get_img[1][0]."',
					'".$get_nowcnt[1][0]."',
					'',
					'쭈꾸미'
				);";
$result = @mysql_query($query);

//원데이플레이스
$html = file_get_contents('http://www.onedayplace.com/index.php?product_idx=52');
preg_match_all('/var now_value = \"(.*)\"/U', $html, $get_nowcnt);
preg_match_all('/var sale = \"(.*)\"/U', $html, $get_rate);
preg_match_all('/var price = \"(.*)\"/U', $html, $get_price);
preg_match_all('/var sale_price =\"(.*)\"/U', $html, $get_sale_price);

echo '할인율 : '.$get_rate[1][0].'<br>';
echo '원래가격 : '.$get_price[1][0].'<br>';
echo '현재가격 : '.$get_sale_price[1][0].'<br>';
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '이미지 : <img src="http://onedayplace.com/images/contents/'.date('Ymd',mktime()).'/main_1.jpg"/><br>';


$query = "INSERT INTO `C_datalist`(`c_name`, `c_url`, `c_title`, `c_ori_price`, `c_price`, `c_rate`, `c_img`, `c_people`, `c_time`, `c_comment`) 
				VALUES(
					'원데이플레이스',
					'http://www.onedayplace.com/index.php?product_idx=52',
					'".$get_title[1][0]."',
					'".$get_price[1][0]."',
					'".$get_sale_price[1][0]."',
					'".$get_rate[1][0]."',
					'http://onedayplace.com/images/contents/".date('Ymd',mktime())."/main_1.jpg',
					'".$get_nowcnt[1][0]."',
					'',
					'쭈꾸미'
				);";
$result = @mysql_query($query);

//슈가딜
$html = file_get_contents('http://www.sugardeal.co.kr/');
preg_match_all('/alt=\"현재\">(.*)</U', $html, $get_nowcnt);
preg_match_all('/var tcounter =(.*);/U', $html, $get_lefthour);
//preg_match_all('/var sale = \"(.*)\"/U', $html, $get_rate);
//preg_match_all('/var price = \"(.*)\"/U', $html, $get_price);
//preg_match_all('/var sale_price =\"(.*)\"/U', $html, $get_sale_price);
$left_hour = floor(trim($get_lefthour[1][0])/3600);
$left_min = floor(trim($get_lefthour[1][0])%3600/60);
$left_sec = floor(trim($get_lefthour[1][0])%60);
if($left_hour < 10) $left_hour='0'.$left_hour;
if($left_min < 10) $left_min='0'.$left_min;
if($left_sec < 10) $left_sec='0'.$left_sec;

echo '제목: 모름<br>';//누락
echo '할인율 : 50<br>';//누락
echo '원래가격 : 모름<br>';//누락
echo '현재가격 : 모름<br>';//누락
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '남은시간 : '.$left_hour.':'.$left_min.':'.$left_sec.'<br>';
echo '이미지 : <img src="http://www.sugardeal.co.kr/bbs/data/item/1281260741_l1"/><br>';//주기적인것이 필요함..


//데일리픽
$html = file_get_contents('http://www.dailypick.co.kr/');
preg_match_all('/<li class=\"buyer\"><strong>(.*)</U', $html, $get_nowcnt);
preg_match_all('/var g_remainTime =(.*);/U', $html, $get_lefthour);
preg_match_all('/javascript:share_twitter\(\'(.*)\'/U', $html, $get_title);
preg_match_all('/<img src=\"\/mall\/updir\/products\/(.*)\" style/U', $html, $get_img);
//preg_match_all('/var sale_price =\"(.*)\"/U', $html, $get_sale_price);
$left_hour = floor(trim($get_lefthour[1][0])/3600);
$left_min = floor(trim($get_lefthour[1][0])%3600/60);
$left_sec = floor(trim($get_lefthour[1][0])%60);
if($left_hour < 10) $left_hour='0'.$left_hour;
if($left_min < 10) $left_min='0'.$left_min;
if($left_sec < 10) $left_sec='0'.$left_sec;

echo '제목: '.$get_title[1][0].'<br>';
echo '할인율 : 모름<br>';//누락
echo '원래가격 : 모름<br>';//누락
echo '현재가격 : 모름<br>';//누락
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '남은시간 : '.$left_hour.':'.$left_min.':'.$left_sec.'<br>';
echo '이미지 : <img src="http://www.dailypick.co.kr/mall/updir/products/'.$get_img[1][0].'"/><br>';//주기적인것이 필요함..


//위폰(답안나옴..다 미완성)
$html2 = file_get_contents('http://m.wipon.co.kr/wipon/wipon.php');

//preg_match_all('/<li class=\"buyer\"><strong>(.*)</U', $html, $get_nowcnt);
//preg_match_all('/var g_remainTime =(.*);/U', $html, $get_lefthour);
preg_match_all('/<title>(.*)<\/title>/U', $html2, $get_title);
preg_match_all('/<div><img src=\"..(.*)\"/U', $html2, $get_img);
preg_match_all('/<strike>(.*)<\/strike>/U', $html2, $get_price);
preg_match_all('/class=\"sale\">(.*)</U', $html2, $get_sale_price);

echo '제목: '.$get_title[1][0].'<br>';
echo '할인율 : '.((1-$get_sale_price[1][0]/$get_price[1][0])*100).'<br>';//디비전이 0일때 오류 수정필요
echo '원래가격 : '.$get_price[1][0].'<br>';
echo '현재가격 : '.$get_sale_price[1][0].'<br>';
echo '구입인원 : 모름<br>';
echo '남은시간 : 모름<br>';
echo '이미지 : <img src="http://m.wipon.co.kr'.$get_img[1][1].'"/><br>';


//쿠폰
$html = file_get_contents('http://www.koofun.co.kr/');
preg_match_all('/no_1.gif align=absmiddle> <b>(.*)</U', $html, $get_nowcnt);
preg_match_all('/encodeURIComponent\(\"(.*)\"/U', $html, $get_title);
preg_match_all('/style=\'padding:0px 0px 15px 0px\'><img src=\"(.*)\"/U', $html, $get_img);
preg_match_all('/<img src=\"\/mall\/images\/w2.gif\" align=absmiddle \/> <s>(.*)</U', $html, $get_price);
preg_match_all('/id=\'priceHTML\'>(.*)</U', $html, $get_sale_price);

echo '제목: '.$get_title[1][0].'<br>';
echo '할인율 : '.($get_sale_price[1][0]/$get_price[1][0]*100).'<br>';//디비전이 0일때 오류 수정필요
echo '원래가격 : '.$get_price[1][0].'<br>';
echo '현재가격 : '.$get_sale_price[1][0].'<br>';
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '남은시간 : 모름<br>';//누락
echo '이미지 : <img src="http://www.koofun.co.kr'.$get_img[1][0].'"/><br>';


//키위1
$html = file_get_contents('http://www.qiwi.co.kr/coupon/details/22');
$html = preg_replace("/[\r\n\t]+/","", trim($html));
$html = preg_replace("/\s+/","", trim($html));
preg_match_all('/style=\"margin-bottom:4px;\"><\/img><imgalt="(.*)\"/U', $html, $get_nowcnt);
//preg_match_all('/encodeURIComponent\(\"(.*)\"/U', $html, $get_title);
preg_match_all('/<!--메인큰이미지--><divclass=\"mainImg\"style=\"\"><imgsrc=\"(.*)\"/U', $html, $get_img);
preg_match_all('/alt=\"정가\"\/><\/th><td><imgalt=\"(.*)\"/U', $html, $get_price);
preg_match_all('/alt=\"DC가격\"\/><\/th><td><imgalt=\"(.*)\"/U', $html, $get_sale_price);

echo '제목: 모름<br>';//누락
echo '할인율 : '.($get_sale_price[1][0]/$get_price[1][0]*100).'<br>';//디비전이 0일때 오류 수정필요
echo '원래가격 : '.$get_price[1][0].'<br>';
echo '현재가격 : '.$get_sale_price[1][0].'<br>';
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '남은시간 : 모름<br>';//누락
echo '이미지 : <img src="http://www.qiwi.co.kr/'.$get_img[1][0].'"/><br>';


//키위2
$html = file_get_contents('http://www.qiwi.co.kr/coupon/details/18');
$html = preg_replace("/[\r\n\t]+/","", trim($html));
$html = preg_replace("/\s+/","", trim($html));
preg_match_all('/style=\"margin-bottom:4px;\"><\/img><imgalt="(.*)\"/U', $html, $get_nowcnt);
//preg_match_all('/encodeURIComponent\(\"(.*)\"/U', $html, $get_title);
preg_match_all('/<!--메인큰이미지--><divclass=\"mainImg\"style=\"\"><imgsrc=\"(.*)\"/U', $html, $get_img);
preg_match_all('/alt=\"정가\"\/><\/th><td><imgalt=\"(.*)\"/U', $html, $get_price);
preg_match_all('/alt=\"DC가격\"\/><\/th><td><imgalt=\"(.*)\"/U', $html, $get_sale_price);

echo '제목: 모름<br>';//누락
echo '할인율 : '.($get_sale_price[1][0]/$get_price[1][0]*100).'<br>';//디비전이 0일때 오류 수정필요
echo '원래가격 : '.$get_price[1][0].'<br>';
echo '현재가격 : '.$get_sale_price[1][0].'<br>';
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '남은시간 : 모름<br>';//누락
echo '이미지 : <img src="http://www.qiwi.co.kr/'.$get_img[1][0].'"/><br>';

//파티윈
$html = file_get_contents('http://www.partywin.co.kr/new/');
$html = preg_replace("/[\r\n\t]+/","", trim($html));
$html = preg_replace("/\s+/","", trim($html));
$html = iconv('euckr', 'utf-8', urlutfchr($html));

preg_match_all('/<TDclass=\"no_t1_b\"><divalign=\"right\"><FONTCOLOR=\"black\"><B>(.*)<\/B>/U', $html, $get_nowcnt);
preg_match_all('/<FONTCOLOR=\"#0284CF\"><B>(.*)<\/B>/U', $html, $get_title);
preg_match_all('/<divalign=\"right\"><imgsrc=\"(.*)\"/U', $html, $get_img);
preg_match_all('/<TDwidth=\"65\"class=\"no_t1_b\"><divalign=\"right\"><B><s>(.*)<\/s>/U', $html, $get_price);
preg_match_all('/<TDclass=\"no_t1_b\"><divalign=\"right\"><FONTCOLOR=\"#0073A9\"><B>(.*)<\/B>/U', $html, $get_sale_price);

echo '제목: '.$get_title[1][0].'<br>';
echo '할인율 : '.($get_sale_price[1][0]/$get_price[1][0]*100).'<br>';//디비전이 0일때 오류 수정필요
echo '원래가격 : '.$get_price[1][0].'<br>';
echo '현재가격 : '.$get_sale_price[1][0].'<br>';
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '남은시간 : 모름<br>';//누락
echo '이미지 : <img src="http://www.partywin.co.kr'.$get_img[1][0].'"/><br>';


//할인의 추억
$html = file_get_contents('http://www.couponmemory.com/index.php');
$html = preg_replace("/[\r\n\t]+/","", trim($html));
$html = preg_replace("/\s+/","", trim($html));
$html = iconv('euckr', 'utf-8', urlutfchr($html));
preg_match_all('/main.php\?id=(.*)\"/U', $html, $get_id);

$html = file_get_contents('http://www.couponmemory.com/main.php?id='.$get_id[1][0]);
$html = preg_replace("/[\r\n\t]+/","", trim($html));
$html = preg_replace("/\s+/","", trim($html));
$html = iconv('euckr', 'utf-8', urlutfchr($html));

preg_match_all('/&now=(.*)명/U', $html, $get_nowcnt);
preg_match_all('/javascript:share4_0\(\'(.*)\'/U', $html, $get_title);
preg_match_all('/<divalign=\"right\"><imgsrc=\"(.*)\"/U', $html, $get_img);
preg_match_all('/\?before=(.*)&/U', $html, $get_price);
preg_match_all('/&after=(.*)\"/U', $html, $get_sale_price);

echo '제목: '.$get_title[1][0].'<br>';
echo '할인율 : '.($get_sale_price[1][0]/$get_price[1][0]*100).'<br>';//디비전이 0일때 오류 수정필요
echo '원래가격 : '.$get_price[1][0].'<br>';
echo '현재가격 : '.$get_sale_price[1][0].'<br>';
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '남은시간 : 모름<br>';//누락
echo '이미지 : <img src="http://www.couponmemory.com/cms/data/product/'.$get_id[1][0].'/1.jpg"/><br>';



//Kupon : 이미지는 파일명에 업체명이 들어가서 뽑아오기 불가능할듯..트위터에서 제목 뽑아올 수 있을듯..시간도 뽑을 수 있음..
$html_before = file_get_contents('http://www.kupon.co.kr/index.kupon?kuponNo=5');
$html = preg_replace("/[\r\n\t]+/","", trim($html_before));
$html = preg_replace("/\s+/","", trim($html));

preg_match_all('/\"description\" content=\"(.*)\"/U', $html_before, $get_title);
preg_match_all('/alt=\"현재\"\/><span>(.*)<\/span></U', $html, $get_nowcnt);
preg_match_all('/<pclass=\"rate\">(.*)%<\/p>/U', $html, $get_rate);
preg_match_all('/<pclass=\"normal\">￦(.*)<\/p>/U', $html, $get_price);
preg_match_all('/<pclass=\"discounted\">￦(.*)<\/p>/U', $html, $get_sale_price);
preg_match_all('/<linkrel=\"image_src\"href=\"(.*)\"/U', $html, $get_img);

echo '제목: '.$get_title[1][0].'<br>';
echo '할인율 : '.$get_rate[1][0].'<br>';
echo '원래가격 : '.$get_price[1][0].'<br>';
echo '현재가격 : '.$get_sale_price[1][0].'<br>';
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '이미지 : <img src="'.$get_img[1][0].'"/><br>';


$html_before = file_get_contents('http://www.kupon.co.kr/index.kupon?kuponNo=6');
$html = preg_replace("/[\r\n\t]+/","", trim($html_before));
$html = preg_replace("/\s+/","", trim($html));

preg_match_all('/\"description\" content=\"(.*)\"/U', $html_before, $get_title);
preg_match_all('/alt=\"현재\"\/><span>(.*)<\/span></U', $html, $get_nowcnt);
preg_match_all('/<pclass=\"rate\">(.*)%<\/p>/U', $html, $get_rate);
preg_match_all('/<pclass=\"normal\">￦(.*)<\/p>/U', $html, $get_price);
preg_match_all('/<pclass=\"discounted\">￦(.*)<\/p>/U', $html, $get_sale_price);
preg_match_all('/<linkrel=\"image_src\"href=\"(.*)\"/U', $html, $get_img);

echo '제목: '.$get_title[1][0].'<br>';
echo '할인율 : '.$get_rate[1][0].'<br>';
echo '원래가격 : '.$get_price[1][0].'<br>';
echo '현재가격 : '.$get_sale_price[1][0].'<br>';
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '이미지 : <img src="'.$get_img[1][0].'"/><br>';


$html_before = file_get_contents('http://www.kupon.co.kr/index.kupon?kuponNo=7');
$html = preg_replace("/[\r\n\t]+/","", trim($html_before));
$html = preg_replace("/\s+/","", trim($html));

preg_match_all('/\"description\" content=\"(.*)\"/U', $html_before, $get_title);
preg_match_all('/alt=\"현재\"\/><span>(.*)<\/span></U', $html, $get_nowcnt);
preg_match_all('/<pclass=\"rate\">(.*)%<\/p>/U', $html, $get_rate);
preg_match_all('/<pclass=\"normal\">￦(.*)<\/p>/U', $html, $get_price);
preg_match_all('/<pclass=\"discounted\">￦(.*)<\/p>/U', $html, $get_sale_price);
preg_match_all('/<linkrel=\"image_src\"href=\"(.*)\"/U', $html, $get_img);

echo '제목: '.$get_title[1][0].'<br>';
echo '할인율 : '.$get_rate[1][0].'<br>';
echo '원래가격 : '.$get_price[1][0].'<br>';
echo '현재가격 : '.$get_sale_price[1][0].'<br>';
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '이미지 : <img src="'.$get_img[1][0].'"/><br>';


//쿠팡
$html_before = file_get_contents('http://coupang.com');
$html = preg_replace("/[\r\n\t]+/","", trim($html_before));
$html = preg_replace("/\s+/","", trim($html));

preg_match_all('/alt=\"구매인원\"\/><\/dt><dd>(.*)</U', $html, $get_nowcnt);
preg_match_all('/alt=\"(.*)\" \/><\/h2>/U', $html_before, $get_title);
preg_match_all('/<imgid=\"mimage\"src=\"(.*)\"/U', $html, $get_img);
preg_match_all('/class=\"price1\">(.*)</U', $html, $get_price);
preg_match_all('/class=\"price3\">(.*)</U', $html, $get_sale_price);

echo '제목: '.$get_title[1][0].'<br>';
echo '할인율 : '.($get_sale_price[1][0]/$get_price[1][0]*100).'<br>';//디비전이 0일때 오류 수정필요
echo '원래가격 : '.$get_price[1][0].'<br>';
echo '현재가격 : '.$get_sale_price[1][0].'<br>';
echo '구입인원 : '.$get_nowcnt[1][0].'<br>';
echo '남은시간 : 모름<br>';//누락
echo '이미지 : <img src="http://coupang.com'.$get_img[1][0].'"/><br>';*/
	mysql_close($connect);
?>