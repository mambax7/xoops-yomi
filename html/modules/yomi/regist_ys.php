<?php
// For XOOPS
include("header.php");
//

if( strtolower($_SERVER['REQUEST_METHOD']) == "post" )
{
	if ( ! $xoopsHypTicket->check() )
	{
		ob_end_clean();
		redirect_header(XOOPS_URL.'/',1,$xoopsHypTicket->getErrors());
	}
}

include('init.php');

EST_reg();
################################################################
# Yomi-Search�ѥǡ�����Ͽ�ѥץ����
################################################################
#(1)�ץ�ӥ塼����(preview)
#(2)����ڤ����ե�����(no_link)
#(3)������Ͽ�¹�(act_regist)
#(4)����������Τ���Υѥ����ǧ��(enter)
#(5)�إ�פ�ɽ��(help)
#(6)��Ͽ�����ѹ�(act_mente)
#(7)�ѥ���ɤκ�ȯ�ԡ��ѹ�(act_repass)
#(8)����¹�(act_del)
#(9)��Ͽ����(������Ͽ��������������Ͽ����Ͽ�����ѹ�)(regist)

#(f1)��Ͽ��̲��̽���(PR_kt)
#(f2)��å��������̽���(mes)
#(f3)�������ƤΥ����å�(check)
#(f4)���ƥ����ɽ��1(PR_preview_kt1)
#(f5)���ƥ����ɽ��1(PR_preview_kt2)
#(f6)�������Ƥ�����(join_fld)
#(f7)������Ͽ�Ѥ�ID�����&����URL��Ͽ�����å�(get_id_url_ch)
#(f8)��Ͽ��̲��̽���(PRend)
#(f9)�ޡ���Form�����Բ��̽���(PR_mark)

$Eref=$_SERVER['HTTP_REFERER'];
$_SERVER['HTTP_REFERER']="";
#�������̤�GETquery��POST���Ѵ���
if($_GET['changer'] == 'admin' && $_GET['in_mode'] == 'mente' && $_GET['Smode_name'] == 'mente') {
	$_POST['changer']=$_GET['changer'];
	$_POST['in_mode']=$_GET['in_mode'];
	$_POST['Smode_name']=$_GET['Smode_name'];
	$id = $_POST['id'] = $_GET['id'];
	$_POST['pass']=$_GET['pass'];
}
#-----------------#
# �ƥե�����ɤΥǡ������$P���פ�����
#$Pdata[0]=�ǡ���ID,$Pdata[1]=�����ȥ�,$Pdata[2]=�ۡ���ڡ�����URL,$Pdata[3]=�ޡ����ǡ���
#$Pdata[4]=������,$Pdata[5]=�ѥ����,$Pdata[6]=�Ҳ�ʸ,$Pdata[7]=�����ͥ�����
#$Pdata[8]=��̾��,$Pdata[9]=�᡼�륢�ɥ쥹,$Pdata[10]=���ƥ���(�ꥹ��),$Pdata[11]=time
#$Pdata[12]=�Хʡ�URL,$Pdata[13]=�����ե饰,$Pdata[14]=IP,$Pdata[15]=�������
# $Pmode=>������Υ⡼��
# $_POST['in_mode']=>������������⡼��(�ʤ�,new_dairi,mente,form)
# $Smode_name=>�ƥ⡼�ɤ�Ƚ���Ѥ������ѿ�(�ʤ�,new_dairi,mente)
# $_POST['changer'}=>�ѹ���(�ʤ�,admin)

// POST���Ϥ򥵥˥�����
for($kt_no=1; $kt_no <= $EST_reg['kt_max']; $kt_no++){
	$_POST["Fkt$kt_no"] = preg_replace("/[^0-9_]+/","",$_POST["Fkt$kt_no"]);
}

##������������($_POST['in_mode'})
if(!isset($_POST['in_mode'])){ #������Ͽ()
	$Pdata=array("","","http://","","","","","","","",$_POST['kt'],"","http://","","","");
}
elseif($_POST['in_mode'] == "new_dairi"){ #������������Ͽ(new_dairi)
}
elseif($_POST['in_mode'] == "mente"){ #�����ѹ�(mente)
	$query = "SELECT * FROM $EST[sqltb]log WHERE id='$_POST[id]' LIMIT 1";
	$result = $xoopsDB->query($query) or die("Query failed");
	$Pdata = mysql_fetch_row($result);
	if($Pdata){
		if ($is_admin != 1){
			$cr_pass=crypt($_POST['pass'],$Pdata[5]);
			if(($_POST['changer'] != "admin" && $Pdata[5] != $cr_pass) || (!$_POST['pass'])){mes("�ѥ���ɤ��㤤�ޤ�","�ѥ����ǧ�ڥ��顼","java");}
		}
	}
	else {mes("��������ǡ����Ϥ���ޤ���","���顼","java");}
}
elseif($_POST['in_mode'] == "form"){ #��������(form)
	$_POST['Fkt']="";
	for($kt_no=1; $kt_no <= $EST_reg['kt_max']; $kt_no++){
		$_POST['Fkt'] .= "&".$_POST["Fkt$kt_no"];
	}
	$Pdata=array("",$_POST['Ftitle'],$_POST['Furl'],"","",$_POST['Fpass'],$_POST['Fsyoukai'],"",$_POST['Fname'],$_POST['Femail'],$_POST['Fkt'],"",$_POST['Fbana_url'],"","",$_POST['Fkey'],"",$_POST['Fuid']);
}
else{$Pdata=array();}

#-------------------------------

#(1)�ץ�ӥ塼����(preview)
if(isset($_POST['preview']) && $_POST['preview'] == "on"){
	#����Ͽ�Ԥο�����Ͽ���ˤΤ߻���
	check();
	##����¾������
	#��ߥ�󥯤�̵ͭ
	$MES_sougo[1]=" checked"; $MES_sougo[0]="";
	#�Ҳ�ʸ�β��Ԥ��Ѵ�(<br>��\n)
	$_POST['Fsyoukai']=str_replace("<br>", "\n", $_POST['Fsyoukai']);
	require "$EST[temp_path]regist_new_preview.html";
	exit;
}

#(2)����ڤ����ե�����(&no_link)
elseif($_REQUEST['mode'] == "no_link"){
	if($_GET["pre"] == "on"){
		$Eref=urlencode($Eref);
		$YOMI_TICKET_TAG = YOMI_TICKET_TAG;
		$mes=<<<EOM
�����Ԥˡ�<b>$_GET[title]</b>�פˤĤ��Ƥ����Τ�Ԥ��ޤ�<br>
�����Τ���ץܥ���򲡤��ȴ����Ԥ����ΤǤ��ޤ�
<br><br>
<form action="regist_ys.php" method=post target="">
  $YOMI_TICKET_TAG
  <input type=hidden name=mode value="no_link">
  <input type=hidden name=id value="$_GET[id]">
  <input type=hidden name=pre value="">
  <input type=hidden name=ref value="$Eref">
  <input type=hidden name=title value="$_GET[title]">
	<ul>
	[���μ���]<br>
		<input type=checkbox name=type_no_link value="1">����ڤ�<br>
		<input type=checkbox name=type_move value="2">�ۡ���ڡ�����ž<br>
		<input type=checkbox name=type_bana_no_link value="3">�Хʡ�����ڤ�<br>
		<input type=checkbox name=type_ill value="4">�����ȿ[<a href="$EST[cgi_path_url]regist_ys.php?mode=new">����Ϥ�����</a>]<br>
		<input type=checkbox name=type_other value="5">����¾(��������ˤ⤴������������)<br>
	<br>
	[������](ɬ�פ�����Ф�������������)<br>
		<textarea name=com cols=40 rows=4></textarea><br>
	<br>
	[��̾��](Ǥ��)<br>
	<input type=text name=c_name><br>
	[E-Mail](Ǥ��)<br>
	<input type=text name=c_email><br>
	</ul>
</ul>
<center>
  <input type=submit value="���Τ���">
</center>
</form>
<hr width="90%">
<center>
<form><input type=button value=" ���β��̤���� " onClick="history.back()"></form>
</center>
EOM;
		mes($mes,"�����Ԥؤ����β���");
	}
	$_POST['id']=preg_replace("/\D/", "", $_POST['id']);
	if($_POST['id'] && (strstr($_SERVER['HTTP_USER_AGENT'],"Mozilla") || strstr($_SERVER['HTTP_USER_AGENT'],"Lynx") || strstr($_SERVER[HTTP_USER_AGENT],"Opera"))){
		$ip_fl=1;
		if($EST['no_link_ip']){
			$ip=explode(",",$EST['no_link_ip']);
			foreach($ip as $tmp){
				if(strstr($_SERVER['REMOTE_ADDR'],$tmp)){$ip_fl=0;break;}
			}
		}
		if($ip_fl){
			$fl=0;
			#������(����ڤ�=0/�����Ȱ�ž=1/�Хʡ�����ڤ�=2/�����ȿ=3/����¾=4)
			if($_POST['type_no_link']){$Dhoukoku.="1,";$fl=1;}
			if($_POST['type_move']){$Dhoukoku.="2,";$fl=1;}
			if($_POST['type_bana_no_link']){$Dhoukoku.="3,";$fl=1;}
			if($_POST['type_ill']){$Dhoukoku.="4,";$fl=1;}
			if($_POST['type_other']){$Dhoukoku.="5,";$fl=1;}
			if(!$fl){mes("�����μ��̡פ˺����Ĥϥ����å����Ƥ�������","�����å��ߥ�","java");}
			#������
			$Dcom=str_replace("\n", "<br>", $_POST['com']);
			#̾��
			$Dname=str_replace("\n", "", $_POST['c_name']);
			#E-Mail
			$Demail=str_replace("\n", "", $_POST['c_email']);
			if(strlen("$Dcom$Dname$Demail")>500){mes("�����ȡ���̾����E-Mail��ʸ������<br>��פ�250ʸ��(���Ѵ���)�ޤǤǡ����ꤤ���ޤ���","ʸ���������С�","java");}
			$fp=fopen("$EST[log_path]no_link_temp.cgi", "a");
			flock($fp, LOCK_EX);
			fputs($fp, "$_POST[id]<>$_SERVER[REMOTE_ADDR]<>$Dhoukoku<>$Dcom<>$Dname<>$Demail<>\n");
			fclose($fp);
		}
	}
	$_POST[ref]=urldecode($_POST[ref]);
	mes("����𤢤꤬�Ȥ��������ޤ���<br>�����ͤˡ�<b>".htmlspecialchars($_POST['title'])."</b>�פˤĤ��Ƥ����Τ�Ԥ��ޤ���","����𤢤꤬�Ȥ��������ޤ�",$_POST['ref']);
	exit;
}

