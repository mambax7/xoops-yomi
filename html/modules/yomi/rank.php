<?php
include("header.php");

include('init.php');

##�ܼ�##
#(1)��󥯥����׽���(link)
#(1.1)�������������׽���(r_link)
#(2)������ɥ�󥭥�ɽ������(PR_keyrank)
#(3)��������(IN)��󥭥�ɽ������(PR_rev)
#(4)�͵�(OUT)��󥭥�ɽ������(PR_rank)

if(!isset($_GET['page'])){$_GET['page']=1;}

$refer = (isset($_SERVER['REQUEST_URI']))? htmlspecialchars(preg_replace('#^(https?://[^/]+).*$#','$1',XOOPS_URL) . $_SERVER['REQUEST_URI']) : '';

$myts =& MyTextsanitizer::getInstance();

if (isset($_GET['mode'])) {
	#(1)��󥯥����׽���(link)
	if($_GET['mode'] == "link"){
		$_GET['id']=preg_replace("/\D/", "", $_GET['id']);
		if($_GET['id']){
			#referer�����å�
			if(!$_SERVER['HTTP_REFERER']){$fl=1;} #referer��̵���Ȥ��˥�����Ȥ��ʤ����ˤϤ��ιԤ���
			$ref_list=explode(",",$EST[rank_ref]);
			if(!$EST['rank_ref']){$fl=1;}
			else{
				foreach($ref_list as $tmp){
					if(strstr($_SERVER['HTTP_REFERER'],$tmp)){$fl=1;}
				}
			}
			if($fl){
				$time=time();
				$query="SELECT id FROM $EST[sqltb]rank WHERE id='$_GET[id]' AND ip='$_SERVER[REMOTE_ADDR]' AND time > ".($time-$EST[rank_time]*3600);
				$result=$xoopsDB->query($query) or die("Query failed rank32 $query");
				$tmp = mysql_fetch_row($result);
				if(!$tmp) {
					$query="INSERT INTO $EST[sqltb]rank (id,time,ip) VALUES ('$_GET[id]', '$time' ,'$_SERVER[REMOTE_ADDR]');";
					$result=$xoopsDB->queryF($query) or die("Query failed rank36 $query");
					$query = 'UPDATE ' . $EST['sqltb'] . 'log SET `count` = `count` + 1 WHERE `id` = ' . $_GET['id'];
					$result=$xoopsDB->queryF($query) or die("Query failed rank38 $query");
				}
				//mysql_close($link);
			}
		}
		if($_GET['url']){location($_GET['url']);}
	}

	#(1.1)�������������׽���(&r_link)
	elseif($_GET['mode'] == "r_link"){
		if($EST['rev_fl']){
			$_GET['id']=preg_replace("/\D/", "", $_GET['id']);
			if($_GET['id']){
				$query="SELECT id, url FROM {$EST['sqltb']}log WHERE id='{$_GET['id']}'";
				$result=$xoopsDB->query($query) or die("Query failed rank54 $query");
				if ($result) { //ID��¸�ߤ�����Τ߽�������
					list($id, $url) = mysql_fetch_row($result);
					// $_SERVER['HTTP_REFERER']�����å�
					if ($ref = @$_SERVER['HTTP_REFERER']) {
						$ref = preg_replace('#^(https?://[^/]+).*$#', '$1', $ref);
						if (strpos($url, $ref) === 0) {
							$time=time();
							$_GET['id']=str_replace("\n", "", $_GET['id']);
							$query="SELECT id FROM {$EST['sqltb']}rev WHERE id='{$_GET['id']}' AND ip='{$_SERVER['REMOTE_ADDR']}' AND time > ".($time-$EST['rank_time']*3600);
							$result=$xoopsDB->query($query) or die("Query failed rank54 $query");
							$tmp = mysql_fetch_row($result);
							if(!$tmp) {
								$query="INSERT INTO {$EST['sqltb']}rev (id,time,ip) VALUES ('{$_GET['id']}', '$time' ,'{$_SERVER['REMOTE_ADDR']}')";
								$result=$xoopsDB->queryF($query) or die("Query failed rank58 $query");
								$query = 'UPDATE ' . $EST['sqltb'] . 'log SET `count` = `count_rev` + 1 WHERE `id` = ' . $_GET['id'];
								$result=$xoopsDB->queryF($query) or die("Query failed jump29 $query");
							}
						}
					}
				}
			}
		}
		//$EST[location]=0; #refresh�����פˤ���
		location($EST['rev_url']);
	}

	#(2)������ɥ�󥭥�ɽ������(&PR_keyrank)
	elseif($_GET['mode'] == "keyrank"){
		$bad_key=array();
		$open_key=array();
		@ include $EST['log_path']."keyrank_ys.php";
		require "$EST[temp_path]keyrank.html";
		exit;
	}

	#(3)��������(IN)��󥭥�ɽ������(&PR_rev)
	elseif($_GET['mode'] == "rev" || $_GET['mode'] == "rev_bf" || $_GET['mode'] == "rev_rui"){
		if(!$EST['rev_fl']){mes("����������󥭥󥰤ϼ»ܤ��ʤ�����ˤʤäƤ��ޤ�","���顼","java");}
		$CK_data=get_cookie();

		if (isset($CK_data[6])) $EST['kt_child_show']=$CK_data[6];

		if (empty($_GET['kt'])) $_GET['kt'] = "";

		if($_GET['mode'] == "rev"){$Stitle="����������󥭥�";}
		elseif($_GET['mode'] == "rev_bf"){$Stitle="����Υ���������󥭥�";}
		else{$Stitle="����������󥭥�(�߷�)";}
		if($_GET['kt']){$Stitle.=" - " . $ganes[$_GET['kt']];}
		// �إå����𥹥ڡ����޻�
		$_no_ad_space = (preg_match("/\.$/",$ganes[$_GET['kt']]));

		if (isset($_GET['child_show'])) {
			$EST['kt_child_show'] = $_GET['child_show'];
			$g_prm_child = "&child_show=".$_GET['child_show'];
			$CK_data[6]=$_GET['child_show'];
			set_cookie($CK_data);
		} else {
			$g_prm_child = "";
		}
		if ($_GET['kt'])
		{
			$child_count = Child_count($_GET['kt']);
			if ($EST['kt_child_show']){
				$kt_sql = "&".$_GET['kt'];
				if ($child_coun > 0) {
					$Stitle .= " �ʲ��򽸷�";
				} else {
					$Stitle .= " �Τ߽���";
				}
			} else {
				$kt_sql = "&".$_GET['kt']."&";

			}
		}
		else
		{
			$Stitle .= " ����";
		}

		//xoops2 �����ȥ�����
		global $xoopsModule,$xoopsTpl;
		if (is_object($xoopsTpl))
		{
			$xoops_pagetitle = $xoopsModule->name();
			$xoops_pagetitle = "$Stitle-$xoops_pagetitle";
			$xoopsTpl->assign("xoops_pagetitle",$xoops_pagetitle);
		}

		$Eref=urlencode($_SERVER['HTTP_REFERER']);
		$Slog=array();
		$log_lines=array(); $Clog=0; $bf_pt=0; $pre_pt=""; $pre_rank=$pre_rank_z=1; $pre_pt_fl=1;
		$end_no=$_GET['page']*$EST['hyouji'];
		$str_no=$end_no-$EST['hyouji']+1;
		$time=time();
		if($_GET['mode'] == "rev"){
			$start=$time-$EST['rev_kikan']*86400;
			$end=$time;
			$last_mod=date("Y/m/d H:i", $start)."��-��".date("Y/m/d H:i", $end);
		}
		elseif($_GET['mode'] == "rev_bf"){
			$start=$time-$EST['rev_kikan']*172800;
			$end=$time-$EST['rank_kikan']*86400+1;
			$last_mod=date("Y/m/d H:i", $start)."��-��".date("Y/m/d H:i", $end);
		}
		else{
			$start=0;
			$end=$time;
			$last_mod="��-��".date("Y/m/d H:i", $end);
		}

		if ($start) {
			if ($_GET['kt']){
				$query="SELECT r.id, COUNT(r.id) AS pt
				FROM $EST[sqltb]rev r, $EST[sqltb]log l
				WHERE l.id = r.id and r.time BETWEEN $start AND $end and l.category LIKE '%$kt_sql%'
				GROUP BY r.id";
			} else {
				$query="SELECT id,COUNT(*) AS pt FROM $EST[sqltb]rev WHERE time BETWEEN $start AND $end GROUP BY id";
			}
		} else {
			if ($_GET['kt']){
				$query="SELECT * FROM $EST[sqltb]log WHERE category LIKE '%$kt_sql%'";
			} else {
				$query="SELECT * FROM $EST[sqltb]log";
			}
		}

		if (!$Clog) {
			$result = $xoopsDB->query($query." LIMIT ".$EST['rank_best']);
			$Clog = mysql_num_rows($result);
		}

		$query .= $start? " ORDER BY pt DESC" : " ORDER BY count_rev DESC";

		$end_no=$EST['hyouji'];
		$str_no=$_GET['page']*$EST['hyouji']-$EST['hyouji'];
		$query .= " LIMIT $str_no , $end_no";

		$result = $xoopsDB->query($query);
		while($Rank = mysql_fetch_array($result)){
			$kt_fl=0;
			if (isset($Rank['count_rev'])) {
				$Slog = $Rank;
				$Slog[16] = $Rank['count_rev'];
			} else {
				$query="SELECT * FROM $EST[sqltb]log WHERE id='$Rank[id]' LIMIT 1";
				$result2 = $xoopsDB->query($query) or die("Query failed rev_rank120 $query");
				$Slog = mysql_fetch_row($result2);
				$Slog[16] = $Rank['pt'];
			}
			if($Slog[0]){
				$Slog[6] = str_replace('<br>', "\n", $Slog[6]);
				if ($EST['syoukai_br'] == 2) {
					$Slog[6] = $myts->displayTarea(unhtmlspecialchars($Slog[6]));
				} else if ($EST['syoukai_br'] == 1) {
					$Slog[6] = nl2br($Slog[6]);
				}
				array_push($log_lines,$Slog);
			}

		}

		#�ʥӥ��������С���ɽ��
		$navi = "";
		$kt=explode("_",$_GET['kt']); array_pop($kt);
		$temp_kt = "";
		foreach ($kt as $tmp){
			$temp_kt .= $tmp;
			$navi .= "<a href=\"$Ekt$temp_kt\">$ganes[$temp_kt]</a> &gt; ";
			$temp_kt .="_";
		}

		$tmp=array($_GET['page'],$Clog,$EST['hyouji'],"{$g_prm_child}&mode=$_GET[mode]&kt=$_GET[kt]",$EST['rank']);
		$PRmokuji=mokuji($tmp);
		require "$EST[temp_path]rev_rank.html";
	}
}

