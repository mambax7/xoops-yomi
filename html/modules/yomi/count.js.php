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

$link = mysqli_connect(XOOPS_DB_HOST, XOOPS_DB_USER, XOOPS_DB_PASS, XOOPS_DB_NAME) or die('MySQL Connect Error');

$query = "SELECT COUNT(*) FROM `".$EST['sqltb']."log`";

$result = mysqli_query($link, $query) or die(mysqli_error($link));
list($count) = mysqli_fetch_row($result); #����Ͽ��
mysqli_close($link);

header('Content-Type:text/javascript');
echo "document.getElementById('yomi_bcat_count').innerHTML = '{$count}';";
