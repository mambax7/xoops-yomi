<?php
////////////////////// special blocks ///////////////////////
include_once(XOOPS_ROOT_PATH."/modules/yomi/blocks/yomi_block_func.php");
function b_yomi_new_s($options)
{
	global $xoopsDB;

	$block = array();

	$block['title'] = "���奵����(Yomi������)";
	$block['content'] = "";

	//$kensu = $options[0];#ɽ��������
	$kensu = $options[0] * $options[1];#ɽ��������
	$query="SELECT * FROM ".$xoopsDB->prefix("yomi_log")." WHERE id > 0 AND renew = 0 ORDER BY stamp DESC LIMIT ".$kensu;
	$result = $xoopsDB->query($query) or die("Query failed rank109 $query");

	$block['content'] = b_yomi_show_cols($result,$options[0],$options[2],$options[3],$options[4],$options[5]);

	return $block;

}
function b_yomi_renew_s($options)
{
	global $xoopsDB;

	$block = array();

	$block['title'] = "����������(Yomi������)";
	$block['content'] = "";

	$kensu = $options[0] * $options[1];#ɽ��������
	$query="SELECT * FROM ".$xoopsDB->prefix("yomi_log")." WHERE id > 0 AND renew = 1 ORDER BY stamp DESC LIMIT ".$kensu;
	$result = $xoopsDB->query($query) or die("Query failed rank109 $query");

	$block['content'] = b_yomi_show_cols($result,$options[0],$options[2],$options[3],$options[4],$options[5]);

	return $block;

}

function b_yomi_s_edit($options) {
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
	$form .= "</table>";
	
	return $form;
}
?>