#(4)�͵���󥭥�ɽ������
if(!$EST['rank_fl']){mes("�͵���󥭥󥰤ϼ»ܤ��ʤ�����ˤʤäƤ��ޤ�","���顼","java");}
if(!isset($_GET['mode'])){$_GET['mode']="rank";}
$CK_data=get_cookie();

if (isset($CK_data[6])) $EST['kt_child_show']=$CK_data[6];

if (empty($_GET['page'])) $_GET['page'] = 1;
if (empty($_GET['kt'])) $_GET['kt'] = "";

if($_GET['mode'] == "rank"){$Stitle="�͵���󥭥�";}
elseif($_GET['mode'] == "rank_bf"){$Stitle="����ο͵���󥭥�";}
else{$Stitle="�͵���󥭥�(�߷�)";}
if($_GET['kt']){$Stitle.=" - " . $ganes[$_GET['kt']];}

// �إå����𥹥ڡ����޻�
$_no_ad_space = (preg_match("/\.$/",$ganes[$_GET['kt']]));

if (isset($_GET['child_show'])) {
	$EST['kt_child_show'] = $_GET['child_show'];
	$g_prm_child = "&child_show=".$_GET['child_show'];
	$CK_data[6]=$_GET['child_show'];
	set_cookie($CK_data);
} else {
	$g_prm_child = "";
}
if ($_GET['kt'])
{
	$child_count = Child_count($_GET['kt']);
	if ($EST['kt_child_show']){
		$kt_sql = "&".$_GET['kt'];
		if ($child_count > 0) {
			$Stitle .= " �ʲ��򽸷�";
		} else {
			$Stitle .= " �Τ߽���";
		}
	} else {
		$kt_sql = "&".$_GET['kt']."&";

	}
}
else
{
	$Stitle .= " ����";
}