#������Ͽ�¹�
#(3)������Ͽ�¹�(act_regist)
elseif($_POST['mode'] == "act_regist"){
	#$new=>�ɲåǡ����񤭹�����/$TASK=>�������륫�ƥ���ꥹ��
	#$hyouji_log=>���ɽ���ѤΥ��ǡ���
	#�ѥ����ǧ��(������ǧ��)
	if($_POST['changer'] == "admin" && $is_admin != 1){
		$cr_pass=crypt($_POST['pass'],$EST['pass']);
		if($cr_pass != $EST['pass'] || (!$_POST['pass'])){
			if(!$_SERVER['REMOTE_HOST']){$_SERVER['REMOTE_HOST']=gethostbyaddr($_SERVER['REMOTE_ADDR']);}
			mes("�ѥ���ɤ�ǧ�ڤ˼��Ԥ��ޤ���<br>ǧ�ڤ�������ԥ塼����IP���ɥ쥹��<b>$_SERVER[REMOTE_ADDR]</b><br>ǧ�ڤ�������ԥ塼���Υۥ���̾��<b>$_SERVER[REMOTE_HOST]</b>","�ѥ����ǧ�ڼ���","java");
		}
	}
	check(); #�������ƤΥ����å�
	#ID����&����URL��Ͽ�����å�
	if($EST_reg['nijyu_url']){$new_id=get_id_url_ch(1);}
	else{
		$query = "SELECT id FROM $EST[sqltb]log ORDER BY id DESC LIMIT 1";
		$result = $xoopsDB->query($query) or die("Query failed");
		$num=mysql_fetch_row($result);
		$new_id=++$num[0];
		$fp=fopen("$EST[log_path]$EST[temp_logfile]", "r");
		while($tmp=fgets($fp, 4096)) {
			$Tlog=explode("<>",$tmp);
			if ($new_id <= $Tlog[0]) $new_id = ++$Tlog[0];
		}
		fclose($fp);
	}
	$Slog=join_fld($new_id); #�������Ƥ�����
	$hyouji_log=$Slog;
	if($EST['user_check'] && $_POST['changer'] != "admin" && $_POST['mode'] == "act_regist"){ #<����Ͽ��>
		#����Ͽ���ǡ������ɲý񤭹���
		$Slog[6]=str_replace("\n", "<br>", $Slog[6]);
		$Slog[7]=str_replace("\n", "<br>", $Slog[7]);
		$new=implode("<>",$Slog);
		$new .= "<>\n";
		$fp = fopen("$EST[log_path]$EST[temp_logfile]", "a");
		flock($fp, LOCK_EX);
		fputs ($fp, $new);
		fclose($fp);
		##�᡼�������
		#��̾���դ���ޡ���������
		if($_POST['Fsougo']){$PR_mail_sougo="(link)";}
		else{$PR_mail_sougo="";}
		if($_POST['Fto_admin']){$PR_mail_com="(com)";}
		else{$PR_mail_com="";}
		if($_POST['Fadd_kt']){$PR_mail_kt="(kt)";}
		else{$PR_mail_kt="";}
		$PR_mail_add_line=$PR_mail_sougo . $PR_mail_com . $PR_mail_kt;
		$Slog[6]=str_replace("<br>", "\n", $Slog[6]);
		$Slog[7]=str_replace("<br>", "\n", $Slog[7]);
		if($EST['mail_temp']){require "pl/mail_ys.php";}
		if($EST['mail_to_admin'] && $EST['mail_temp']){ #�����ͤإ᡼������
			sendmail($EST['admin_email'],$Slog[9],"$EST[search_name] ����Ͽ������ޤ���".$PR_mail_add_line,"temp","admin",$Slog,$_POST['Fsougo'],$_POST['Fadd_kt'],$_POST['Fto_admin']);
		}
		if($EST['mail_to_register'] && $EST['mail_temp']){ #��Ͽ�Ԥإ᡼������
			sendmail($Slog[9],$EST['admin_email'],"$EST[search_name] ����Ͽ��λ����","temp","",$Slog,$_POST['Fsougo'],$_POST['Fadd_kt'],$_POST['Fto_admin']);
		}
		//$Slog[6]=str_replace("\n", "<br>", $Slog[6]);
		//$Slog[7]=str_replace("\n", "<br>", $Slog[7]);
		##��Ͽ��̽���
		require "$EST[temp_path]regist_new_end_temp.html";
	} #</����Ͽ��>
	else{ #<������Ͽ��>
		$Slog = array_map("addslashes", $Slog);
		$query = "INSERT INTO $EST[sqltb]log VALUES ('$Slog[0]','$Slog[1]','$Slog[2]','$Slog[3]','$Slog[4]','$Slog[5]','$Slog[6]','$Slog[7]','$Slog[8]','$Slog[9]','$Slog[10]','$Slog[11]','$Slog[12]','$Slog[13]','$Slog[14]','$Slog[15]','$Slog[16]','$Slog[17]',0,0,0)";
		$result = $xoopsDB->query($query) or die("Query failed regist633");
		##��Ͽ�ԤΥ�å���������¸��������ξ��
		if(($_POST['Fadd_kt'] || $_POST['Fto_admin']) && $EST_reg['look_mes'] && preg_match("/(\d+)(\w*)/", $EST_reg['look_mes'], $match)){
			$i=0;
			$look_mes_list=array();
			$max=$match[1];
			$fp = fopen("$EST[log_path]look_mes.cgi", "r");
			while($tmp = fgets($fp, 4096)){
				if($i<$max){array_push($look_mes_list,$tmp);}
				else{break;}
				$i++;
			}
			fclose($fp);
			#�������������
			if($match[2] == "m" && $i>=$max){
				$mail_mes=<<<EOM
				## $EST[search_name] ��Ͽ�Ԥ���Υ�å��������� ##

EOM;
				foreach($look_mes_list as $tmp){
					$tlook_mes=explode("<>",$tmp);
					$mail_mes.=<<<EOM
+-------------------------+
��Ͽ����$tlook_mes[1] / ��̾����$tlook_mes[5] / Email�� $tlook_mes[4]
�����ȥ롧$tlook_mes[7]
URL��
$tlook_mes[6]
������URL��
$EST[cgi_path_url]regist_ys.php?mode=enter&id=$tlook_mes[0]
EOM;
					if($tlook_mes[2]){$mail_mes.="���ߴ�˾���ƥ��ꡧ$tlook_mes[2]\n";}
					if($tlook_mes[3]){
						$tlook_mes[3]=str_replace("<br>", "\n", $tlook_mes[3]);
						$mail_mes.=$tlook_mes[3] . "\n";
					}
				}
				$mail_mes.="+-------------------------+\n";
				require "pl/mail_ys.php";
				sendmail($EST['admin_email'],$EST['admin_email'],"$EST[search_name] ��Ͽ�Ԥ���Υ�å���������(${max}��)","any","","","","","","",$mail_mes);
				$i=0;
				$look_mes_list=array();
			}
			if($i == $max){array_pop ($look_mes_list);}
			#�����ɲåǡ���($look_mes)�����
			$look_mes[0]=$Slog[0];
			$look_mes[1]=$Slog[4];
			$look_mes[2]=$_POST['Fadd_kt'];
			$look_mes[3]=$_POST['Fto_admin']; $look_mes[4]=str_replace("\n", "<br>", $look_mes[4]);
			$look_mes[4]=$Slog[9];
			$look_mes[5]=$Slog[8];
			$look_mes[6]=$Slog[2];
			$look_mes[7]=$Slog[1];
			$look_mes=join("<>",$look_mes); $look_mes=str_replace("\n", "", $look_mes);
			$look_mes.="<>\n";
			array_unshift($look_mes_list,$look_mes);
			$fp = fopen("$EST[log_path]look_mes.cgi", "w");
			foreach($look_mes_list as $tmp) {
				fputs($fp, $tmp);
			}
			fclose($fp);
		}
		##�᡼�������
		if($_POST[FCmail] != "no" or $_POST['changer'] != "admin"){ #������������ʤ�
			#��̾���դ���ޡ���������
			#local($PR_mail_sougo,$PR_mail_com,$PR_mail_kt);
			if($_POST['Fsougo']){$PR_mail_sougo="(link)";}
			else{$PR_mail_sougo="";}
			if($_POST['Fto_admin']){$PR_mail_com="(com)";}
			else{$PR_mail_com="";}
			if($_POST['Fadd_kt']){$PR_mail_kt="(kt)";}
			else{$PR_mail_kt="";}
			$PR_mail_add_line=$PR_mail_sougo . $PR_mail_com . $PR_mail_kt;
			$Slog[6]=str_replace("<br>", "\n", $Slog[6]);
			$Slog[7]=str_replace("<br>", "\n", $Slog[7]);
			if($EST['mail_new']){require "pl/mail_ys.php";}
			if($EST['mail_to_admin'] && $EST['mail_new']){ #�����ͤإ᡼������
				sendmail($EST['admin_email'],$Slog[9],"$EST[search_name] ������Ͽ������ޤ���${PR_mail_add_line}","new","admin",$Slog,$_POST['Fsougo'],$_POST['Fadd_kt'],$_POST['Fto_admin']);
			}
			if($EST['mail_to_register'] && $EST['mail_new']){ #��Ͽ�Ԥإ᡼������
				sendmail($Slog[9],$EST['admin_email'],"$EST[search_name] ������Ͽ��λ����","new","",$Slog,$_POST['Fsougo'],$_POST['Fadd_kt'],$_POST['Fto_admin']);
			}
		}
		##��Ͽ��̽���
		$Slog=$hyouji_log;
		require "$EST[temp_path]regist_new_end.html";
	} #</������Ͽ��>
}
#(4)����������Τ���Υѥ����ǧ��(enter)
elseif($_GET['mode'] == "enter"){
	if(empty($_POST['id'])) {$_POST['id'] = (empty($_GET['id']))? 0 : $_GET['id'];}

	#���å������ɤ߹���
	$CK_data=get_cookie();
	
	$id_from_cookie = FALSE;
	if (!x_uid && !empty($CK_data[1]))
	{
		// �����Ȥ� cookie �� id ����äƤ�����
		$query = "SELECT count(*) FROM ".$EST['sqltb']."log WHERE id='".$CK_data[1]."'";
		$result = $xoopsDB->query($query) or die("Query failed registys.php in ".__LINE__);
		$_count = 0;
		if ($result)
		{
			list($_count) = mysql_fetch_row($result);
		}
		if ($_count)
		{
			$_POST['id'] = $CK_data[1];
			$id_from_cookie = TRUE;
		}
	}
	
	if ($_POST['id'])
	{
		// id�����ꤵ��Ƥ���
		$query = "SELECT * FROM $EST[sqltb]log WHERE id='".$_POST['id']."' LIMIT 1";
		$result = $xoopsDB->query($query) or die("Query failed registys.php in ".__LINE__);
		$Pdata = mysql_fetch_row($result);
		$Pdata = array_map("stripslashes", $Pdata);
		$_count = count($Pdata);
		
		if ($_count && (($CK_data[4] && $CK_data[3]) || $is_admin == 1 || (($x_uid) && $Pdata[17] == $x_uid)))
		{
			// ľ��ǧ��
			$_POST['pass']=$CK_data[3];
			if ($is_admin) $_POST['changer']="admin";
			$_POST['in_mode']="mente";
			$result = "";
		}
		else
		{
			if ($_count)
			{
				#�������ͤ�����
				$PR_data=<<<EOM
[��Ͽ�ǡ���]<br>
<table width=200><tr><td>
�������ȥ롧<br>$Pdata[1]<br>
��URL��<br><a href="$Pdata[2]">$Pdata[2]</a>
<div align=right>[<a href="$Pdata[2]" target="_blank">��ǧ</a>]</div>
</td></tr>
</table>
EOM;
			}
			else
			{
				if (!$id_from_cookie)
				{
					mes("���ꤵ�줿ID�Υǡ�����¸�ߤ��ޤ���","���顼","java");
				}
				else
				{
					$_POST['id'] = "";
					$PR_data = "";
				}
			}
			require "$EST[temp_path]enter.html";
			exit;
		}
	}
	else
	{
		// id�����ꤵ��Ƥ��ʤ�
		if ($x_uid)
		{
			// ������桼����
			$query = "SELECT * FROM $EST[sqltb]log WHERE uid='".$x_uid."' ORDER BY `stamp` DESC LIMIT 100";
			$result = $xoopsDB->query($query) or die("Query failed registys.php in ".__LINE__);
			$msg = "";
			while ($Pdata = mysql_fetch_row($result))
			{
				$msg .= '<li><a href="?mode=enter&amp;id='.$Pdata[0].'">'.htmlspecialchars($Pdata[1]).'</a></li>';
			}
			$title = "��Ͽ�Ѥߥǡ����ι���";
			if ($msg)
			{
				$msg = "�Խ������������ȥ�򥯥�å����Ƥ���������"."<ul>".$msg."</ul>";
			}
			else
			{
				$msg = "��Ͽ�����ǡ����Ϥ���ޤ���";
			}
			mes($msg, $title, "java");
		}
		else
		{
			$_POST['id'] = "";
			$PR_data = "";
			require "$EST[temp_path]enter.html";
			exit;
		}
	}
}
#(5)�إ�פ�ɽ��(help)
elseif($_GET['mode'] == "help"){
	require "$EST[temp_path]help.html";
	exit;
}
#(6)��Ͽ�����ѹ�(act_mente)
elseif($_POST['mode'] == "act_mente"){
	#$new=>�ɲåǡ����񤭹�����/$TASK=>�������륫�ƥ���ꥹ��#
	#����¾������
	$Smode_name="mente";
	#�ѥ����ǧ��(������ǧ��)
	if($_POST['changer'] == "admin" && $is_admin != 1){
		$cr_pass=crypt($_POST['pass'],$EST['pass']);
		if($cr_pass != $EST['pass'] || (!$_POST['pass'])){
			if(!$_SERVER['REMOTE_HOST']){$_SERVER['REMOTE_HOST']=gethostbyaddr($_SERVER['REMOTE_ADDR']);}
			mes("($x_uid:$Pdata[17])�ѥ���ɤ�ǧ�ڤ˼��Ԥ��ޤ���<br>ǧ�ڤ�������ԥ塼����IP���ɥ쥹��<b>$_SERVER[REMOTE_ADDR]</b><br>ǧ�ڤ�������ԥ塼���Υۥ���̾��<b>$_SERVER[REMOTE_HOST]</b>","�ѥ����ǧ�ڼ���","java");
		}
	}
	elseif(!$is_admin && $EST_reg['no_mente']){mes("���ߡ���Ͽ�Ԥˤ�뽤�����������ߤ���Ƥ��ޤ�","���顼","java");}
	check(); #�������ƤΥ����å�
	#$Spre_log����&����URL��Ͽ�����å�
	if($EST_reg['nijyu_url']){get_id_url_ch(2);}
	$query = "SELECT * FROM $EST[sqltb]log WHERE id='$_POST[id]' LIMIT 1";
	$result = $xoopsDB->query($query) or die("Query failed");
	$Spre_log = mysql_fetch_row($result);
	#��Ͽ�ԤΥѥ����ǧ��
	if($_POST[changer] != "admin"){
		#$cr_pass=crypt($_POST[pass],$Spre_log[5]);
		if($Spre_log[5] != $_POST['Fpass']){mes("�ѥ���ɤ��ְ�äƤ��ޤ�".$_POST['Fpass']." $Spre_log[5]","�ѥ����ǧ�ڥ��顼","java");}
	}
	$Slog=join_fld($_POST[id]);
	#���Υ��ǡ����˽񤭹���
	$Tlog = array_map("addslashes", $Slog);
	$query = "UPDATE $EST[sqltb]log SET title='$Tlog[1]',url='$Tlog[2]',mark='$Tlog[3]',last_time='$Tlog[4]',message='$Tlog[6]',comment='$Tlog[7]',name='$Tlog[8]',mail='$Tlog[9]',category='$Tlog[10]',stamp='$Tlog[11]',banner='$Tlog[12]',renew='$Tlog[13]',keywd='$Tlog[15]',build_time='$Tlog[16]',uid='$Tlog[17]' WHERE id='$_POST[id]'";
	$result = $xoopsDB->query($query) or die("Query failed1");
	##�᡼�������
	if($_POST[FCmail] != "no" or $_POST['changer'] != "admin"){ #������������ʤ�
		$Slog[6]=str_replace("<br>", "\n", $Slog[6]);
		$Slog[7]=str_replace("<br>", "\n", $Slog[7]);
		if($EST['mail_new']){require "pl/mail_ys.php";}
		if($EST['mail_to_admin'] && $EST['mail_ch']){ #�����ͤإ᡼������
			sendmail($EST['admin_email'],$Slog[9],"$EST[search_name] ��Ͽ�����ѹ���λ����","mente","admin",$Slog);
		}
		if($EST['mail_to_register'] && $EST['mail_ch']){ #��Ͽ�Ԥإ᡼������
			sendmail($Slog[9],$EST['admin_email'],"$EST[search_name] ��Ͽ�����ѹ���λ����","mente","",$Slog);
		}
		//$Slog[6]=str_replace("\n", "<br>", $Slog[6]);
		//$Slog[7]=str_replace("\n", "<br>", $Slog[7]);
	}
	##�������륫�ƥ���ꥹ�Ȥ����
	#%TASK�����
	#�ޡ�����ɽ������
	$i=1; $PR_mark="";
	$mark=explode("_",$Slog[3]);
	foreach ($mark as $tmp){
		if($tmp){$PR_mark .= $EST["name_m$i"] . " ";}
		$i++;
	}
	#���ƥ�����ѹ�ɽ������
	if($EST[user_change_kt]){$PR_kt="����Ͽ�Ԥˤ�륫�ƥ����ѹ��ϸ��߶ػߤ���Ƥ��ޤ�";}
	else{$PR_kt="";}
	##��Ͽ��̽���
	require "$EST[temp_path]regist_mente_end.html";
}
#(7)�ѥ���ɤκ�ȯ�ԡ��ѹ�(act_repass)
elseif($_POST['mode'] == "act_repass"){
	if($_POST['repass_mode'] == "repass"){ #�ѥ���ɺ�ȯ�Ի�
		if($_POST['repass_check'] != "on"){mes("�ѥ���ɺ�ȯ�Ԥγ�ǧ�����å�������ޤ��󡣤⤦������äƤ�������å�������ƺ��ټ¹Ԥ��Ƥ�������","��ǧ�����å��򤷤Ƥ�������","java");}
		if(!$EST['re_pass_fl']){mes("�ѥ���ɤκ�ȯ�ԤϤǤ��ʤ�����ˤʤäƤ��ޤ�","���顼","java");}
		#�������ѥ���ɤ����
		#local($tane,$data_temp,@pass_rm);
		$new_pass = uniqid("");
		$cr_new_pass=crypt($new_pass, "ys");
		if($EST['mail_pass']){$PR_mes="�ѥ���ɤκ�ȯ�Ԥ���λ���ޤ���<br>�������ѥ���ɤϥ᡼�륢�ɥ쥹����������ޤ�";}
		else{$PR_mes="�ѥ���ɤκ�ȯ�Ԥ���λ���ޤ���<br>�������ѥ���ɤϡ� <b>$new_pass</b> �פǤ�";}
	}
	else{ #�ѥ�����ѹ���
		$_POST['new_pass']=preg_replace("/\W/", "", $_POST[new_pass]);
		$new_pass=$_POST['new_pass'];
		$cr_new_pass=crypt($new_pass, "ys");
		if($EST['mail_pass']){$PR_mes="�ѥ���ɤ��ѹ�����λ���ޤ���<br>�������ѥ���ɤϥ᡼�륢�ɥ쥹����������ޤ�";}
		else{$PR_mes="�ѥ���ɤ��ѹ�����λ���ޤ���<br>�������ѥ���ɤϡ� <b>$new_pass</b> �פǤ�";}
	}
	$query = "SELECT * FROM $EST[sqltb]log WHERE id='$_POST[id]' LIMIT 1";
	$result = $xoopsDB->query($query) or die("Query failed1");
	$Slog = mysql_fetch_row($result);
	if($Slog) {
		if($_POST['repass_mode'] != "repass"){
			if($_POST['changer'] != "admin"){$cr_pass=crypt($_POST['pass'],$Slog[5]);}
			else{$cr_pass=crypt($_POST['pass'],$EST['pass']);}
			if($_POST['changer'] != "admin"){
				if($cr_pass != $Slog[5]){mes("�ѥ���ɤ��ְ�äƤ��ޤ�","���顼","java");}
			}
			else{
				if($cr_pass != $EST['pass']){mes("�����ѥ���ɤ��ְ�äƤ��ޤ�","���顼","java");}
			}
		}
		elseif($_POST['email'] != $Slog[9]){
			mes("ID�ȥ᡼�륢�ɥ쥹�����פ��ޤ���Ǥ���","���顼","java");
		}
		$mail_to=$Slog[9];
		$Slog[5]=$cr_new_pass;
		$query = "UPDATE $EST[sqltb]log SET passwd='$Slog[5]' WHERE id='$_POST[id]' LIMIT 1";
		$result = $xoopsDB->queryF($query) or die("Query failed1");
		if($EST['mail_pass']){
			require "pl/mail_ys.php";
			sendmail($mail_to,$EST['admin_email'],"$EST[search_name] �ѥ�����ѹ�����","pass","",$Slog);
		}
		mes($PR_mes,"�ѥ�����ѹ���λ","$EST[home]");
	}
	else {mes("��������ID�Ϥ���ޤ���","���顼","java");}
	exit;
}
#(8)����¹�(act_del)
elseif($_POST['mode'] == "act_del"){
	/*
	// �����ԤΤߺ���Ǥ���褦�ˤ���
	global $xoopsUser;
	if ( $xoopsUser )
	{
		$xoopsModule = XoopsModule::getByDirname("yomi");
		if (!$xoopsUser->isAdmin($xoopsModule->mid()))
			mes("���ߡ���Ͽ�Ԥˤ��������ߤ���Ƥ��ޤ�","���顼","java");
	}
	else
		mes("���ߡ���Ͽ�Ԥˤ��������ߤ���Ƥ��ޤ�","���顼","java");
	*/
	$Cdel=0;
	if($_POST[del_mode] == "single"){ #del_mode:single
		if($_POST['del_check'] != "on"){mes("�����ǧ�Τ���˥����å�������Ƥ������ܥ���򲡤��Ƥ�������","��ǧ�����å��򤷤Ƥ�������","java");}
		if($_POST['changer'] != "admin" && $EST_reg['no_mente']){mes("���ߡ���Ͽ�Ԥˤ�뽤�����������ߤ���Ƥ��ޤ�","���顼","java");}
		if($_POST['changer'] == "admin"){pass_check();}
		$fl=0;
		$query = "SELECT passwd FROM $EST[sqltb]log WHERE id='$_POST[id]' LIMIT 1";
		$result = $xoopsDB->query($query) or die("Query failed1");
		$Slog = mysql_fetch_row($result);
		if($Slog) {
			if(!$x_uid || ($_POST['changer'] != "admin" && $x_uid != $_POST['Fuid'])) { #�������ͤ���Ͽ�Ԥξ��
				$cr_pass=crypt($_POST['pass'],$Slog[0]);
				if($cr_pass != $Slog[0] || (!$_POST['pass'])){mes("�ѥ���ɤ�ǧ�ڤ˼��Ԥ��ޤ���","���顼","java");}
			}
			$query = "DELETE FROM $EST[sqltb]log WHERE id='$_POST[id]' LIMIT 1";
			$result = $xoopsDB->queryF($query) or die("Query failed regist916");
			// rank���⳺��ID���
			$query = "DELETE FROM $EST[sqltb]rank WHERE id='$_POST[id]'";
			$result = $xoopsDB->queryF($query) or die("Query failed regist916");
			// rev���⳺��ID���
			$query = "DELETE FROM $EST[sqltb]rev WHERE id='$_POST[id]'";
			$result = $xoopsDB->queryF($query) or die("Query failed regist916");
		}
		else {mes("��������ǡ����ϸ��Ĥ���ޤ���","���顼","java");}
	}
	else{ #del_mode:multi
		if($_POST['changer'] != "admin"){mes("�ѹ��Ի��꤬�����Ǥ�","���顼","java");}
		pass_check();
		#����ڤ�ꥹ�Ȥ���κ���ξ��
		if($_POST['no_link'] == "on"){
			$lines = array();
			$fp=fopen("$EST[log_path]no_link.cgi", "r");
				while($tmp = fgets($fp, 4096)){
					$data=explode("<>",$tmp); #id<>count<>ip<>url<>\n
					if(!$_POST["id_$data[0]"]){array_push($lines,$tmp);}
					if($_POST["id_$data[0]"] == "on") {$_POST['del'][] = $data[0];}
				}
			fclose($fp);
			$fp=fopen("$EST[log_path]no_link.cgi", "w");
				foreach($lines as $tmp) {
					fputs($fp, $tmp);
				}
			fclose($fp);
		}
		#�ǥåɥ�󥯥����å��Ѥߥꥹ�Ȥ���κ���ξ��
		if($_POST['dl_check'] == "on"){
			if(!is_file($_POST['checkfile'])){mes("�ե�������꤬�۾�Ǥ�","���顼","java");}
			$lines = array();
			$fp=fopen("./$_POST[checkfile]", "r");
				while($tmp = fgets($fp, 4096)){
					$data=explode("\t",$tmp); #id=0<><><>url=13<>\n
					if(!$_POST["id_$data[0]"]){array_push($lines,$tmp);}
					if($_POST["id_$data[0]"] == "on") {$_POST['del'][] = $data[0];}
				}
			fclose($fp);
			$fp=fopen("./$_POST[checkfile]", "r");
				foreach($lines as $tmp) {
					fputs($fp, $tmp);
				}
			fclose($fp);
		}
		if($_POST[del]) {
			foreach($_POST['del'] as $del){
				$query = "DELETE FROM $EST[sqltb]log WHERE id='$del' LIMIT 1";
				$result = $xoopsDB->queryF($query) or die("Query failed regist558 $query");
			}
		}
	}
	if($_POST['changer'] == "admin" && ($_POST['no_link'] == "on" || $_POST['dl_check'] == "on")){mes("�����������λ���ޤ���","�����λ","kanri");}
	else{mes("�����������λ���ޤ���","�����λ",$EST[home]);}
	exit;
}
#(9)��Ͽ����(������Ͽ��������������Ͽ����Ͽ�����ѹ�)(regist)
#���å�����Ͽ
if($_POST['in_mode'] == "mente"){ #��Ͽ�����ѹ���
	$CK_data=get_cookie();
	if($_POST['changer'] != "admin" && $_POST['pass']){$CK_data[0]=$_POST['pass'];} #��Ͽ�ԥѥ����
	$CK_data[1]=$_POST[id]; #ID
	if($_POST['changer'] == "admin"){$CK_data[2]="admin";} #�ѹ���
	if($_POST['changer'] == "admin" && $_POST['pass']){$CK_data[3]=$_POST['pass'];} #�����ԥѥ����
	if($_POST['cookie'] == "off"){set_fo_cookie();}
	else{set_cookie($CK_data);}
}
#�ѥ����ǧ��(������ǧ��)
if ($is_admin != 1 && $x_uid == $Pdate[17]){
	if($_POST['changer'] == "admin"){
		$cr_pass=crypt($_POST['pass'],$EST['pass']);
		if($cr_pass != $EST['pass'] || (!$_POST['pass'])){
			if(!$_SERVER['REMOTE_HOST']){$_SERVER['REMOTE_HOST']=gethostbyaddr($_SERVER['REMOTE_ADDR']);}
			mes("�ѥ���ɤ�ǧ�ڤ˼��Ԥ��ޤ���<br>ǧ�ڤ�������ԥ塼����IP���ɥ쥹��<b>".$_SERVER['REMOTE_ADDR']."</b><br>ǧ�ڤ�������ԥ塼���Υۥ���̾��<b>".$_SERVER['REMOTE_HOST']."</b>","�ѥ����ǧ�ڼ���","java");
		}
	}
}
#�����ͤΤߤ���Ͽ�Ǥ���⡼��
if(($EST_reg['no_regist']==1) && $_POST['in_mode'] != "mente" && $_POST['changer'] != "admin"){
	mes("���ߡ�ˬ��Ԥˤ�뿷����Ͽ����ߤ���Ƥ��ޤ�","���顼","java");
}
##$Smode_name������
#������������Ͽ
if($_POST['changer'] == "admin" && $_POST['in_mode'] != "mente"){$Smode_name="new_dairi";}
#��Ͽ�����ѹ�
elseif($_POST['in_mode'] == "mente"){$Smode_name="mente";}
#��Ͽ�Ԥο�����Ͽ
else{$Smode_name="";}
##$Pmode������
#��Ͽ�����ѹ�
if($_POST['Smode_name'] == "mente"){
	$Pmode="act_mente";
}
#������Ͽ
else{
	if ($is_admin == 1 && !$_POST['id']) {
		$Smode_name = "new_dairi";
		$_POST['changer'] = "admin";
	}
	$Pmode="act_regist";
}
##����¾������
#��ߥ�󥯤�̵ͭ
$MES_sougo[1]=" checked"; $MES_sougo{0}="";
#�ƥ�ץ졼�Ȥ��ɤ߹���
if($Smode_name == "new_dairi"){
	if(!$Pdata[10]) $Pdata[10] = "&".$_GET['kt'];
	require "$EST[temp_path]regist_new_admin.html";
}
elseif($_POST['changer'] != "admin" && $Smode_name == "mente"){
	if($EST['syoukai_br']){
		$Pdata[6]=str_replace("<br>", "\n", $Pdata[6]);
		$Pdata[7]=str_replace("<br>", "\n", $Pdata[7]);
	}
	if($EST_reg[no_mente]){mes("���ߡ���Ͽ�Ԥˤ�뽤�����������ߤ���Ƥ��ޤ�","���顼","java");}
	require "$EST[temp_path]regist_mente.html";
}
elseif($_POST['changer'] == "admin" && $Smode_name == "mente"){
	if($EST[syoukai_br]){
		$Pdata[6]=str_replace("<br>", "\n", $Pdata[6]);
		$Pdata[7]=str_replace("<br>", "\n", $Pdata[7]);
	}
	require "$EST[temp_path]regist_mente_admin.html";
}
else{
	if ($xoopsUser) {
		$name_xoops = $xoopsUser->name();
		if(!$name_xoops) $name_xoops = $xoopsUser->uname();
		if(!$Pdata[8]) $Pdata[8] = $name_xoops;
		if(!$Pdata[9]) $Pdata[9] = $xoopsUser->email();
		if(!$Pdata[17]) $Pdata[17] = $xoopsUser->uid();
	} elseif($EST_reg['no_regist']) {
		mes("���ߡ������Ȥˤ����Ͽ����ߤ���Ƥ��ޤ�","���顼","java");
	}
	if(!$Pdata[10]) {$Pdata[10] = "&".$_GET['kt'];}
	require "$EST[temp_path]regist_new.html";
	exit;
}
#----------------------------------------------------------------------------
#(f1)��Ͽ���륫�ƥ����ɽ��(PR_kt)
function PR_kt($category=""){
	global $EST, $EST_reg, $ganes, $gane_UR;
	$kt_no=1;
	$PRselect=" selected";
	$Pkt=explode("&",$category);
	if($EST_reg['kt_min'] != $EST_reg['kt_max']){
		echo "<ul>��<b>$EST_reg[kt_min]</b>��<b>$EST_reg[kt_max]</b>�Ĥޤ�����Ǥ��ޤ�<br>";
	}
	else{
		echo "<ul>��<b>$EST_reg[kt_max]</b>�����򤷤Ƥ�������<br>";
	}
	?>
		���ƥ��ƥ���ξܺ٤ϡ�<a href="<?=$EST['cgi_path_url']?>sitemap.php" target="_blank">���ƥ������</a>�פ򻲹ͤˤ��Ƥ�������<br>
<?php
	gane_st(); #�����륹�ơ����������
	for ($kt_no=1; $kt_no <= $EST_reg['kt_max']; $kt_no++){
		$PRselect=" selected";
		?>
		<!--<select name=Fkt<?=$kt_no?> size=7>-->
		<select name=Fkt<?=$kt_no?> size=1>
<?php
		if($Pkt[$kt_no]){echo '<option value="' . $Pkt[$kt_no] . "\"$PRselect>" . full_kt($Pkt[$kt_no]) . "\n"; $PRselect="";}
		?>
			<option value=""<?=$PRselect?>>--���ꤷ�ʤ�--
<?php
		foreach ($ganes as $line=>$val){
			if($_POST['changer'] == "admin" || !$gane_UR[$line]){
				echo "<option value=\"$line\">" . full_kt($line) . "\n";
			}
		}
		?>
		</select><br><br>
<?php
	}
	echo "</ul><br>";
}

