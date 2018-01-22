<?php
include_once(XOOPS_ROOT_PATH."/modules/yomi/blocks/yomi_block_func.php");
function b_yomi_s3($options) {

	global $xoopsDB;

	$block = array();

	$block['title'] = "������ɽ��(Yomi)";
	$block['content'] = "";

	$kensu = $options[0]*$options[1];#ɽ��������

	$log_lines=array(); $Clog=0;

	if($options[6] == 'm1')
		$where = " WHERE mark LIKE '1%'";
	elseif($options[6] == 'm2')
		$where = " WHERE mark LIKE '%1'";
	else
		$where = "";

	$query = "SELECT COUNT(*) FROM ".$xoopsDB->prefix("yomi_log").$where.";";
	$result = $xoopsDB->query($query) or die("Query failed $query");
	list($count) = $xoopsDB->fetchRow($result);
	srand(b_yomi_s3_make_seed());
	$limit = rand(0,$count-$kensu);

	$query = "SELECT * FROM ".$xoopsDB->prefix("yomi_log").$where." ORDER BY rand() LIMIT $limit,$kensu;";
	$result = $xoopsDB->query($query) or die("Query failed $query");

	if($result){
		$block['content'] .= b_yomi_show_cols($result,$options[0],$options[2],$options[3],$options[4],$options[5]);
	} else {
		$block['content'] .= "�����ǡ����Ϥ���ޤ���";
	}

	return $block;
}
function b_yomi_s3_edit($options) {
	$form = "<table>";
	$form .= "<tr><td>ɽ�����</td>";
	$form .= "<td><input type='text' name='options[]' value='".$options[0]."' /></td></tr>";
	$form .= "<tr><td>ɽ���Կ�</td>";
	$form .= "<td><input type='text' name='options[]' value='".$options[1]."' /></td></tr>";
	$form .= "<tr><td>������̾����ʸ����(Ⱦ��ʸ����)</td>";
	$form .= "<td><input type='text' name='options[]' value='".$options[2]."' /></td></tr>";
	$form .= "<tr><td>��������������ʸ����(Ⱦ��ʸ����)<br />�� 0 ������ɽ�����ޤ���</td>";
	$form .= "<td><input type='text' name='options[]' value='".$options[3]."' /></td></tr>";
	$form .= "<tr><td>�Хʡ�������(px)</td>";
	$form .= "<td><input type='text' name='options[]' value='".$options[4]."' /></td></tr>";
	$form .= "<tr><td>�Хʡ������(px)</td>";
	$form .= "<td><input type='text' name='options[]' value='".$options[5]."' /></td></tr>";
	$form .= "<tr><td colspan=2>���Хʡ���,�Хʡ��ⶦ�� 0 �����ξ��Хʡ���ɽ�����ޤ���<br / >�ɤ��餫������0�ʾ�ǻ��ꤷ�����ϡ������ͤΤߥ��åȤ���ޤ���</td></tr>";
	$form .= "<tr><td>�оݥޡ��� 'm1' or 'm2' (̵����Ǥ��٤��о�)</td>";
	$form .= "<td><input type='text' name='options[]' value='".$options[6]."' /></td></tr>";
	$form .= "</table>";

	return $form;
}

// �ޥ�����ǥ����ɤ����ꤹ��
function b_yomi_s3_make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}

?>