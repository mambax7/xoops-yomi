<?php
if(ini_get("magic_quotes_gpc")) {
	$_GET = array_map("stripslashes", $_GET);
}
$_GET = array_map("htmlspecialchars", $_GET);
//��������Υѥ�᡼���ѹ��� single_link.php �إ�����쥯��
if (!empty($_GET['item_id'])){
	$redirect = "single_link.php?item_id=".$_GET['item_id']."&mode=".$_GET['mode']."&order=".$_GET['order'];
	header("Location: $redirect");  
}
// For XOOPS
if (empty($_GET['engine']) || $_GET['engine'] == "pre")
	include("header.php");
else
	include("../../mainfile.php");

include('init.php');

//���ϻ���
$Ebf_times = yomi_getmicrotime();

// �������鸡��������Ѥ����¤���
$EST['allow_search_form'] = trim($EST['allow_search_form']);
if ($EST['allow_search_form']){
	$allow_urls = array();
	$allow_urls = explode(" ",$EST['allow_search_form']);
	$allow_ok = false;
	foreach($allow_urls as $allow_url){
		$allow_url = quotemeta($allow_url);
		$allow_url = str_replace("/","\/",$allow_url);
		if (preg_match("/^$allow_url/",$GLOBALS['HTTP_REFERER'])){
			$allow_ok = true;
			brark;
		}
	}
	if (!$allow_ok) mes("���ѵ��Ĥ���Ƥ��ʤ�URL���饢����������Ƥ��뤫���ޤ��ϻ��ȸ�������Ǥ��ޤ���", "���������Բ�","index.php");
}

##�ܼ�##
#(1)�������ɽ������(search)
#(2)�ܺٸ�������(search_ex)
#(3)������󥯲���(meta)
#(4)���������ѥǡ�����ϥå���������(open_for_search)
#(5)�����������󥸥�ؤΥ�󥯰�����ɽ��(PR_mata_page)
#(6)������ɤ�����󥭥󥰥ե�����(keyrank_temp.cgi)�˵�Ͽ(set_word)

##�ƥ�ץ졼�ȥե�����
#������̲���=>temp/search.html
#�ܺٸ�������=>temp/search_ex.html
#������󥯲���=>temp/search_meta.html

#�ڥ��ƥ��긡����
##[���ץ����]
#���ƥ������([kt]&[option(b_all=�ʲ�)])
#���ջ���=>( today-x | year/mon/day | [str_day]-[end_day] )
#����������ɥ�=>window=new
if(!$_GET['page']){$_GET['page']=1;}

