<?php
##########################################################
## mail_ys.cgi (Yomi-Search�ѥ᡼�������ϥ饤�֥��)    ##
##########################################################
#��(1)��(4)�����Ū��ñ�˽�����ǽ�Ǥ���
###   �ܼ�   ###
#(1)��Ͽ�᡼�����ʸ����(&regist_mail)
#(2)��Ͽ�����ѹ��᡼�����ʸ����(&ch_mail)
#(3)����Ͽ�᡼�����ʸ����(&temp_mail)
#(4)�ѥ���ɺ�ȯ�ԥ᡼�����ʸ����(&pass_mail)
#(5)�᡼����������(&mail)
################

#(1)��Ͽ�᡼�����ʸ����(&regist_mail)
###����ʸ�������ѹ�����ˡ��
##����$�פ���Ϥޤ���ʬ�ϡ��ѿ��פǤ�����Ͽ�ԤΥǡ����ˤ�ä��͡���
##�ͤ�����ޤ��Τǽ�������ݤˤϤ��Τޤް�ư�����Ƥ���������
##���̾�����ܸ���ʬ�Ͻ������Ƥ�����פǤ���
##�������Ǥ�����ʬ�ϡ�print OUT<<"EOM";�٤μ��ιԤ���
##��EOM�٤����ιԤޤǤǤ���

function regist_mail($log,$PR){
	global $EST,$PRkt,$PRpass,$admin_fl,$vars;
	$honbun="���Τ��Ӥϡ�".$EST['search_name']." �ؤΤ���Ͽ���꤬�Ȥ��������ޤ���

��Ͽ���Ƥϰʲ��ΤȤ���Ǥ��Τǡ�����ǧ����������
*************************************************
����Ͽ������".$log[4]."
����Ͽ�Ԥ�IP���ɥ쥹��".$vars['REMOTE_ADDR']."
����Ͽ�ԤΥۥ���̾��".$vars['REMOTE_HOST']."
�����ȸ���".$_SERVER['HTTP_REFERER']."
*************************************************

��ID
$log[0]
����̾��
$log[8]
���ť᡼��
$log[9]
�������ȥ�
$log[1]
����Ͽ�������ƥ���
$PRkt
���Ҳ�ʸ
$log[6]
��URL
$log[2]
";
	if($admin_fl != "admin"){
		$honbun .="�������ѥ����\n$PRpass\n";
	}
	if($PR['sougo']){
		$honbun .="����ߥ��\n{$PR['sougo']}\n";
	}
	if($PR['add_kt']){
		$honbun .="���ɲä����ߤ������ƥ���\n{$PR['add_kt']}\n";
	}
	if($PR['to_admin']){
		$honbun .="�������ͤؤΥ�å�����\n{$PR['to_admin']}\n";
	}
	if($PR['to_register']){
		$honbun .="�������ͤ���".$log[8]."����ؤΥ�å�����\n{$PR['to_register']}\n";
	}
	$honbun .="
����Ͽ�����ѹ���URL��
$EST[cgi_path_url]regist_ys.php?mode=enter&id=$log[0]

��Ͽ����Ƥ���${EST[new_time]}���֤�New!�ޡ������դ���
�������Υڡ����ǷǺܤ���ޤ���

��Ͽ���Ƥ��ѹ��ˤʤä����ʤɤˤϡ�
���ƽ����򤷤Ƥ��������ȡ�UP!�ޡ�����
${EST[new_time]}�����դ������������Ⱦ���Υڡ����˷Ǻܤ���ޤ���
";

	if($EST['rev_fl']){
		$honbun .="
�����ߥ���������󥭥󥰤򳫺Ť��Ƥ��ޤ�
�ʲ���URL�˥�󥯤�ĥ�äƤ��������ȥ�󥭥󥰤˽��פ���ޤ���
�ڤ��ʤ����Ѥ�URL��
$EST[cgi_path_url]$EST[rank]?mode=r_link&id=$log[0]
���ʤ��Υ����Ȥ�ˬ��Ԥ������嵭��URL�ؤΥ�󥯤򥯥�å�����ȡ�
�������ȤΥȥåץڡ�����ž�����졢����������󥭥󥰤Υݥ���Ȥ��û�����ޤ���
��������������������Ʊ��ˬ��Ԥ�������Υ���������1�ݥ���ȤȤ��ޤ�
�⤷�������Ф����ä���������
";
	}
	$honbun .="
�ʤ���������Ͽ���Ƥν�������������ˤϡ������ѥ����
�ˤ����ƹԤ����Ȥ��Ǥ��ޤ��Τǡ��ѥ���ɤ����ڤ��ݴɤ��Ƥ����Ʋ�������

���줫���ɤ�����������ꤤ���ޤ���

+-------------------------------------+
  $EST[search_name] ������ $EST[admin_name]
  HP: $EST[admin_hp]
  E-Mail: $EST[admin_email]
+-------------------------------------+
";
	return $honbun;
}

