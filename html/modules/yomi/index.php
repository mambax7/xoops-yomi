<?php
################################################################
# Yomi-Search Ver4 [���������󥸥�] (Since:1999/09/22)
#   (C) 1999-2001 by yomi
#   E�᡼��: yomi@pekori.to
#   �ۡ���ڡ���: http://yomi.pekori.to/
################################################################

## ---[���ѵ���]------------------------------------------------------------+
## 1. ���Υ�����ץȤϥե꡼���եȤǤ������Υ�����ץȤ���Ѥ���
##    �����ʤ�»�����Ф��ƺ�Ԥϰ��ڤ���Ǥ���餤�ޤ���
## 2. ���Υ�����ץȤ���Ѥ������������ѵ���(http://yomi.pekori.to/kiyaku.html)
##    ��Ʊ�դ�����ΤȤߤʤ����Ƥ��������ޤ���
##    �����Ѥˤʤ�����ɬ�����ɤߤ���������
## 3. Ʊ���Ρ֥������� (new.gif/recom.gif/sougogif.gif) �פ������
##   �ֵ�����ȥ������������  (http://www.ushikai.com/)�פ˵�°���Ƥ��ޤ���
## -------------------------------------------------------------------------+

if (is_readable("shorturl.php")) include("shorturl.php");

if (defined("YOMI_SHORTURL") && YOMI_SHORTURL)
{
	if(!empty($_SERVER["PATH_INFO"]) && preg_match("/^\/(.+)$/",$_SERVER["PATH_INFO"],$match))
	{
		$match[1] = preg_replace("/\/$/","",$match[1]);
		if ($match[1] == "new") $_GET['mode'] = "new";
		else if ($match[1] == "renew") $_GET['mode'] = "renew";
		else if ($match[1] == "m1") $_GET['mode'] = "m1";
		else if ($match[1] == "m2") $_GET['mode'] = "m2";
		else
		{
			$parms = explode("/",$match[1]);
			$_GET['kt'] = join("_",$parms);
			$_GET['mode'] = "kt";
		}
	}
}

//For XOOPS
include("header.php");

// Hide Notice Error. orz... TODO
error_reporting('E_ERROR | E_WARNING | E_PARSE');

if (isset($_SERVER['_REQUEST_URI'])) $_SERVER['REQUEST_URI'] = $_SERVER['_REQUEST_URI'];

include('init.php');