#(1)�������ɽ������(search)
if($_GET['mode'] == "search"){ #�������ɽ������
	//�����Ƚ�Υ��å�������
	$CK_data = get_cookie();
	if (!isset($_GET['sort']) && isset($CK_data[7])) $_GET['sort']=$CK_data[7];
	if (isset($_GET['sort'])) {
		$CK_data[7]=$_GET['sort'];
		set_cookie($CK_data);
	}

	$words_a = array();
	$words_o = array();
	$words_n = array();
	$kt_search_list=array();
	#�������ץ����򥯥å����˵�Ͽ
	#���ץ����
	#[0]=>�������(a|o)/[1]=>�������λ���̵ͭ(0|1)/[2]=>�������󥸥�̾(ID)/
	#[3]=>�������󥸥�̾(ɽ��̾)/[4]=>www.(0|1)/[5]=>���ƥ������(ID)
	#[6]=>���ƥ������(ɽ��̾)/[7]=>���ꥫ�ƥ���(0|1)/[8]=>���ջ���(data)
	#[9]=>���ջ���(ɽ��̾)/[10]=>���ջ��ꥳ�ޥ��(data)/[11]=>���ƥ���̾����(0|1)
	if($_GET['set_option'] == "on"){
		$CK_data = get_cookie();
		#local(@cookie_lines);
		if($_GET[method] == "and"){$cookie_lines[0]="a";} #[0]
		else{$cookie_lines[0]="o";}
		if($_GET[use_str] == "on"){$cookie_lines[1]="1";} #[1]
		else{$cookie_lines[1]="0";}
		$cookie_lines[2]=$_GET[engine]; #[2]
		if($_GET[engine] == "pre"){$cookie_lines[3]="$EST[search_name]��";} #[3]
		else{
		$fp = fopen("pl/cfg.php", "r");
			$i=0;
			while($tmp = fgets($fp, 4096)){
				if($tmp == "function search_form(){\n"){$i=1;}
				if($i){
					if(preg_match("/<option value=\"$_GET[engine]\">(.+)��/", $tmp, $match)){$cookie_lines[3]=$match[1]; break;}
				}
			}
		fclose($fp);
		}
		$cookie_lines[3]=str_replace(",","��", $cookie_lines[3]);
		if($_GET[www] == "on"){$cookie_lines[4]="0";} #[4]
		else{$cookie_lines[4]="1";}
		$cookie_lines[5]=$_GET[search_kt]; #[5]
		if($_GET[search_kt]){$cookie_lines[6]=&full_kt($_GET[search_kt]);} #[6]
		else{$cookie_lines[6]="���ꤷ�ʤ�";}
		if($_GET[search_kt_ex] != "-b_all"){$cookie_lines[7]=0;} #[7]
		else{$cookie_lines[7]=1;}
		$cookie_lines[8]=$_GET[search_day]; #[8]
		if($_GET[search_day] == "today"){$cookie_lines[9]="����";} #[9]
		elseif(preg_match("/^(\d+)-/", $_GET[search_day], $match)){$cookie_lines[9]= $match[1] . "������";}
		else{$cookie_lines[9]="���ꤷ�ʤ�";}
		$cookie_lines[10]=$_GET[search_day_ex]; #[10]
		$cookie_lines[10]=str_replace(",","��", $cookie_lines[10]);
		if($_GET[kt_search] == "on"){$cookie_lines[11]=0;} #[11]
		else{$cookie_lines[11]=1;}
		$CK_data[5]=implode(",",$cookie_lines);
		$CK_data[5]=str_replace(";", "", $CK_data[5]);
		set_cookie($CK_data);
	}
	#�����ͤ�����
	if(!$_GET['engine']){$_GET['engine']="pre";}
	if(preg_match("/\D/", $_GET['page'])){mes("�ڡ��������ͤ������Ǥ�","�ڡ������ꥨ�顼","java");}
	if(!$_GET['sort']){$_GET['sort']=$EST['defo_hyouji'];}
	if($_GET['search_kt_ex']){$_GET['search_kt']=$_GET['search_kt'] . $_GET['search_kt_ex'];}
	if($_GET['search_day_ex']){$_GET['search_day']=$_GET['search_day_ex'];}
	if($_GET['kn']>0 && $_GET['kn']<=20){ #������ɤη��
		if(preg_match("/\D/", $_GET['kn'])){mes('$_GET[kn]�������Ǥ�',"���顼","java");}
		for($i=1; $i< $_GET['kn']; $i++){
			if($_GET["word$i"]){$_GET['word'] .= " ".$_GET["word$i"];}
		}
	}
	//�������ʸ�����󥳡����Ѵ�
	//$_GET['word'] = mb_convert_encoding($_GET['word'], "EUC-JP", "auto");

	if(!$_GET['hyouji']){$_GET['hyouji']=$EST['hyouji'];}
	
	if($EST['keyrank'] && $_GET['page']==1){ #������ɥ�󥭥��ѤΥǡ��������
		set_word();
	}
	#������ʸ�β���
	$w_line=str_replace("��", " ", $_GET['word']);
	$_GET['word']=$w_line;
	if($_GET['use_str'] == "on"){ #��������Ȥ�
		$words=explode(" ",$w_line);
		$w_fl="and";
		foreach ($words as $word){
			if($word == "and"){$w_fl="and";}
			elseif($word == "or"){$w_fl="or";}
			elseif($word == "not"){$w_fl="not";}
			elseif($w_fl == "and"){array_push($words_a,$word); $w_fl="and";}
			elseif($w_fl == "or"){array_push($words_o,$word); $w_fl="and";}
			elseif($w_fl == "not"){array_push($words_n,$word); $w_fl="and";}
			else{array_push($words_a,$word);}
		}
	}
	else{ #��������Ȥ�ʤ�
		if($_GET['method'] != "or"){ $words_a=explode(" ",$w_line);}
		else{$words_o=explode(" ",$w_line);}
	}
	#����������ʬ��
	if($_GET['engine'] != "pre"){
		$_GET[target]=$_GET[window];
		require "pl/meta_ys.php";
		meta("select");
	}
	if(!$_GET['word'] && !$_GET[search_day]){ #������ɡ����ջ����ξ����̤����ΤȤ�
		mes("<b>�������</b>��<b>���ջ���</b>�Τ����줫��ɬ�����ꤷ�Ƥ�������","�����ߥ�","java");
	}
	if(!$_GET['word']){$_GET['kt_search']="off";}
 	##��������
	#���ƥ��긡��
	if($_GET['kt_search'] != "off"){
		reset($ganes);
		while (list($kt, $kt_name) = each($ganes)){
			$kt_fl=1;
			foreach ($words_a as $word){ #and����
				if(!stristr($kt_name,$word)){$kt_fl=0; break;}
			}
			foreach ($words_o as $word){ #or����
				$kt_fl=0;
				if(stristr($kt_name,$word)){$kt_fl=1; break;}
			}
			foreach ($words_n as $word){ #not����
				if(stristr($kt_name,$word)){$kt_fl=0; break;}
			}
			if($kt_fl){array_push($kt_search_list,$kt);}
		}
	}
	#���ɽ��
	if($_GET['search_day']){
		if(preg_match("/^today-?(\d*)$/", $_GET['search_day'], $match)){ #today-x
			if($match[1]>10000){$match[1]=0;}
			if($match[1]>0){$search_day="��".$match[1]."�����˹������줿������<br>";}
			else{$search_day="�������˹������줿������<br>";}
		}
		elseif(preg_match("/^(\d+)\-(\d+)$/", $_GET['search_day'], $match)){ #[str_day]-[end_day]
			if($match[2]>0){$search_day="��".$match[1]."������".$match[2]."�����˹������줿������<br>";}
			else{$search_day="��".$match[1]."������˹������줿������<br>";}
		}
		elseif(preg_match("/^(\d+)\/(\d+)\/(\d+)$/", $_GET['search_day'], $match)){ #year/mon/day
			$search_day="��".$_GET['search_day']." �˹������줿������<br>";
		}
	
	}
	##�ե�������ɤ߹��ߡ������ǡ������������
	$Clog=open_for_search($_GET['search_kt'],$_GET['search_day'],$_GET['sort']);
	
	$log_lines = array_splice($write,($_GET['page']-1)*$EST['hyouji'],$EST['hyouji']);
	unset($write);
	##����@write���˴�
	
	// �إå����ڡ����޻ߥ��ƥ����Ƚ��
	EST_reg();
	$_no_ad_space = false;
	foreach($log_lines as $Slog)
	{
		$kt = explode("&",$Slog[10]);
		foreach ($kt as $tmp)
		{
			if($ganes[$tmp])
			{
				$_no_ad_space = (preg_match("/\.$/",$ganes[$tmp]));	
			}
			if ($_no_ad_space) break;
		}
		if ($_no_ad_space) break;
	}
	
	if($_GET['item_id']){
		//$Stitle="�������";
		$Stitle = $write[0][1];
	}elseif($_GET['use_str'] == "on"){
		$Stitle="�������(��������$_GET[word])";
	}
	else{
		if(!$_GET['method']){$_GET['method']="and";}
		$Stitle="�������(������ɡ�$_GET[word] / ������$_GET[method])";
	}
	//xoops2 �����ȥ�����
	global $xoopsModule,$xoopsTpl;
	if (is_object($xoopsTpl))
	{
		$xoops_pagetitle = $xoopsModule->name();
		$xoops_pagetitle = "$Stitle-$xoops_pagetitle";
		$xoopsTpl->assign("xoops_pagetitle",$xoops_pagetitle);
	}

	require $EST['temp_path']."search.html";
	
	include("footer.php");
	
	if (isset($link) && $link) {
		@mysql_close($link);
	}
	
	exit;
}

