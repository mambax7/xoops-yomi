<?php
################################################################
# Yomi-Search Ver4 [���������󥸥�] (Since:1999/09/22)
#   (C) 1999-2001 by yomi
#   E�᡼��: yomi@pekori.to
#   �ۡ���ڡ���: http://yomi.pekori.to/
################################################################

## ---[���ѵ���]------------------------------------------------------------+
## 1. ���Υ�����ץȤϥե꡼���եȤǤ������Υ�����ץȤ���Ѥ���
##    �����ʤ�»�����Ф��ƺ�Ԥϰ��ڤ���Ǥ���餤�ޤ���
## 2. ���Υ�����ץȤ���Ѥ������������ѵ���(http://yomi.pekori.to/kiyaku.html)
##    ��Ʊ�դ�����ΤȤߤʤ����Ƥ��������ޤ���
##    �����Ѥˤʤ�����ɬ�����ɤߤ���������
## 3. Ʊ���Ρ֥������� (new.gif/recom.gif/sougogif.gif) �פ������
##   �ֵ�����ȥ������������  (http://www.ushikai.com/)�פ˵�°���Ƥ��ޤ���
## -------------------------------------------------------------------------+

if (is_readable("shorturl.php")) include("shorturl.php");

if (defined("YOMI_SHORTURL") && YOMI_SHORTURL)
{
	if(!empty($_SERVER["PATH_INFO"]) && preg_match("/^\/(.+)$/",$_SERVER["PATH_INFO"],$match))
	{
		$match[1] = preg_replace("/\/$/","",$match[1]);
		if ($match[1] == "new") $_GET['mode'] = "new";
		else if ($match[1] == "renew") $_GET['mode'] = "renew";
		else if ($match[1] == "m1") $_GET['mode'] = "m1";
		else if ($match[1] == "m2") $_GET['mode'] = "m2";
		else
		{
			$parms = explode("/",$match[1]);
			$_GET['kt'] = join("_",$parms);
			$_GET['mode'] = "kt";
		}
	}
}

//For XOOPS
include("header.php");

include('init.php');

$EST['script'] = $EST['cgi_path_url'] . $EST['script'];
$EST['search'] = $EST['cgi_path_url'] . $EST['search'];
$EST['rank'] = $EST['cgi_path_url'] . $EST['rank'];
$EST['admin'] = $EST['cgi_path_url'] . $EST['admin'];
$EST['html_path_url'] = $EST['cgi_path_url'] . $EST['html_path_url'];
$EST['img_path_url'] = $EST['cgi_path_url'] . $EST['img_path_url'];

if (!isset($_GET['mode'])) $_GET['mode']="";
#�ƥ⡼�ɤ�ʬ��
#-----------------#
if($_GET['mode']){
	if(!$EST['home']){$EST['home']=$EST['script'];}
	if($_GET['mode'] == "kt"){
		#�ƥ��ƥ���λ���ɽ�������ȥ�
		$Stitle=$ganes[$_GET['kt']];
		list($Spre_kt_file)=explode("_",$_GET['kt']);
	}
	elseif($_GET['mode'] == "new"){$Stitle="���奵����"; $Spre_kt_file="new_ys";} #���奵���Ȥ�ɽ�������ȥ�,�ե�����̾
	elseif($_GET['mode'] == "renew"){$Stitle="����������"; $Spre_kt_file="renew_ys";} #���������Ȥ�ɽ�������ȥ�,�ե�����̾
	elseif($_GET['mode'] == "m1"){$Stitle="Ķ�������᥵����"; $Spre_kt_file="m1_ys";} #m1�����Ȥ�ɽ�������ȥ�,�ե�����̾
	elseif($_GET['mode'] == "m2"){$Stitle="�������᥵����"; $Spre_kt_file="m2_ys";} #m2�����Ȥ�ɽ�������ȥ�,�ե�����̾
	elseif($_GET['mode'] == "random"){random();} #�����ॸ����
	elseif($_GET['mode'] == "link"){yomi_link();} #��󥯥�����
	else{mes("���ꤷ���⡼�ɤ�¸�ߤ��ޤ���(mode=".$_GET['mode'].")","�⡼�����򥨥顼","java");}
	##�ڡ�������
	
	$_GET['page']=(isset($_GET['page']))? preg_replace("/\D/", "", $_GET['page']) : 1 ;
	if($_GET['page']<1 || $_GET['page']>1000){$_GET['page']=1;}
	require $EST['temp_path']."kt.html";
}elseif($EST['home'] && $EST['top']){
	location($EST['home']);
}else{
	$query = "SELECT COUNT(*) FROM ".$EST['sqltb']."log";
	$result = $xoopsDB->query($query) or die("Query failed yomi40 $query");
	$tmp = mysql_fetch_row($result); #����Ͽ��
	$Cpre_gane=$tmp[0];
	$CK_data=get_cookie();
	if (isset($CK_data[7])) $EST['defo_hyouji']=$CK_data[7];
	require $EST['temp_path']."top.html";
}
exit;

#(1)��å��������̽���(mes)
#��:mes($arg1,$arg2,$arg3);
#��ǽ:��å��������̤���Ϥ���
#����:$arg1=>ɽ�������å�����
#     $arg2=>�ڡ����Υ����ȥ�(��ά���ϡ֥�å��������̡�)
#     $arg3=>��JavaScript�ˤ������ץܥ���ɽ��=java
#            ��HTTP_REFERER��Ȥ����=env
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
		$BACK_URL="��<a href=\"".$_SERVER['HTTP_REFERER']."\">���</a>��";
	}
	elseif(!$arg3){$BACK_URL="";}
	else{$BACK_URL="��<a href=\"$arg3\">���</a>��";}
	require $EST['temp_path']."mes.html";
	exit;
}

#(2)�����ॸ����(random)
function random(){
	global $EST,$xoopsDB;
	$i=1;
	$query = "SELECT * FROM ".$EST['sqltb']."log";
	$result = $xoopsDB->query($query) or die("Query failed yomi85 $query");
	$total_url=mysql_num_rows($result);
	list($usec, $sec) = explode(' ', microtime());
	srand((float)$sec + ((float)$usec * 100000));
	$id = rand(1, $total_url);
	$query = "SELECT url FROM ".$EST['sqltb']."log";
	$result = $xoopsDB->query($query) or die("Query failed yomi91");
	while($tmp = mysql_fetch_assoc($result)){
		if($i == $id){
			break;
		}
		$i++;
	}
	//mysql_close($link);
	location($tmp['url']);
	exit;
}

#(3)��󥯥����׽���(link)
function yomi_link(){
	$_GET['id']=preg_replace("/\D/", "", $_GET['id']);
	if($_GET['id']){
		#referer�����å�
		if(!$_SERVER['HTTP_REFERER']){$fl=1;} #referer��̵���Ȥ��˥�����Ȥ��ʤ����ˤϤ��ιԤ���
		$ref_list=explode(",",$EST['rank_ref']);
		if(!$EST['rank_ref']){$fl=1;}
		else{
			foreach($ref_list as $tmp){
				if(strstr($_SERVER['HTTP_REFERER'],$tmp)){$fl=1;}
			}
		}
		if($fl){
			$_GET['id']=str_replace("\n", "", $_GET['id']);
			$fp=fopen($EST['log_path']."rank_temp_ys.cgi", "a");
			flock($fp, LOCK_EX);
			fputs($fp, $_GET['id']."<>" . time() . "<>".$_SERVER['REMOTE_ADDR']."\n");
			fclose($fp);
		}
	}
	if($_GET['url']){location($_GET['url']);}
}
##-- end of yomi.php --##