#(f2)��å��������̽���(mes)
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
function mes($MES, $TITLE="", $arg3=""){
	global $EST, $EST_reg, $link;
	global $xoopsOption,$xoopsConfig,$xoopsLogger,$xoopsTpl;
	global $x_ver,$ver;
	if(!$TITLE) {$TITLE="��å���������";}
	if($arg3 == "java" || ($arg3 == "back_reg" && $_POST['mode'] == "act_mente")){
		$BACK_URL="<form><input type=button value=\"&nbsp;&nbsp;&nbsp;&nbsp;���&nbsp;&nbsp;&nbsp;&nbsp;\" onClick=\"history.back()\"></form>";
	}
	elseif($arg3 == "env"){
		$BACK_URL="��<a href=\"$SERVER[HTTP_REFERER]\">���</a>��";
	}
	elseif($arg3 == "kanri"){
		$BACK_URL="<form action=\"$EST[admin]\" method=post>".YOMI_TICKET_TAG."<input type=hidden name=mode value=kanri><input type=hidden name=pass value=\"$_POST[pass]\">	<?php echo YOMI_TICKET_TAG; ?><input type=submit value=\"��������\"></form>";
	}
	elseif(!$arg3){$BACK_URL="";}
	elseif($arg3 == "back_reg"){
		$_POST['Fsyoukai']=str_replace("<br>", "\n", $_POST['Fsyoukai']);
		if($_POST['changer'] == "admin"){$_POST[in_mode]="new_dairi";}
		else{$_POST['in_mode']="form";}
		$BACK_URL=<<<EOM
<form action="regist_ys.php" method=post>
	<input type=hidden name="in_mode" value="form">
	<input type=hidden name="pass" value="$_POST[pass]">
	<input type=hidden name="changer" value="$_POST[changer]">
	<input type=hidden name="Fname" value="$_POST[Fname]">
	<input type=hidden name="Femail" value="$_POST[Femail]">
	<input type=hidden name="Fpass" value="$_POST[Fpass]">
	<input type=hidden name="Fpass2" value="$_POST[Fpass2]">
	<input type=hidden name="Furl" value="$_POST[Furl]">
	<input type=hidden name="Fbana_url" value="$_POST[Fbana_url]">
	<input type=hidden name="Ftitle" value="$_POST[Ftitle]">
	<input type=hidden name="Fsyoukai" value="$_POST[Fsyoukai]">
	<input type=hidden name="Fkanricom" value="$_POST[Fkanricom]">
EOM;
		for($i = 1; $i <= $EST_reg[kt_max]; $i++){
			$BACK_URL .='<input type=hidden name="Fkt'.$i.'" value="'.$_POST["Fkt$i"]."\">\n";
		}
		$BACK_URL .= YOMI_TICKET_TAG;
		$BACK_URL .=<<<EOM
	<input type=hidden name="Fkey" value="$_POST[Fkey]">
	<input type=hidden name="Fadd_kt" value="$_POST[Fadd_kt]">
	<input type=hidden name="Fto_admin" value="$_POST[Fto_admin]">
	<input type=hidden name=Fsougo value="$_POST[Fsougo]">
	<input type=submit value="��Ͽ���̤����">
EOM;
	}
	else{$BACK_URL="��<a href=\"$arg3\">���</a>��";}
	require "$EST[temp_path]mes.html";
	exit;
}