//xoops2 �����ȥ�����
global $xoopsModule,$xoopsTpl;
if (is_object($xoopsTpl))
{
	$xoops_pagetitle = $xoopsModule->name();
	$xoops_pagetitle = "$Stitle-$xoops_pagetitle";
	$xoopsTpl->assign("xoops_pagetitle",$xoops_pagetitle);
}

$Eref=urlencode($_SERVER['HTTP_REFERER']);
//$i=1;$rank_z=1;$rank=1;

$log_lines=array();
$pre_pt = 0;
//$pre_rank = 1;
//$Clog = $_GET['cl'];
$Clog = 0;
$pre_rank=$pre_rank_z = ($_GET['page']-1)*$EST['hyouji']+1;


$end_no=$EST['hyouji'];
$str_no=$_GET['page']*$EST['hyouji']-$EST['hyouji'];

$time=time();
if($_GET['mode'] == "rank"){
	$start=$time-$EST['rank_kikan']*86400;
	$end=$time;
	$last_mod=date("Y/m/d H:i", $start)."��-��".date("Y/m/d H:i", $end);
}
elseif($_GET['mode'] == "rank_bf"){
	$start=$time-$EST['rank_kikan']*172800;
	$end=$time-$EST['rank_kikan']*86400+1;
	$last_mod=date("Y/m/d H:i", $start)."��-��".date("Y/m/d H:i", $end);
}
else {
	$start=0;
	$end=$time;
	$last_mod="��-��".date("Y/m/d H:i", $end);
}