#������󥯲���
elseif($_GET['mode'] == "meta"){
	if($EST[keyrank] && $_GET[page]==1){ #������ɥ�󥭥��ѤΥǡ��������
		set_word();
	}
	require "pl/meta_ys.php";
	require "$EST[temp_path]search_meta.html";
}

#�ܺٸ�������
else{
	require "$EST[temp_path]search_ex.html";
}
exit;

#(4)���������ѥǡ�����ϥå���������(&open_for_search)
#�о��������@write�������
#$arg1=>���ƥ������([kt]-[option(b_all=�ʲ�)])
#$arg2=>���ջ���=>( today-x | year/mon/day | [str_day]-[end_day]_[option(re)] )
#$arg3=>��������ˡ(id/time/ac/mark)
function open_for_search($target_kt, $target_day, $sort){
	global $xoopsDB,$EST,$ganes, $words_a, $words_o, $words_n, $write;
	$i = 0;
	#���ƥ��������ʬ
	if($target_kt){
		list($target_kt1,$target_kt2)=explode("-",$target_kt);
		$target_kt1 = preg_replace("/[^0-9_]+/","",$target_kt1);
		list($oya_kt,)=explode("_",$target_kt1);
		if(!$ganes[$oya_kt]){mes("���ƥ�����꤬�����Ǥ�","���ƥ�����ꥨ�顼","java");}
		if($target_kt2){$category = "category LIKE '%&$target_kt1%'";}
		else{$category = "category LIKE '%&$target_kt1&%'";}
	}
	#��ɸ�����ʬ
	if(count($words_a)>=0){ #and����
		foreach ($words_a as $word){
			$word = addslashes($word);
			if (preg_match("/#id:([0-9]*)/",$word,$reg)){
				$where .= " AND id=".$reg[1];
			} else {
				$where .= " AND (title LIKE '%$word%' OR message LIKE '%$word%' OR keywd LIKE '%$word%' OR url LIKE '%$word%')";
			}
		}
	}
	if(count($words_o)>=0){ #or����
		foreach ($words_o as $word){
			$word = addslashes($word);
			$where .= " OR (title LIKE '%$word%' OR message LIKE '%$word%' OR keywd LIKE '%$word%' OR url LIKE '%$word%')";
		}
	}
	if(count($words_n)>=0){ #not����
		foreach ($words_n as $word){
			$word = addslashes($word);
			$where .= " AND (title NOT LIKE '%$word%' AND message NOT LIKE '%$word%' AND keywd NOT LIKE '%$word%' AND url NOT LIKE '%$word%')";
		}
	}
	if($where) {$where = substr($where, 4);}
	#���ո�����ʬ
	if($target_day){
		if(preg_match("/^today-?(\d*)$/", $target_day, $match)){ #today-x
			if($match[1]>10000){$match[1]=0;}
			//$bf_day=time()-86400*$match[1];
			//$ltime = "stamp > '$bf_day'";
			$str_times=time()-86400*$match[1];
			$end_times=time()-86400*($match[1]-1);
			$ltime = "stamp BETWEEN '$str_times' AND '$end_times'";
		}
		elseif(preg_match("/^(\d+)\-(\d+)$/", $target_day, $match)){ #[str_day]-[end_day]
			$str_times=time()-86400*$match[1];
			$end_times=time()-86400*$match[2];
			$ltime = "stamp BETWEEN '$str_times' AND '$end_times'";
		}
		elseif(preg_match("/^(\d+)\/(\d+)\/(\d+)$/", $target_day, $match)){ #year/mon/day
			$month=array('01'=>'January','02'=>'February','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December');
			$mon=$month[$match[2]];
			$str_times = strtotime ("$match[3] $mon $match[1]");
			$end_times = $str_times + 86400;
			$ltime = "stamp BETWEEN '$str_times' AND '$end_times'";
		}
		else{mes("���ջ���Υ��ޥ�ɤ�����������ޤ���","���顼","java");}
	}
	#�ǡ�����Ǽ��ʬ
	if($sort == "id_new"){$order = "ORDER BY id DESC";}
	elseif($sort == "id_old"){$order = "ORDER BY id";}
	elseif($sort == "time_new"){$order = " ORDER BY stamp DESC";}
	elseif($sort == "time_old"){$order = " ORDER BY stamp";}
	elseif($sort == "ac_new"){$order = " ORDER BY title";}
	elseif($sort == "vote"){$order = " ORDER BY votes DESC";}
	elseif($sort == "rating"){$order = " ORDER BY rating DESC";}
	elseif($sort == "comment"){$order = " ORDER BY comments DESC";}
	elseif($sort == "ac_old"){$order = " ORDER BY title DESC";}
	else{$order = " ORDER BY mark DESC, id DESC";} #mark
	if($category) {$query = " ($category)";}
	if($query and $where) {$query .= " AND ";}
	if($where) {$query .= "($where)";}
	if($query and $ltime) {$query .= " AND ";}
	if($ltime) {$query .= "($ltime)";}
	$query = "SELECT * FROM $EST[sqltb]log WHERE".$query.$order;
	##���������¹�
	//$link=mysql_connect($EST[host], $EST[sqlid], $EST[sqlpass]) or die("Could not connect");
	//mysql_select_db($EST[sqldb]) or die("Could not select database");
	$result = $xoopsDB->query($query) or die("Query failed1");
		while($line = mysql_fetch_row($result)){
			$write[] = $line;
			$i++;
		}
	if(!count($write)){$i=0;}
	return $i;
}

