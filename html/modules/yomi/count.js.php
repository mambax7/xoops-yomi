<?php
// Yomi-Serch[XOOPS��] ����Ͽ������ϥ�����ץ�
//              by nao-pon (http://hypweb.net/)
//
// ������ˡ
// HTML����JavaScript�Ȥ��ƸƤӽФ��ޤ���
// 
// ������
// ����Ͽ:<script language="JavaScript" src="http://XOOPS�Υ롼��/modules/yomi/count.js.php"></script>������

define('_LEGACY_PREVENT_LOAD_CORE_', TRUE); // for XOOPS Cube Legacy
$xoopsOption['nocommon'] = 1;
require '../../mainfile.php';
require 'pl/cfg.php';

$db = mysql_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS) or die(mysql_error());
mysql_select_db(XOOPS_DB_NAME,$db);

$query = "SELECT COUNT(*) FROM `".$EST['sqltb']."log`";
//echo $query;
$result = mysql_query($query) or die(mysql_error());
list($count) = mysql_fetch_row($result); #����Ͽ��

echo "document.open();
document.write(\"{$count}\");
document.close();";

mysql_close($db);
?>