#(f3)�������ƤΥ����å�(check)
function check(){
	global $xoopsUser;
	##�ػߥ�ɤΥ����å�
	global $EST_reg, $ganes, $gane_UR, $is_admin;
	$_POST = array_map("stripslashes", $_POST);
	$_Fkanricom = $_POST['Fkanricom'];
	$_POST = array_map("htmlspecialchars", $_POST);
	$_POST['Fkanricom'] = $_Fkanricom;
	if($EST_reg['kt_no_word']){
		#��ɥ����å��оݤι���
		$check_str = $_POST['Fname']." ".$_POST['Femail']." ".$_POST['Furl']." ".$_POST['Fbana_url']." ".$_POST['Ftitle']." ".$_POST['Fsyoukai']." ".$_POST['Fkey'];
		$no_words=explode(" ",$EST_reg['kt_no_word']);
		foreach ($no_words as $word){
			if(stristr($check_str,$word)){mes("��Ͽ�ǡ�������ˤ��ػߤ���Ƥ�����դ����äƤ��ޤ���<br>��Ͽ���褦�Ȥ��Ƥ���ǡ����Υ�����򤳤Υ��������󥸥󤬶ػߤ��Ƥ����ǽ��������ޤ���","��ɥ����å����顼","back_reg");}
		}
		if(!$_SERVER['REMOTE_HOST']){$_SERVER['REMOTE_HOST']=gethostbyaddr($_SERVER['REMOTE_ADDR']);}
		$addr_host=$_SERVER['REMOTE_ADDR'] . " " . $_SERVER['REMOTE_HOST'];
		foreach ($no_words as $word){
			if(stristr($addr_host,$word)){mes("����IP���ϥۥ���̾�������Ͽ�϶ػߤ���Ƥ����ǽ��������ޤ���<br>$_SERVER[REMOTE_ADDR]/$_SERVER[REMOTE_HOST]<br>","IP/HOST�����å����顼","back_reg");}
		}
	}
	##̾��
	if($EST_reg['Fname'] && !$_POST['Fname']){mes("<b>��̾��</b>��<font color=red>����ɬ�ܹ���</font>�Ǥ�","�����ߥ�","back_reg");}
	$num=strlen($_POST['Fname'])-$EST_reg['Mname']*2;if($num>0){mes("<b>��̾��</b>������<b>$EST_reg[Mname]</b>ʸ������Ǥ�������������","ʸ���������С�(Ⱦ�Ѵ�����${num}ʸ��ʬ)","back_reg");}
	$_POST['Fname']=str_replace("\n", "", $_POST['Fname']);
	##�᡼�륢�ɥ쥹
	if($EST_reg['Femail'] && !$_POST['Femail']){mes("<b>�᡼�륢�ɥ쥹</b>��<font color=red>����ɬ�ܹ���</font>�Ǥ�","�����ߥ�","back_reg");}
	elseif(strlen($_POST['Femail'])-$EST_reg['Memail']>0){$num=strlen($_POST['Femail'])-$EST_reg['Memail'];mes("<b>�᡼�륢�ɥ쥹</b>��Ⱦ��<b>$EST_reg[Memail]</b>ʸ������Ǥ�������������","ʸ���������С�(Ⱦ�Ѵ�����${num}ʸ��ʬ)","back_reg");}
	elseif($EST_reg['Femail'] && !preg_match("/(.+)\@(.+)\.(.+)/", $_POST['Femail'])){mes("<b>�᡼�륢�ɥ쥹</b>�����Ϥ�����������ޤ���","�����ߥ�","back_reg");}
	$_POST['Femail']=str_replace("\n", "", $_POST['Femail']);
	##�ѥ����
	//if ($is_admin != 1){
	if (!$xoopsUser){
		if($_POST['mode'] != "act_mente"){
			if(!$_POST['Fpass']){mes("<b>�ѥ����</b>��<font color=red>!����ɬ�ܹ���</font>�Ǥ�","�����ߥ�","back_reg");}
			elseif($num=strlen($_POST['Fpass'])>8){$num=strlen($_POST['Fpass'])-8;mes("<b>�ѥ����</b>��Ⱦ��<b>8</b>ʸ������Ǥ�������������","ʸ���������С�(Ⱦ�Ѵ�����${num}ʸ��ʬ)","back_reg");}
			elseif($_POST['Fpass'] != $_POST['Fpass2']){mes("�����<b>�ѥ����</b>���Ϥ����פ��ޤ���Ǥ���","���ϥߥ�","back_reg");}
			$_POST['Fpass']=str_replace("\n", "", $_POST['Fpass']);
		}
	}
	##�ۡ���ڡ������ɥ쥹(������Ͽ�����å����̤ΤȤ���˵���)
	if($_POST['Furl'] == "http://"){$_POST['Furl']="";}
	if($EST_reg['Furl'] && !$_POST['Furl']){mes("<b>�ۡ���ڡ������ɥ쥹</b>��<font color=red>����ɬ�ܹ���</font>�Ǥ�","�����ߥ�","back_reg");}
	elseif(strlen($_POST['Furl'])-$EST_reg['Murl']>0){$num=strlen($_POST['Furl'])-$EST_reg['Murl'];mes("<b>�ۡ���ڡ������ɥ쥹</b>��Ⱦ��<b>$EST_reg[Murl]</b>ʸ������Ǥ�������������","ʸ���������С�(Ⱦ�Ѵ�����${num}ʸ��ʬ)","back_reg");}
	elseif($_POST['Furl'] && !preg_match("/^https?:\/\/.+\..+/", $_POST['Furl'])){mes("<b>�ۡ���ڡ������ɥ쥹</b>�����Ϥ�����������ޤ���","�����ߥ�","back_reg");}
	$_POST[Furl]=str_replace("\n", "", $_POST['Furl']);
	##�����ȥ�Хʡ���URL
	if($EST_reg['bana_url']){
		if($_POST['Fbana_url'] == "http://"){$_POST['Fbana_url']="";}
		if($EST_reg['Fbana_url'] && !$_POST['Fbana_url']){mes("<b>�����ȥ�Хʡ���URL</b>��<font color=red>����ɬ�ܹ���</font>�Ǥ�","�����ߥ�","back_reg");}
		elseif(strlen($_POST['Fbana_url'])-$EST_reg['Mbana_url']>0){$num=strlen($_POST['Fbana_url'])-$EST_reg['Mbana_url'];mes("<b>�����ȥ�Хʡ���URL</b>��Ⱦ��<b>$EST_reg[Mbana_url]</b>ʸ������Ǥ�������������","ʸ���������С�(Ⱦ�Ѵ�����${num}ʸ��ʬ)","back_reg");}
		elseif($_POST['Fbana_url'] && !preg_match("/^https?:\/\/.+\..+\.(gif|jpg|jpeg|png)$/", $_POST['Fbana_url'])){mes("<b>�����ȥ�Хʡ���URL</b>�����Ϥ�����������ޤ���","�����ߥ�","back_reg");}
	}
	else{$_POST['Fbana_url']="";}
	$_POST['Fbana_url']=str_replace("\n", "", $_POST['Fbana_url']);
	##�ۡ���ڡ����Υ����ȥ�
	if($EST_reg['Ftitle'] && !$_POST['Ftitle']){mes("<b>�ۡ���ڡ����Υ����ȥ�</b>��<font color=red>����ɬ�ܹ���</font>�Ǥ�","�����ߥ�","back_reg");}
	if(strlen($_POST['Ftitle'])-($EST_reg['Mtitle']*2)>0){$num=strlen($_POST['Ftitle'])-($EST_reg['Mtitle']*2);mes("<b>�ۡ���ڡ����Υ����ȥ�</b>������<b>$EST_reg[Mtitle]</b>ʸ������Ǥ�������������","ʸ���������С�(Ⱦ�Ѵ�����${num}ʸ��ʬ)","back_reg");}
	$_POST['Ftitle']=str_replace("\n", "", $_POST['Ftitle']);
	##�ۡ���ڡ����ξҲ�ʸ
	if($EST_reg['Fsyoukai'] && !$_POST['Fsyoukai']){mes("<b>�ۡ���ڡ����ξҲ�ʸ</b>��<font color=red>����ɬ�ܹ���</font>�Ǥ�","�����ߥ�","back_reg");}
	if(strlen($_POST['Fsyoukai'])-($EST_reg['Msyoukai']*2)>0){$num=strlen($_POST['Fsyoukai'])-($EST_reg['Msyoukai']*2);mes("<b>�ۡ���ڡ����ξҲ�ʸ</b>������<b>$EST_reg[Msyoukai]</b>ʸ������Ǥ�������������","ʸ���������С�(Ⱦ�Ѵ�����${num}ʸ��ʬ)","back_reg");}
	if(!$EST['syoukai_br']){$_POST['Fsyoukai']=str_replace("\n", "", $_POST['Fsyoukai']);}
	//else{$_POST[Fsyoukai]=str_replace("\n", "<br>", $_POST[Fsyoukai]);}
	##�����ͥ�����
	//$_POST[Fkanricom]=str_replace("\n", "<br>", $_POST[Fkanricom]);
	##���ƥ���
	{#local(%kt_fl,$i,$j=0,$PR_kt);
		$kt_fl=array();
		gane_st(); #�����륹�ơ����������
		for($i=1; $i <= $EST_reg['kt_max']; $i++){
			$_POST["Fkt$i"]=str_replace("\n", "", $_POST["Fkt$i"]);
			if($kt_fl[$_POST["Fkt$i"]]){$_POST["Fkt$i"]="";}
			elseif($ganes[$_POST["Fkt$i"]]){$kt_fl[$_POST["Fkt$i"]]=1;}
			else{$_POST["Fkt$i"]="";}
			##�ػߥ��ƥ������Ͽ���褦�Ȥ������
			if($_POST[changer] != "admin" && $gane_UR[$_POST["Fkt$i"]]){
				mes("��Ͽ�Ԥ���Ͽ���Ǥ��ʤ����ƥ�����ѹ����褦�Ȥ��Ƥ��ޤ�","���ƥ�������ߥ�","back_reg");
			}
		}
		$j = count($kt_fl);
		if($EST_reg['kt_min'] == $EST_reg['kt_max']){$PR_kt="<b>$EST_reg[kt_max]</b>��";}
		else{$PR_kt="<b>$EST_reg[kt_min]</b>��<b>$EST_reg[kt_max]</b>��";}
		if($EST_reg['kt_min']>$j || $j>$EST_reg['kt_max']){mes("<b>���ƥ���</b>��${PR_kt}���򤷤Ƥ�������","������ߥ�","back_reg");}
	}
	##�������
	if($EST_reg['Fkey'] && !$_POST['Fkey']){mes("<b>�������</b>��<font color=red>����ɬ�ܹ���</font>�Ǥ�","�����ߥ�","back_reg");}
	if(strlen($_POST['Fkey'])-($EST_reg['Mkey']*2)>0){$num=strlen($_POST['Fkey'])-($EST_reg['Mkey']*2);mes("<b>�������</b>������<b>$EST_reg[Mkey]</b>ʸ������Ǥ�������������","ʸ���������С�(Ⱦ�Ѵ�����${num}ʸ��ʬ)","back_reg");}
	$_POST[Fkey]=str_replace("\n", "<br>", $_POST[Fkey]);
	##�ɲä����ߤ������ƥ���
	if($_POST['mode'] != "act_mente" && $_POST['changer'] != "admin"){
		if($EST_reg['Fadd_kt'] && !$_POST['Fadd_kt']){mes("<b>�ɲä����ߤ������ƥ���</b>��<font color=red>����ɬ�ܹ���</font>�Ǥ�","�����ߥ�","back_reg");}
		if(strlen($_POST['Fadd_kt'])-($EST_reg['Madd_kt']*2)>0){$num=strlen($_POST['Fadd_kt'])-($EST_reg['Madd_kt']*2);mes("<b>�ɲä����ߤ������ƥ���</b>������<b>$EST_reg[Madd_kt]</b>ʸ������Ǥ�������������","ʸ���������С�(Ⱦ�Ѵ�����${num}ʸ��ʬ)","back_reg");}
		$_POST['Fadd_kt']=str_replace("\n", "<br>", $_POST['Fadd_kt']);
	}
	##��ߥ�󥯤�̵ͭ
	$MES_sougo[1]="����"; $MES_sougo[0]="���ʤ�";
	if($_POST['Fsougo'] != "1"){$_POST['Fsougo']=0;}
	##�����ͤؤΥ�å�����
	if($_POST['mode'] != "act_mente" && $_POST['changer'] != "admin"){
		if($EST_reg['Fto_admin'] && !$_POST['Fto_admin']){mes("<b>�����ͤؤΥ�å�����</b>��<font color=red>����ɬ�ܹ���</font>�Ǥ�","�����ߥ�","back_reg");}
		if(strlen($_POST['Fto_admin'])-($EST_reg['Mto_admin']*2)>0){$num=strlen($_POST['Fto_admin'])-($EST_reg['Mto_admin']*2);mes("<b>�����ͤؤΥ�å�����</b>������<b>$EST_reg[Mto_admin]</b>ʸ������Ǥ�������������","ʸ���������С�(Ⱦ�Ѵ�����${num}ʸ��ʬ)","back_reg");}
		if(!$EST['syoukai_br']){$_POST['Fto_admin']=str_replace("\n", "", $_POST['Fto_admin']);}
		else{$_POST['Fto_admin']=str_replace("\n", "<br>", $_POST['Fto_admin']);}
	}
}