#(5)�����������󥸥�ؤΥ�󥯰�����ɽ��(&PR_mata_page)
function PR_meta_page($location_list){
	$T_flag=1;
	$target = (!empty($_POST['target']))? $_POST['target'] : (!empty($_GET['target']))? $_GET['target'] : "";
	$target = htmlspecialchars($target);
	echo "<table style=\"width:90%;padding:8px;\" align=\"center\" width=\"90%\" cellpadding=8>";
	foreach ($location_list as $list){
		list($Dengine,$Durl)=explode("<>",$list);
		if($T_flag==5){echo "</tr>"; $T_flag=1;}
		if($T_flag==1){echo "<tr>";}
		?>
<td class="yomi-s" style="text-align:center;"><a href="<?=$Durl?>" target="<?=$target?>"><?=$Dengine?></a></td>
<?php
		$T_flag++;
	}
	if($T_flag!=2){echo "</tr>";}
	echo "</table>";
}

#(6)������ɤ�ǡ����١����˵�Ͽ(&set_word)
function set_word(){
	global $EST,$xoopsDB;
	$time=time();
	$keyword=str_replace("'", "��", $_GET['word']);
	if(strlen($keyword)<50){
		$keyword=str_replace("��", " ", $keyword);
		//$keyword=mb_strtolower($keyword);
		//$keyword=strtolower($keyword);
		//XREA�Ǥʤ���strtolower()��ʸ����������Τǻ����ʤ�
		$u_word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$l_word = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
		$keyword = str_replace($u_word,$l_word,$keyword);

		$keywords=explode(" ",$keyword);
		$keywords = array_map("addslashes", $keywords);

		//$link=mysql_connect($EST[host], $EST[sqlid], $EST[sqlpass]) or die("Could not connect");
		//mysql_select_db($EST[sqldb]) or die("Could not select database");

		if(count($keywords)>0){
			foreach ($keywords as $i){
				if($i && $i != "and" && $i != "or" && $i != "not" && (!preg_match("/#id:[0-9]*/",$i))){
					//if(preg_match("/\w/", $i)){$i=strtolower($i);}
					$i=str_replace("\n", "", $i);
					$query = "SELECT word FROM $EST[sqltb]key WHERE word='$i' AND ip='$_SERVER[REMOTE_ADDR]' AND time > ".($time-24*3600);
					$result=$xoopsDB->query($query) or die("Query failed search289 $query");
					$tmp = mysql_fetch_row($result);
					if(!$tmp) {
						$query="INSERT INTO $EST[sqltb]key (word, time, ip) VALUES ('$i', '$time', '$_SERVER[REMOTE_ADDR]')";
						$result=$xoopsDB->queryF($query) or die("Query failed search291 $query");
					}
				}
			}
		}
	}
}

