<?php
// init.php
// by nao-pon http://hypweb.net/

//error_reporting(E_ERROR);

global $ganes, $gane_top, $EST;

require_once dirname(__FILE__).'/pl/cfg.php';
require_once dirname(__FILE__).'/pl/temp.php';

#�С���������
include dirname(__FILE__).'/version.php';
$ver = $_md_yomi_info['ver'];
$x_ver = $_md_yomi_info['x_ver'];

$EST['shorturl'] = (defined("YOMI_SHORTURL"))? YOMI_SHORTURL : "";

//�ʥӥ��������Ρ֥ۡ���פؤΥ��
if (empty($EST['shorturl']))
	define('YOMI_HOME_URL','<a href="'.$EST['home'].'">'._MD_YOMI_TOP.'</a>');
else
	define('YOMI_HOME_URL','<a href="'.XOOPS_URL.'/'.$EST['shorturl'].'/">'._MD_YOMI_TOP.'</a>');

#�ǡ��������Υե�����ɿ�
$Efld=16;  #0��15�ޤ�

#CGI/HTML�����ɽ��������
if (empty($EST['shorturl']))
	$Ekt = $EST['cgi_path_url'].$EST['script']."?mode=kt&kt=";
else
	$Ekt = XOOPS_URL."/".$EST['shorturl']."/";
$Eend="";

?>