<?php
// Yomi-Serch[XOOPS��] ���o�^�����o�̓X�N���v�g
//              by nao-pon (http://hypweb.net/)
//
// �g�p���@
// HTML����JavaScript�Ƃ��ČĂяo���܂��B
// 
// �g�p��
// ���o�^:<script language="JavaScript" src="http://XOOPS�̃��[�g/modules/yomi/count.js.php"></script>�T�C�g

require 'pl/cfg.php';

$db = mysql_connect($EST['host'],$EST['sqlid'],$EST['sqlpass']);
mysql_select_db($EST['sqldb'],$db);
$query = "SELECT COUNT(*) FROM ".$EST['sqltb']."log";
//echo $query;
$result = mysql_query($query) or die("Query failed $query");
list($count) = mysql_fetch_row($result); #���o�^��

echo "document.open();
document.write(\"{$count}\");
document.close();";

mysql_close($db);
?>