if (!isset($_GET['mode'])) $_GET['mode']="";
#�ƥ⡼�ɤ�ʬ��
#-----------------#
if($_GET['mode']){
	if(!$EST['home']){$EST['home']=$EST['script'];}
	if($_GET['mode'] == "kt"){
		#�ƥ��ƥ���λ���ɽ�������ȥ�
		$Stitle=$ganes[$_GET['kt']];
		list($Spre_kt_file)=explode("_",$_GET['kt']);
	}
	elseif($_GET['mode'] == "new"){$Stitle="���奵����"; $Spre_kt_file="new_ys";} #���奵���Ȥ�ɽ�������ȥ�,�ե�����̾
	elseif($_GET['mode'] == "renew"){$Stitle="����������"; $Spre_kt_file="renew_ys";} #���������Ȥ�ɽ�������ȥ�,�ե�����̾
	elseif($_GET['mode'] == "m1"){$Stitle="Ķ�������᥵����"; $Spre_kt_file="m1_ys";} #m1�����Ȥ�ɽ�������ȥ�,�ե�����̾
	elseif($_GET['mode'] == "m2"){$Stitle="�������᥵����"; $Spre_kt_file="m2_ys";} #m2�����Ȥ�ɽ�������ȥ�,�ե�����̾
	elseif($_GET['mode'] == "random"){random();} #�����ॸ����
	elseif($_GET['mode'] == "link"){yomi_link();} #��󥯥�����
	else{mes("���ꤷ���⡼�ɤ�¸�ߤ��ޤ���(mode=".$_GET['mode'].")","�⡼�����򥨥顼","java");}
	##�ڡ�������

	$_GET['page']=(isset($_GET['page']))? preg_replace("/\D/", "", $_GET['page']) : 1 ;
	if($_GET['page']<1 || $_GET['page']>1000){$_GET['page']=1;}

	$CK_data=get_cookie();
	#�ե�������ɤ߹��ߡ����إ��ƥ���ɽ��
	##�ե�������ɤ߹���
	$time=time();
	$start=$time-$EST['rank_kikan']*86400;
	$end=$time;
	$_no_ad_space = false;
	if($_GET['mode'] == "kt"){ #�ƥ��ƥ���ξ��

		$Stitle=$ganes[$_GET['kt']];
		// �إå����𥹥ڡ����޻�
		$_no_ad_space = (preg_match("/\.$/",$Stitle));

		//xoops2 �����ȥ�����
		global $xoopsModule,$xoopsTpl;
		$_kt = $kt;
		$kt=explode("_",$_GET['kt']);
		array_pop($kt);
		$temp_kt = "";
		$navi = "";
		foreach ($kt as $tmp){
			$temp_kt .= $tmp;
			$navi = $ganes[$temp_kt]."-".$navi;
			$temp_kt .="_";
		}
		if (is_object($xoopsTpl))
		{
			$xoops_pagetitle = $xoopsModule->name();
			$xoops_pagetitle = $Stitle."-".$navi.$xoops_pagetitle;
			$xoopsTpl->assign("xoops_pagetitle",$xoops_pagetitle);
		}

		// �ޡ���ͥ������
		$order_mark = NULL;
		if (!isset($_GET['sort']) && $CK_data[8] !== "") {
			$order_mark = ($CK_data[8])? 'mark DESC, ' : '';
		}
		if (isset($_GET['sort'])) {
			$CK_data[8] = (empty($_GET['mark']))? '0' : '1';
			$order_mark = (empty($_GET['mark']))? '' : 'mark DESC, ';
		}
		if (is_null($order_mark)) $order_mark = ''; #�ޡ���ͥ��ǥե����
		$_GET['mark'] = $order_mark? '1' : '0';

		// ����������
		if (!isset($_GET['sort']) && $CK_data[7] !== "") $EST['defo_hyouji']=$_GET['sort']=$CK_data[7];
		if (isset($_GET['sort'])) $CK_data[7]=$_GET['sort'];

		// ���إ��ƥ���ǡ���ɽ������
		if ($CK_data[6]==='1' || $CK_data[6]==='0') $EST['kt_child_show']=$CK_data[6];
		if (isset($_GET['child_show'])) {
			$EST['kt_child_show'] = $_GET['child_show'];
			$g_prm_child .= "&amp;child_show=".$_GET['child_show'];
			$CK_data[6]=($_GET['child_show'])? '1' : '0';
		} else {
			$g_prm_child = '';
		}

		set_cookie($CK_data);

		if ($EST['kt_child_show']){
			$kt_sql = "&".str_replace('_', '\_', $_GET['kt']);
			if (Child_count($_GET['kt']) > 0) $Stitle .= " (���إ��ƥ���ǡ���ɽ����)";
		} else {
			$kt_sql = "&".str_replace('_', '\_', $_GET['kt'])."&";
		}

		if (empty($_GET['sort'])) $_GET['sort'] = 'id_new'; #�ǥե���Ȥ��ɤ߹�����ˡ
		switch($_GET['sort']) {
			case "id_new": $order="id DESC"; break;
			case "id_old": $order="id"; break;
			case "time_new": $order="stamp DESC"; break;
			case "time_old": $order="stamp"; break;
			case  "ac_new": $order="title"; break;
			case  "ac_old": $order="title DESC"; break;
			case  "rating": $order = "rating DESC"; break;
			case  "vote": $order = "votes DESC"; break;
			case  "comment": $order = "comments DESC"; break;
			default:
				$order="id DESC";
				$_GET['sort'] = 'id_new';
		}
		$order = $order_mark . $order;

		// ������Υǡ���������
		$sort_selected = array_combine(array('id_new', 'id_old', 'time_new', 'time_old', 'as_new', 'ac_old', 'rating', 'vote', 'comment'), array_pad(array(), 9, ''));
		$sort_selected[$_GET['sort']] = ' selected="selected"';
		$sort_selected['mark'] = ($order_mark)? ' checked="checked"' : '';

		$Ssearch_kt=$_GET['kt']; #�����оݤΥ��ƥ����ֹ�
		$log_lines=array(); #ɽ���ǡ����ꥹ��
		$Clog=array(); #�ƥ��ƥ������Ͽ��
		$st_no=$EST['hyouji']*($_GET['page'] -1);

		$query = "SELECT `category`, `id` FROM ".$EST['sqltb']."log WHERE category LIKE '%&".str_replace('_', '\_', $Ssearch_kt)."%';";
		$result = $xoopsDB->query($query) or die("Query failed");
		$_counter = array();
		$_kt_len = strlen($Ssearch_kt) + 1;
		while ($line = mysql_fetch_row($result)) {
			$tmp = explode("&", trim($line[0], '&'));
			foreach($tmp as $tmp2) {
				if (strpos($tmp2, $Ssearch_kt) !== 0) continue;
				$_cats = array();
				if ($tmp2 !== $Ssearch_kt) {
					if ($tmp3 = substr($tmp2, $_kt_len)) {
						foreach(explode('_', $tmp3) as $_cat) {
							$_cats[] = $_cat;
							$_key = $Ssearch_kt . '_' . join('_', $_cats);
							$_counter[$_key][strval($line[1])] = true;
							if ($EST['kt_child_show']) $_counter[$Ssearch_kt][strval($line[1])] = true;
						}
					}
				} else {
					$_counter[$Ssearch_kt][strval($line[1])] = true;
				}
			}
		}

		foreach(array_keys($_counter) as $_key) {
			$Clog[$_key] = count($_counter[$_key]);
		}

		$query = "SELECT * FROM ".$EST['sqltb']."log WHERE category LIKE '%$kt_sql%' ORDER BY $order LIMIT $st_no, ".$EST['hyouji'].";";
		$result = $xoopsDB->query($query) or die("Query failed");
		while ($Slog = mysql_fetch_row($result)) {
			if($CK_data[3] || $is_admin == 1) {
				$query2="SELECT count, count_rev FROM $EST[sqltb]log WHERE id='$Slog[0]'";
				$result2 = $xoopsDB->query($query2) or die("Query failed kt52 $query");
				list($acc, $rev)=mysql_fetch_row($result2);

				$query2="SELECT COUNT(*) FROM $EST[sqltb]rank WHERE time BETWEEN $start AND $end AND id='$Slog[0]'";
				$result2 = $xoopsDB->query($query2) or die("Query failed kt40 $query");
				$count=mysql_fetch_row($result2);
				$Slog['count'] = "${EST['rank_kikan']}��(${count[0]})";

				$Slog['count'] .= "_"."��(${acc})";

				$query2="SELECT COUNT(*) FROM $EST[sqltb]rev WHERE time BETWEEN $start AND $end AND id='$Slog[0]'";
				$result2 = $xoopsDB->query($query2) or die("Query failed kt48 $query");
				$count=mysql_fetch_row($result2);
				$Slog['count'] .= ":�ե�� "."${EST['rank_kikan']}��(${count[0]})";

				$Slog['count'] .= "_"."��(${rev})";
			}

			$Slog['jump_url'] = $EST['rank_fl']? $EST['cgi_path_url']."jump.php?id=$Slog[0]" : $Slog[2];
			$Slog['favicon'] = yomi_get_favicon($Slog[2], '��');

			array_push($log_lines,$Slog);
		}
	}
	else{ #����¾���ü쥫�ƥ���
		$Stitle=$EST["name_".$_GET['mode']];

		//xoops2 �����ȥ�����
		global $xoopsModule,$xoopsTpl;
		if (is_object($xoopsTpl))
		{
			$xoops_pagetitle = $xoopsModule->name();
			$xoops_pagetitle = "$Stitle-$xoops_pagetitle";
			$xoopsTpl->assign("xoops_pagetitle",$xoops_pagetitle);
		}

		$log_lines=array(); #ɽ���ǡ����ꥹ��
		$Ssearch_kt=$Spre_kt_file;
		$st_no=$EST['hyouji']*($_GET['page'] -1);
		if($_GET['mode'] == 'new') {
			$ntime=time()-$EST['new_time']*24*3600;
			//$query = " stamp > $ntime AND renew = 0 ORDER BY mark DESC, id DESC";
			$query = " build_time > $ntime ORDER BY build_time DESC";
		} elseif($_GET['mode'] == 'renew') {
			$ntime=time()-$EST['new_time']*24*3600;
			$query = " stamp > $ntime AND renew = 1 ORDER BY stamp DESC";
		} elseif($_GET['mode'] == 'm1') {
			$query = " mark LIKE '1%'";
		} elseif($_GET['mode'] == 'm2') {
			$query = " mark LIKE '%1'";
		} else {echo "STOP in temp.php in 245"; exit;}
		$query1="SELECT * FROM ".$EST['sqltb']."log WHERE".$query." LIMIT $st_no, ".$EST['hyouji'];
		$result = $xoopsDB->query($query1) or die("Query failed kt110 $query");
		while($Slog = mysql_fetch_row($result)){
			if($CK_data[3] || $is_admin == 1) {
				$query2="SELECT count, count_rev FROM $EST[sqltb]log WHERE id='$Slog[0]'";
				$result2 = $xoopsDB->query($query2) or die("Query failed kt52 $query");
				list($acc, $rev)=mysql_fetch_row($result2);

				$query2="SELECT COUNT(*) FROM $EST[sqltb]rank WHERE time BETWEEN $start AND $end AND id='$Slog[0]'";
				$result2 = $xoopsDB->query($query2) or die("Query failed kt40 $query");
				$count=mysql_fetch_row($result2);
				$Slog['count'] = "${EST['rank_kikan']}��(${count[0]})";

				$Slog['count'] .= "_"."��(${acc})";

				$query2="SELECT COUNT(*) FROM $EST[sqltb]rev WHERE time BETWEEN $start AND $end AND id='$Slog[0]'";
				$result2 = $xoopsDB->query($query2) or die("Query failed kt48 $query");
				$count=mysql_fetch_row($result2);
				$Slog['count'] .= ":�ե�� "."${EST['rank_kikan']}��(${count[0]})";

				$Slog['count'] .= "_"."��(${rev})";
			}
			$Slog['jump_url'] = $EST['rank_fl']? $EST['cgi_path_url']."jump.php?id=$Slog[0]" : $Slog[2];
			$Slog['favicon'] = yomi_get_favicon($Slog[2], '��');
			array_push($log_lines,$Slog);
		}
		$query3="SELECT COUNT(*) FROM $EST[sqltb]log WHERE".$query;
		$result = $xoopsDB->query($query3) or die("Query failed kt115 $query");
		$num = mysql_fetch_row($result);
		$Clog[$Ssearch_kt]=$num[0];
	}
	#�ʥӥ��������С���ɽ��
	$navi = "";
	$kt=explode("_",$_GET['kt']); array_pop($kt);
	$temp_kt = "";
	$_no_head_sp = false;
	foreach ($kt as $tmp){
		$temp_kt .= $tmp;
		$navi .= "<a href=\"".yomi_makelink($temp_kt)."$Eend\">$ganes[$temp_kt]</a> &gt; ";
		$temp_kt .="_";
	}
	if($_GET['mode'] == "new"){
		$query = "SELECT COUNT(*) FROM $EST[sqltb]log";
		$result = $xoopsDB->query($query) or die("Query failed yomi43 $query");
		$total_url = mysql_fetch_row($result);
		$navi .= " - ���ߤ�����Ͽ��:<b>$total_url[0]</b>������";
	}
	##�ڡ���������ɽ��
	gane_guide();
	if($_GET['mode'] == "kt"){$guide = $KTEX[$_GET['kt']];}
	else{$guide = $KTEX[$Spre_kt_file];}
	unset($KTEX);

	$refer = (isset($_SERVER['REQUEST_URI']))? htmlspecialchars(preg_replace('#^(https?://[^/]+).*$#','$1',XOOPS_URL) . $_SERVER['REQUEST_URI']) : '';

	require $EST['temp_path']."kt.html";

	include("footer.php");
	if (isset($link) && $link) {
		@mysql_close($link);
	}

}elseif($EST['home'] && $EST['top']){
	location($EST['home']);
}else{
	$query = "SELECT COUNT(*) FROM ".$EST['sqltb']."log";
	$result = $xoopsDB->query($query) or die("Query failed yomi40 $query");
	$tmp = mysql_fetch_row($result); #����Ͽ��
	$Cpre_gane=$tmp[0];
	$CK_data=get_cookie();
	if (isset($CK_data[7])) $EST['defo_hyouji']=$CK_data[7];
	require $EST['temp_path']."top.html";
}
exit;