#(t1)��å��������̽���(mes)
#��:&mes($arg1,$arg2,$arg3);
#��ǽ:��å��������̤���Ϥ���
#����:$arg1=>ɽ�������å�����
#     $arg2=>�ڡ����Υ����ȥ�(��ά���ϡ֥�å��������̡�)
#     $arg3=>��JavaScript�ˤ������ץܥ���ɽ��=java
#            ��HTTP_REFERER��Ȥ����=env
#            ���������ؤΥܥ���=kanri
#            ���̾��URL���ϥѥ�����ꤹ����ˤϤ���URL���ϥѥ�����
#            ����ά������ɽ��
#�����:�ʤ�
function mes($MES, $TITLE, $arg3=""){
	global $EST;
	global $xoopsOption,$xoopsConfig,$xoopsLogger,$xoopsTpl;
	global $x_ver,$ver;
	if(!$TITLE){$TITLE="��å���������";}
	if($arg3 == "java"){
		$BACK_URL="<form><input type=button value=\"&nbsp;&nbsp;&nbsp;&nbsp;���&nbsp;&nbsp;&nbsp;&nbsp;\" onClick=\"history.back()\"></form>";
	}
	elseif($arg3 == "env"){
		$BACK_URL="��<a href=\"$_SERVER[HTTP_REFERER]\">���</a>��";
	}
	elseif(!$arg3){$BACK_URL="";}
	else{$BACK_URL="��<a href=\"$arg3\">���</a>��";}
	require "$EST[temp_path]mes.html";
	exit;
}
?>