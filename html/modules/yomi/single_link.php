<?php
// For XOOPS
include("header.php");

include('init.php');

if(ini_get("magic_quotes_gpc")) {
	$_GET = array_map("stripslashes", $_GET);
}
$_GET = array_map("htmlspecialchars", $_GET);

if(!$_GET[page]){$_GET[page]=1;}

#(1)�������ɽ������(search)
#�����ͤ�����
if(!$_GET['item_id']){$_GET['item_id']=$_POST['item_id'];}
if(preg_match("/\D/", $_GET['item_id']) || (!$_GET['item_id'])){mes("�����ͤ������Ǥ�","�ڡ������ꥨ�顼","java");}
$item_id = $_GET['item_id'];

#���ɽ��

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

$Stitle = $Slog[1];

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


function open_for_search($target_kt, $target_day, $sort){
	global $xoopsDB, $EST, $item_id, $write;
	$i = 0;
	$query = "SELECT * FROM $EST[sqltb]log WHERE id=".$item_id;
	##���������¹�
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
	echo "<table style=\"width:90%;padding:8px;\" align=\"center\" width=\"90%\" cellpadding=8>";
	foreach ($location_list as $list){
		list($Dengine,$Durl)=explode("<>",$list);
		if($T_flag==5){echo "</tr>"; $T_flag=1;}
		if($T_flag==1){echo "<tr>";}
		?>
<td class="yomi-s" style="text-align:center;"><a href="<?php echo $Durl?>" target="<?php echo $_POST['target']?>"><font size="+1"><?php echo $Dengine?></font></a></td>
<?php
		$T_flag++;
	}
	if($T_flag!=2){echo "</tr>";}
	echo "</table>";
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