#(2)��Ͽ�����ѹ��᡼�����ʸ����(&ch_mail)
###����ʸ�������ѹ�����ˡ��
##��(1)��Ͽ�᡼�����ʸ����٤�Ʊ���Ǥ���
function ch_mail($log){
	global $EST,$PRkt,$vars;
		$honbun="��Ͽ�ѹ����Ƥϰʲ��ΤȤ���Ǥ��Τǡ�����ǧ����������
*************************************************
����Ͽ������".$log[4]."
����Ͽ�Ԥ�IP���ɥ쥹��".$vars['REMOTE_ADDR']."
����Ͽ�ԤΥۥ���̾��".$vars['REMOTE_HOST']."
*************************************************

��ID
$log[0]
���ť᡼��
$log[9]
�������ȥ�
$log[1]
����Ͽ�������ƥ���
$PRkt
���Ҳ�ʸ
$log[6]
��URL
$log[2]
����Ͽ�����ѹ���URL
$EST[cgi_path_url]regist_ys.php?mode=enter&id=$log[0]

+-------------------------------------+
  $EST[search_name] ������ $EST[admin_name]
  HP: $EST[admin_hp]
  E-Mail: $EST[admin_email]
+-------------------------------------+
";
	return $honbun;
}

#(3)����Ͽ�᡼�����ʸ����(&temp_mail)
###����ʸ�������ѹ�����ˡ��
##��(1)��Ͽ�᡼�����ʸ����٤�Ʊ���Ǥ���
function temp_mail($log,$PR){
	global $EST,$PRkt,$PRpass,$admin_fl,$vars;
	$honbun="���Τ��Ӥϡ�".$EST['search_name']." �ؤβ���Ͽ���꤬�Ȥ��������ޤ���
���������Ͽ�������ޤ��ΤǤ��Ф餯���Ԥ�����������
*************************************************
����Ͽ������".$log[4]."
����Ͽ�Ԥ�IP���ɥ쥹��".$vars['REMOTE_ADDR']."
����Ͽ�ԤΥۥ���̾��".$vars['REMOTE_HOST']."
�����ȸ���".$_SERVER['HTTP_REFERER']."
*************************************************

����̾��
$log[8]
���ť᡼��
$log[9]
�������ȥ�
$log[1]
����Ͽ�������ƥ���
$PRkt
���Ҳ�ʸ
$log[6]
��URL
$log[2]
";
	if($admin_fl != "admin"){
		$honbun .="�������ѥ����\n$PRpass\n";
	}
	if($PR['sougo']){
		$honbun .="����ߥ��\n{$PR['sougo']}\n";
	}
	if($PR['add_kt']){
		$honbun .="���ɲä����ߤ������ƥ���\n{$PR['add_kt']}\n";
	}
	if($PR['to_admin']){
		$honbun .="�������ͤؤΥ�å�����\n{$PR['to_admin']}\n";
	}

	$honbun .="
�ʤ���������Ͽ���Ƥν�������������ˤϡ������ѥ����
�ˤ����ƹԤ����Ȥ��Ǥ��ޤ��Τǡ��ѥ���ɤ����ڤ��ݴɤ��Ƥ����Ʋ�������

+-------------------------------------+
  $EST[search_name] ������ $EST[admin_name]
  HP: $EST[admin_hp]
  E-Mail: $EST[admin_email]
+-------------------------------------+
";
	return $honbun;
}

#(4)�ѥ���ɺ�ȯ�ԥ᡼�����ʸ����(&regist_mail)
###����ʸ�������ѹ�����ˡ��
##��(1)��Ͽ�᡼�����ʸ����٤�Ʊ���Ǥ���
function pass_mail($log){
	global $EST, $new_pass, $vars;
	$honbun="���Υ᡼��ϡ��ѥ���ɺ�ȯ�Ԥ����Υ᡼��Ǥ���
�������ѥ���ɡ� $new_pass

*************************************************
���ѹ��Ԥ�IP���ɥ쥹��".$vars['REMOTE_ADDR']."
���ѹ��ԤΥۥ���̾��".$vars['REMOTE_HOST']."
*************************************************

����̾��
$log[8]
�������ȥ�
$log[9]
��URL
$log[2]
����Ͽ�����ѹ���URL��
$EST[cgi_path_url]regist_ys.php?mode=enter&id=$log[0]

+-------------------------------------+
  $EST[search_name] ������ $EST[admin_name]
  HP: $EST[admin_hp]
  E-Mail: $EST[admin_email]
+-------------------------------------+
";
	return $honbun;
}