#(f4)���ƥ����ɽ��1(PR_preview_kt1)
function PR_preview_kt1(){
	global $EST_reg;
	for($kt_no=1; $kt_no<=$EST_reg['kt_max']; $kt_no++){
		$value = $_POST["Fkt$kt_no"];
		echo "	<input type=hidden name=Fkt" . $kt_no . " value=\"" . $value . "\">\n";
	}
}

#(f5)���ƥ����ɽ��2(PR_preview_kt2)
function PR_preview_kt2(){
	global $EST_reg;
	for($kt_no=1; $kt_no<=$EST_reg['kt_max']; $kt_no++){
		$value = $_POST["Fkt$kt_no"];
		echo full_kt($value);
		?>
<input type=hidden name=Fkt<?=$kt_no?> value="<?=$value?>">
<br>
<?php
	}
}

#(f6)�������Ƥ�����(join_fld)
function join_fld($arg=""){
	#��Ͽ�����ѤΥǡ�������
	#$arg=��Ͽ�ѤΥǡ���ID
	#[�⡼��]
	# $Smode_name=>�ƥ⡼�ɤ�Ƚ���Ѥ������ѿ�(�ʤ�,new_dairi,mente)
	# $_POST['changer']=>�ѹ���(�ʤ�,admin)
	#����Ͽ�����ѹ��ξ����ѹ����ǡ����ϡ�$Spre_log�פ˳�Ǽ����Ƥ���
	global $Smode_name, $Spre_log, $EST, $EST_reg; 
	##��ϿNo(�ǡ���ID)(0)
	$Slog[0]=$arg;
	##�����ȥ�(1)
	$Slog[1]=$_POST['Ftitle'];
	##URL(2)
	$Slog[2]=$_POST['Furl'];
	##�ޡ����ǡ���(3)
	if($_POST['changer'] == "admin"){ #�ѹ��Ԥ�������
		$_POST['Fmark']="";
		for($i=1; $i <=2; $i++){ #���ޡ����������䤹�Ȥ��Ͻ���
			if($_POST["Fmark$i"]){$_POST['Fmark'] .= "1_";}
			else{$_POST['Fmark'] .= "0_";}
		}
		$_POST['Fmark']=substr($_POST['Fmark'],0,-1);
		$Slog[3]=$_POST['Fmark'];
	}
	elseif(!$Smode_name){$Slog[3]="0_0";} #��Ͽ�Ԥο�����Ͽ
	else{$Slog[3]=$Spre_log[3];} #��Ͽ�Ԥ��ѹ�
	##������(4)
	#�����μ���
	if ($_POST['Fhold_timestamp']=="on"){
		$Slog[4]=$Spre_log[4];
	} else {
		$Slog[4]=get_time(0,1);
	}
	##�ѥ����(5)
	if($Smode_name == "mente"){$Slog[5]=$Spre_log[5];} #�����ѹ���
	else{ #������Ͽ��
		$Slog[5]=crypt($_POST['Fpass'], "ys");
	}
	##�Ҳ�ʸ(6)
	//���ԥ��������� by nao-pon
	$_POST['Fsyoukai'] = preg_replace("/\x0D\x0A|\x0D|\x0A/","\n",$_POST['Fsyoukai']);
	$Slog[6]=$_POST['Fsyoukai'];
	##�����ͥ�����(7)
	if($_POST['changer'] == "admin"){ #�ѹ��Ԥ�������
	//���ԥ��������� by nao-pon
		$_POST['Fkanricom'] = preg_replace("/\x0D\x0A|\x0D|\x0A/","\n",$_POST['Fkanricom']);
		$Slog[7]=$_POST['Fkanricom'];
	}
	elseif(!$Smode_name){$Slog[7]="";} #��Ͽ�Ԥο�����Ͽ
	else{$Slog[7]=$Spre_log[7];} #��Ͽ�Ԥ��ѹ�
	##��̾��(8)
	$Slog[8]=$_POST['Fname'];
	##E-mail(9)
	$Slog[9]=$_POST['Femail'];
	##���ƥ���(10)
	if($EST[user_change_kt] && $_POST['mode'] == "act_mente" && $_POST['changer'] != "admin"){ #��Ͽ�Ԥ��ѹ��ǥ��ƥ����ѹ��ػߤξ��
		$i=0;
		$kt=explode("&",$Spre_log[10]);
		$Slog[10]=$Spre_log[10];
		foreach ($kt as $tmp){
			$_POST["Fkt$i"]=$tmp;
			$i++;
		}
	}
	else{ #����¾�ξ��
		$Slog[10]="&";
		for($i = 1; $i <= $EST_reg['kt_max']; $i++){
			$Slog[10] .= $_POST["Fkt$i"] . "&";
		}
	}
	##time����(11)����or����(13)
	if ($_POST['Fhold_timestamp']=="on" || $Spre_log[11] + (86400 * $EST['new_time']) > time()){
		$Slog[11]=$Spre_log[11];
		$Slog[13]=$Spre_log[13];
	} else {
		$Slog[11] = time();
		if($Smode_name == "mente"){ #�����ѹ���
			$Slog[13]= "1";
		} else {
			$Slog[13]= "0"; #������Ͽ��
		}
	}
	##�Хʡ�URL(12)
	$Slog[12]=$_POST['Fbana_url'];
	##�ǽ���������IP(14)
	if($Smode_name == "mente"){$Slog[14]=$Spre_log[14];} #�����ѹ���
	else{$Slog[14]="";} #������Ͽ��
	##�������(15)
	$Slog[15]=$_POST['Fkey'];
	##����Ͽ�⡼�ɤξ�������
	if($EST['user_check'] && $_POST['changer'] != "admin" && $_POST['mode'] == "act_regist"){
		$Slog[14]=implode("<1>",array($_POST['Fsougo'],$_POST['Fadd_kt'],$_POST['Fto_admin']));
	}
	##��Ͽ����(16) by nao-pon
	if($Smode_name == "mente"){$Slog[16] = $Spre_log[16];} #�����ѹ���
	else{$Slog[16] = $times;} #������Ͽ��
	##XOOPS�桼��ID
	$Slog[17]=$_POST['Fuid'];
	
	ksort($Slog);
	return($Slog);
}

