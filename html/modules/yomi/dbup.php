<?php
function install_header(){
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<title>Yomi���������ǡ����١������åץ��졼��</title>
	<meta http-equiv='Content-Type' content='text/html; charset=EUC-JP' />
	</head>
	<body>
	<div style='text-align:center'><img src='img/yomi_logo.gif' />
	<h3>Yomi���������ǡ����١������åץ��졼��</h3><br />
<?php
}

function install_footer(){
	global $PHP_SELF;
?>
	<br /><br />

	<a href="../../">XOOPS�ȥåפ���롣</a>
	</div>
	</body>
	</html>
<?php
}
if ( !isset($action) || $action == "" ) {
	$action = "message";
}

if ( $action == "message" ) {
	install_header();
	echo "
	<table width='70%' border='0'><tr><td colspan='2'>Yomi������ [ XOOPS ] �ǡ����١�����ǿ��ΥС�������Ѥ˥��åץ��졼�ɤ��ޤ���</td></tr>
	<tr><td>��</td><td><span style='color:#ff0000;font-weight:bold;'>�����˥ǡ����١����ΥХå����åפ򤹤뤳�Ȥ򶯤������ᤷ�ޤ���</span></td></tr>
	</table>
	";
	echo "<p>�ʲ��Ρ֥��åץ��졼�ɳ��ϡפ򥯥�å�����ȡ����åץ��졼�ɤ���ޤ���</p>";
	echo "<form action='".$PHP_SELF."' method='post'><input type='submit' value='���åץ��졼�ɳ���' /><input type='hidden' value='upgrade' name='action' /></form>";
	install_footer();
	exit();
}

if ( $action == "upgrade" ) {
	include("../../mainfile.php");
	install_header();
	echo "<h4>�ǡ����١������åץ��졼�ɳ���</h4>\n";
	$error = array();
	
	echo "<p>...Updating</p>\n";

	//�ƹ��ܥ��������ѹ�
	$sql = "ALTER TABLE `".$xoopsDB->prefix("yomi_log")."`
	 CHANGE `title` `title` VARCHAR( 255 ) DEFAULT NULL , 
	 CHANGE `name` `name` VARCHAR( 255 ) DEFAULT NULL , 
	 CHANGE `mail` `mail` VARCHAR( 255 ) DEFAULT NULL ,
	 CHANGE `category` `category` VARCHAR( 255 ) DEFAULT NULL ,
	 CHANGE `banner` `banner` VARCHAR( 255 ) DEFAULT NULL ,
	 CHANGE `url` `url` VARCHAR( 255 ) DEFAULT NULL ,
	 CHANGE `passwd` `passwd` VARCHAR( 255 ) DEFAULT NULL ,
	 CHANGE `keywd` `keywd` VARCHAR( 255 ) DEFAULT NULL,
	 CHANGE `message` `message` TEXT DEFAULT NULL,
	 CHANGE `comment` `comment` TEXT DEFAULT NULL";

	$result = $xoopsDB->queryF($sql);
	if (!$result) {
		$error[] = "Failed ALTER TABLE ".$xoopsDB->prefix("yomi_log")."";
	}

	//�ơ��֥�ع����ɲ�
	$result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("yomi_log")."` ADD `uid` INT( 5 ) UNSIGNED DEFAULT '0' NOT NULL");
	if (!$result) {
		$error[] = "".$xoopsDB->prefix("yomi_log")."�ϥ��åץ��졼�ɺѤߤǤ���[1]<br />";
	} else {

		$result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("yomi_log")."` ADD INDEX ( `uid` )");
		if (!$result) {
			$error[] = "Failed ALTER TABLE ".$xoopsDB->prefix("yomi_log")." uid";
		}
	}

	//�ơ��֥�ع����ɲ� Ver 0.84b6�ʹ�
	$sql = "ALTER TABLE `".$xoopsDB->prefix("yomi_log")."`
  ADD rating double(6,4) NOT NULL default '0.0000',
  ADD votes int(11) unsigned NOT NULL default '0',
  ADD comments int(11) unsigned NOT NULL default '0'";

	$result = $xoopsDB->queryF($sql);
	if (!$result) {
		$error[] = "".$xoopsDB->prefix("yomi_log")."�ϥ��åץ��졼�ɺѤߤǤ���[2]<br />";
	} else {

		$result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("yomi_log")."` ADD INDEX ( `uid` )");
		if (!$result) {
			$error[] = "Failed ALTER TABLE ".$xoopsDB->prefix("yomi_log")." uid";
		}
	}

	//�ơ��֥��ɲ� Ver 0.84b6�ʹ�
	$sql = "CREATE TABLE `".$xoopsDB->prefix("yomi_votedata")."`(
  ratingid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) unsigned NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname)
) TYPE=MyISAM;";
	$result = $xoopsDB->queryF($sql);
	if (!$result) {
		$error[] = "".$xoopsDB->prefix("yomi_votedata")."�Ϻ����ѤߤǤ���<br />";
	} else {

		$result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("yomi_log")."` ADD INDEX ( `uid` )");
		if (!$result) {
			$error[] = "Failed ALTER TABLE ".$xoopsDB->prefix("yomi_log")." uid";
		}
	}

	//�ơ��֥��ɲ� Ver 0.84b6�ʹ�
	$sql = "CREATE TABLE `".$xoopsDB->prefix("yomi_comments")."`(
  comment_id int(8) unsigned NOT NULL auto_increment,
  pid int(8) unsigned NOT NULL default '0',
  item_id int(8) unsigned NOT NULL default '0',
  date int(10) unsigned NOT NULL default '0',
  user_id int(5) unsigned NOT NULL default '0',
  ip varchar(15) NOT NULL default '',
  subject varchar(255) NOT NULL default '',
  comment text NOT NULL,
  nohtml tinyint(1) unsigned NOT NULL default '0',
  nosmiley tinyint(1) unsigned NOT NULL default '0',
  noxcode tinyint(1) unsigned NOT NULL default '0',
  icon varchar(25) NOT NULL default '',
  PRIMARY KEY  (comment_id),
  KEY pid (pid),
  KEY item_id (item_id),
  KEY user_id (user_id),
  KEY subject (subject(40))
) TYPE=MyISAM;";
	$result = $xoopsDB->queryF($sql);
	if (!$result) {
		$error[] = "".$xoopsDB->prefix("yomi_comments")."�Ϻ����ѤߤǤ���<br />";
	} else {

		$result = $xoopsDB->queryF("ALTER TABLE `".$xoopsDB->prefix("yomi_log")."` ADD INDEX ( `uid` )");
		if (!$result) {
			$error[] = "Failed ALTER TABLE ".$xoopsDB->prefix("yomi_log")." uid";
		}
	}
	
if ( count($error) ) {

	foreach( $error as $err ) {
		echo $err."<br>";
	}
	echo "�ɲå��åץ��졼�ɤϴ�λ���ޤ�����<br />";
	echo "�ʸ塢���åץ��졼�ɥ�����ץȤ�ɬ�פʤ��ΤǺ�����Ƥ���������<br /><br />";
} else {
	echo "���åץ��졼�ɤϴ�λ���ޤ�����<br />";
	echo "�ʸ塢���åץ��졼�ɥ�����ץȤ�ɬ�פʤ��ΤǺ�����Ƥ���������<br /><br />";

}
	install_footer();
}
?>