#(1)��å��������̽���(mes)
#��:mes($arg1,$arg2,$arg3);
#��ǽ:��å��������̤���Ϥ���
#����:$arg1=>ɽ�������å�����
#     $arg2=>�ڡ����Υ����ȥ�(��ά���ϡ֥�å��������̡�)
#     $arg3=>��JavaScript�ˤ������ץܥ���ɽ��=java
#            ��HTTP_REFERER��Ȥ����=env
#            ���������ؤΥܥ���=kanri
#            ���̾��URL���ϥѥ�����ꤹ����ˤϤ���URL���ϥѥ�����
#            ����ά������ɽ��
#�����:�ʤ�
function mes($MES,$TITLE,$arg3){
	global $EST;
	global $xoopsOption,$xoopsConfig,$xoopsLogger,$xoopsTpl;
	global $x_ver,$ver;
	if(!$TITLE){$TITLE="��å���������";}
	if($arg3 == "java"){
		$BACK_URL='<form><input type=button value="&nbsp;&nbsp;&nbsp;&nbsp;���&nbsp;&nbsp;&nbsp;&nbsp;" onClick="history.back()"></form>';
	}
	elseif($arg3 == "env"){
		$BACK_URL="��<a href=\"".$_SERVER['HTTP_REFERER']."\">���</a>��";
	}
	elseif(!$arg3){$BACK_URL="";}
	else{$BACK_URL="��<a href=\"$arg3\">���</a>��";}
	require $EST['temp_path']."mes.html";
	exit;
}

