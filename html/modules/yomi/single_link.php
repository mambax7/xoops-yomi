<?php
// For XOOPS
include("header.php");

include('init.php');

if(ini_get("magic_quotes_gpc")) {
	$_GET = array_map("stripslashes", $_GET);
}
$_GET = array_map("htmlspecialchars", $_GET);

if(!$_GET['page']){$_GET['page']=1;}

#(1)�������ɽ������(search)
#�����ͤ�����
if(!$_GET['item_id']){$_GET['item_id']=$_POST['item_id'];}
if(preg_match("/\D/", $_GET['item_id']) || (!$_GET['item_id'])){mes("�����ͤ������Ǥ�","�ڡ������ꥨ�顼","java");}
$item_id = intval($_GET['item_id']);
$single_link = true;

//$mode = $_GET['mode'];
//$order = intval($_GET['order']);
//$comment_id = intval($_GET['comment_id']);


#���ɽ��

##�ե�������ɤ߹��ߡ������ǡ������������
$Clog=open_for_search($item_id);

$log_lines = $write;
unset($write);
##����@write���˴�

$myts =& MyTextsanitizer::getInstance();

// �إå����ڡ����޻ߥ��ƥ����Ƚ��
EST_reg();
$_no_ad_space = false;
foreach($log_lines as $key => $Slog)
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
	$Slog[6] = str_replace('<br>', "\n", $Slog[6]);
	if ($EST['syoukai_br'] == 2) {
		$log_lines[$key][6] = $myts->displayTarea(unhtmlspecialchars($Slog[6]));
	} else if ($EST['syoukai_br'] == 1) {
		$log_lines[$key][6] = nl2br($Slog[6]);
	}
}

$Stitle = $Slog[1];

if (is_object($xoopsTpl)) {
	// For comments with d3forum
	$module_config =& $config_handler->getConfigsByCat(0, $xoopsModule->mid());
	$content = array();
	$content['item_id'] = $item_id;
	$content['subject'] = htmlspecialchars($Stitle);
	$xoopsTpl->assign(
		array(
			'mod_config' => $module_config,
			'mydirname'  => 'yomi',
			'content'    => $content
		)
	);
}

//xoops2 �����ȥ�����
//global $xoopsModule,$xoopsTpl;
if (is_object($xoopsTpl))
{
	$xoops_pagetitle = $xoopsModule->name();
	$xoops_pagetitle = "$Stitle - $xoops_pagetitle";
	$xoopsTpl->assign("xoops_pagetitle",$xoops_pagetitle);
}

require $EST['temp_path']."search.html";

include("footer.php");

exit;


function open_for_search($item_id){
	global $xoopsDB, $EST, $write;
	$i = 0;
	$query = "SELECT * FROM {$EST['sqltb']}log WHERE id=".$item_id;
	##���������¹�
	$result = $xoopsDB->query($query) or die("Query failed1");
	if ($write[0] = $xoopsDB->fetchRow($result)) {
		$i = 1;
	}
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