#(5)�᡼����������(&mail)
##$arg1=>������᡼�륢�ɥ쥹
##$arg2=>������᡼�륢�ɥ쥹
##$arg3=>��̾
##$arg4=>�����⡼������(������Ͽ=new/��Ͽ�����ѹ�=mente/����Ͽ=temp/�ѥ���ɺ�ȯ��=pass/���ѥ᡼��=any)
##$arg5=>������=admin/����¾=��ά
##$arg6=>�ǡ�������η������
##$arg7=>��ߥ�󥯤�̵ͭ
##$arg8=>���ߴ�˾���ƥ���
##$arg9=>�����ͤؤΥ�å�����
##$arg10=>�����ͤ�����Ͽ�ԤؤΥ�å�����
##$arg11=>���ѥ᡼��ξ�����ʸ
function sendmail($mailto,$from_mail,$kenmei,$mail_mode,$admin,$log,$arg6="",$PRadd_kt="",$PRto_admin="",$PRto_register="",$PRhonbun="")
{
	#$honbun, #��ʸ
	global $Eref,$admin_fl,$vars,
	$PRkt, #���ƥ���̾
	$PRpass; #�ѥ����
	//$PRsougo, #��ߥ��
	//$PRadd_kt, #���ߴ�˾���ƥ���
	//$PRto_admin, #�����ͤؤΥ�å�����
	//$PRto_register, #�����ͤ�����Ͽ�ԤؤΥ�å�����
	//$PRhonbun; #���ѥ᡼��ξ�����ʸ
	##����¾������
	$vars = array();
	$PR = array();
	$PR['add_kt'] = $PRadd_kt;
	$PR['to_admin'] = $PRto_admin;
	$PR['to_register'] = $PRto_register;
	#referer
	$_SERVER[HTTP_REFERER]=$Eref;
	$admin_fl=$admin;
	$PRkt="";
	#���ƥ���
	$kt=explode("&",$log[10]);
	foreach ($kt as $tmp){
		if ($tmp) $PRkt .= full_kt($tmp) . "\n";
	}
	#�ѥ����
	if(!$_POST[Fpass]){$PRpass="(��Ͽ���˵��������ѥ���ɤǤ�)";}
	elseif($admin_fl != "admin"){$PRpass=$_POST['Fpass'];}
	else{$PRpass="(�����ͤϸ��뤳�Ȥ��Ǥ��ޤ���)";}
	#��ߥ��
	if($arg6){$PR['sougo']="����";}
	else{$PR['sougo']="";}
	#$ENV{'REMOTE_HOST'}
	if(!$_SERVER['REMOTE_HOST']){$_SERVER['REMOTE_HOST']=gethostbyaddr($_SERVER['REMOTE_ADDR']);}
	if($_POST['mode'] == "temp_to_regist_act")
	{
		#����Ͽ������Ͽ�ξ��
		$vars['REMOTE_HOST'] = $vars['REMOTE_ADDR'] = "(�����Ծ�ǧ)";
	}
	else
	{
		$vars['REMOTE_HOST'] = $_SERVER['REMOTE_HOST'];
		$vars['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
	}
	#�����ͤؤΥ�å������β����Ѵ�
	$PR['to_admin']=str_replace("<br>", "\n", $PR['to_admin']);
	$PR['to_admin']=str_replace("&lt;br&gt;", "\n", $PR['to_admin']);

	if($mail_mode == "new"){$honbun=regist_mail($log,$PR);}
	elseif($mail_mode == "mente"){$honbun=ch_mail($log);}
	elseif($mail_mode == "temp"){$honbun=temp_mail($log,$PR);}
	elseif($mail_mode == "pass"){$honbun=pass_mail($log);}
	elseif($mail_mode == "any"){$honbun=$PRhonbun;}
	$honbun = unhtmlentities($honbun);
	mb_language ("ja");
	mb_internal_encoding("EUC-JP");
	mb_send_mail($mailto,$kenmei,$honbun,"From: $from_mail");
}

//HTMLʸ������ƥ��ƥ��򸵤��᤹
function unhtmlentities ($str)
{
	$str = str_replace("&lt;","<",$str);
	$str = str_replace("&gt;",">",$str);
	$str = str_replace("&amp;","&",$str);
	return $str;
}


?>