#(2)�����ॸ����(random)
function random(){
	global $EST,$xoopsDB;
	$i=1;
	$query = "SELECT * FROM ".$EST['sqltb']."log";
	$result = $xoopsDB->query($query) or die("Query failed yomi85 $query");
	$total_url=mysql_num_rows($result);
	list($usec, $sec) = explode(' ', microtime());
	srand((float)$sec + ((float)$usec * 100000));
	$id = rand(1, $total_url);
	$query = "SELECT url FROM ".$EST['sqltb']."log";
	$result = $xoopsDB->query($query) or die("Query failed yomi91");
	while($tmp = mysql_fetch_assoc($result)){
		if($i == $id){
			break;
		}
		$i++;
	}
	//mysql_close($link);
	location($tmp['url']);
	exit;
}

#(3)��󥯥����׽���(link)
function yomi_link(){
	$_GET['id']=preg_replace("/\D/", "", $_GET['id']);
	if($_GET['id']){
		#referer�����å�
		if(!$_SERVER['HTTP_REFERER']){$fl=1;} #referer��̵���Ȥ��˥�����Ȥ��ʤ����ˤϤ��ιԤ���
		$ref_list=explode(",",$EST['rank_ref']);
		if(!$EST['rank_ref']){$fl=1;}
		else{
			foreach($ref_list as $tmp){
				if(strstr($_SERVER['HTTP_REFERER'],$tmp)){$fl=1;}
			}
		}
		if($fl){
			$_GET['id']=str_replace("\n", "", $_GET['id']);
			$fp=fopen($EST['log_path']."rank_temp_ys.cgi", "a");
			flock($fp, LOCK_EX);
			fputs($fp, $_GET['id']."<>" . time() . "<>".$_SERVER['REMOTE_ADDR']."\n");
			fclose($fp);
		}
	}
	if($_GET['url']){location($_GET['url']);}
}
##-- end of yomi.php --##