if ($start) {
	if ($_GET['kt']){
		$query="SELECT r.id, COUNT(r.id) AS pt, l.category, l.id
		FROM $EST[sqltb]rank r, $EST[sqltb]log l
		WHERE l.id = r.id and r.time BETWEEN $start AND $end and l.category LIKE '%$kt_sql%'
		GROUP BY r.id";
	} else {
		$query="SELECT id,COUNT(*) AS pt FROM $EST[sqltb]rank WHERE time BETWEEN $start AND $end GROUP BY id";
	}
} else {
	if ($_GET['kt']){
		$query="SELECT * FROM $EST[sqltb]log WHERE category LIKE '%$kt_sql%'";
	} else {
		$query="SELECT * FROM $EST[sqltb]log";
	}
}

if (!$Clog) {
	$result = $xoopsDB->query($query." LIMIT ".$EST['rank_best']) or die("Query failed rank109 $query");
	$Clog = mysql_num_rows($result);
}

$query .= $start? " ORDER BY pt DESC" : " ORDER BY count DESC";

$query .= " LIMIT $str_no , $end_no";

$result = $xoopsDB->query($query) or die("Query failed rank109 $query");
while($Rank = mysql_fetch_array($result)){
	if (isset($Rank['count_rev'])) {
		$Slog = $Rank;
		$Slog['pt'] = $Rank['count'];
	} else {
		$query="SELECT * FROM $EST[sqltb]log WHERE id='$Rank[id]' LIMIT 1";
		$result2 = $xoopsDB->query($query) or die("Query failed rank120 $query");
		$Slog = mysql_fetch_row($result2);
		$Slog['pt'] = $Rank['pt'];
	}
	if($Slog[0]){
		$Slog[6] = str_replace('<br>', "\n", $Slog[6]);
		if ($EST['syoukai_br'] == 2) {
			$Slog[6] = $myts->displayTarea(unhtmlspecialchars($Slog[6]));
		} else if ($EST['syoukai_br'] == 1) {
			$Slog[6] = nl2br($Slog[6]);
		}
		array_push($log_lines,$Slog);
	}
}

#�ʥӥ��������С���ɽ��
$navi = "";
$kt=explode("_",$_GET['kt']); array_pop($kt);
$temp_kt = "";
foreach ($kt as $tmp){
	$temp_kt .= $tmp;
	$navi .= "<a href=\"$Ekt$temp_kt\">$ganes[$temp_kt]</a> &gt; ";
	$temp_kt .="_";
}
require "$EST[temp_path]rank.html";
exit;

#(t1)��å��������̽���(mes)
#��:mes($arg1,$arg2,$arg3);
#��ǽ:��å��������̤���Ϥ���
#����:$arg1=>ɽ�������å�����
#     $arg2=>�ڡ����Υ����ȥ�(��ά���ϡ֥�å��������̡�)
#     $arg3=>��JavaScript�ˤ������ץܥ���ɽ��=java
#            ��$ENV{'HTTP_REFERER'}��Ȥ����=env
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
		$BACK_URL="��<a href=\"$_SERVER[HTTP_REFERER]\">���</a>��";
	}
	elseif(!$arg3){$BACK_URL="";}
	else{$BACK_URL="��<a href=\"$arg3\">���</a>��";}
	require "$EST[temp_path]mes.html";
	exit;
}

?>