#(f7)������Ͽ�Ѥ�ID�����&����URL��Ͽ�����å�(get_id_url_ch)
#�����å��˳ݤ��ä����ˤϥ�å�����
#$arg=>(������Ͽ=1/�����ѹ�=2)
function get_id_url_ch($fl){
	global $EST,$xoopsDB;
	$i=0;
	if (!empty($_POST['Furl']))
	{
		//��Ͽ�Ѥߥǡ����Υ����å�
		$Tlog=array();
		$query = "SELECT * FROM {$EST['sqltb']}log WHERE url='{$_POST['Furl']}' LIMIT 1";
		$result = $xoopsDB->query($query) or die("Query failed");
		$Tlog = mysql_fetch_row($result);
		if($Tlog)
		{
			$Tlog = array_map("addslashes", $Tlog);
			if($_POST['Furl'] == $Tlog[2]){$i++; $pre_title=$Tlog[1];}
			if($_POST['id'] == $Tlog[0]){$Spre_log=$Tlog;}
			if($fl<=$i){mes("����URL�Ϥ��Ǥ���Ͽ����Ƥ��ޤ�<br><br>$Tlog[1] :<br>$Tlog[2]","������Ͽ���顼","java");}
			if($fl == "2" && $i == "1" && $Spre_log[2] != $_POST['Furl']){mes("����URL�Ϥ��Ǥ���Ͽ����Ƥ��ޤ�<br><br>$pre_title :<br>$_POST[Furl]","������Ͽ���顼","java");}
		}
	}
	
	$query = "SELECT id FROM {$EST['sqltb']}log ORDER BY id DESC LIMIT 1";
	$result = $xoopsDB->query($query) or die("Query failed");
	$num=mysql_fetch_row($result);
	$id=++$num[0];
	
	//����Ͽ�ǡ����Υ����å�
	$fp = fopen("{$EST['log_path']}{$EST['temp_logfile']}", "r");
	while($line=fgets($fp, 4096))
	{
		$Tlog=explode("<>",$line);
		if ($id <= $Tlog[0]) $id = ++$Tlog[0];
		if($EST['user_check'] && $_POST['mode'] == "act_regist")
		{
			#����Ͽ�⡼�ɤǥ桼���ο�����Ͽ��
			if($_POST['Furl'] == $Tlog[2]){$i++;}
			if(!empty($_POST['Furl']) && $fl<=$i)
			{
				fclose($fp);
				mes("����URL�ϸ�����Ͽ������Ǥ�<br><br>$Tlog[1] :<br>$Tlog[2]","������Ͽ���顼","java");
			}
		}
	}
	fclose($fp);
	
	return $id;
}

#(f8)��Ͽ��̲��̽���(PRend)
function PRend(){
	global $EST;
	require "$EST[temp_path]regist_new_end.html";
}

#(f9)�ޡ���Form�����Բ��̽���(PR_mark)
function PR_mark(){
	global $Pdata, $EST;
	if($_POST['changer'] == "admin"){
		$mark=explode("_",$Pdata[3]);
		?>
	<li>�ڥޡ�����
		<ul>
<?php
		for($i=1; $i<=2; $i++){ #���ޡ����������䤹�Ȥ��Ͻ���
			echo "<input type=checkbox name=Fmark$i value=1";
			if($mark[$i-1]){echo " checked";}
			echo ">" . $EST["name_m$i"] . "�� ";
		}
		?>
		</ul><br>
<?php
	}
}

?>