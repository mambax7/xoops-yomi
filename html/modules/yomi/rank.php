<?php
include("header.php");

include('init.php');

##�ܼ�##
#(1)��󥯥����׽���(link)
#(1.1)�������������׽���(r_link)
#(2)������ɥ�󥭥�ɽ������(PR_keyrank)
#(3)��������(IN)��󥭥�ɽ������(PR_rev)
#(4)�͵�(OUT)��󥭥�ɽ������(PR_rank)

if(!isset($_GET['page'])){$_GET['page']=1;}

if (isset($_GET['mode'])) {
	#(1)��󥯥����׽���(link)
	if($_GET['mode'] == "link"){
		$_GET['id']=preg_replace("/\D/", "", $_GET[id]);
		if($_GET['id']){
			#referer�����å�
			if(!$_SERVER['HTTP_REFERER']){$fl=1;} #referer��̵���Ȥ��˥�����Ȥ��ʤ����ˤϤ��ιԤ���
			$ref_list=explode(",",$EST[rank_ref]);
			if(!$EST['rank_ref']){$fl=1;}
			else{
				foreach($ref_list as $tmp){
					if(strstr($_SERVER['HTTP_REFERER'],$tmp)){$fl=1;}
				}
			}
			if($fl){
				$time=time();
				$query="SELECT id FROM $EST[sqltb]rank WHERE id='$_GET[id]' AND ip='$_SERVER[REMOTE_ADDR]' AND time > ".($time-$EST[rank_time]*3600);
				$result=$xoopsDB->query($query) or die("Query failed rank32 $query");
				$tmp = mysql_fetch_row($result);
				if(!$tmp) {
					$query="INSERT INTO $EST[sqltb]rank (id,time,ip) VALUES ('$_GET[id]', '$time' ,'$_SERVER[REMOTE_ADDR]');";
					$result=$xoopsDB->queryF($query) or die("Query failed rank36 $query");
				}
				//mysql_close($link);
			}
		}
		if($_GET['url']){location($_GET['url']);}
	}

	#(1.1)�������������׽���(&r_link)
	elseif($_GET['mode'] == "r_link"){
		if($EST[rev_fl]){
			$_GET['id']=preg_replace("/\D/", "", $_GET['id']);
			if($_GET['id']){
				$query="SELECT id FROM $EST[sqltb]log WHERE id='$_GET[id]'";
				$result=$xoopsDB->query($query) or die("Query failed rank54 $query");
				$tmp = mysql_fetch_row($result);
				if ($tmp) { //ID��¸�ߤ�����Τ߽�������
					$time=time();
					$_GET['id']=str_replace("\n", "", $_GET['id']);
					$query="SELECT id FROM $EST[sqltb]rev WHERE id='$_GET[id]' AND ip='$_SERVER[REMOTE_ADDR]' AND time > ".($time-$EST[rank_time]*3600);
					$result=$xoopsDB->query($query) or die("Query failed rank54 $query");
					$tmp = mysql_fetch_row($result);
					if(!$tmp) {
						$query="INSERT INTO $EST[sqltb]rev (id,time,ip) VALUES ('$_GET[id]', '$time' ,'$_SERVER[REMOTE_ADDR]')";
						$result=$xoopsDB->queryF($query) or die("Query failed rank58 $query");
					}
					//mysql_close($link);
				}
			}
		}
		//$EST[location]=0; #refresh�����פˤ���
		location($EST[rev_url]);
	}

	#(2)������ɥ�󥭥�ɽ������(&PR_keyrank)
	elseif($_GET['mode'] == "keyrank"){
		require "$EST[temp_path]keyrank.html";
		exit;
	}

	#(3)��������(IN)��󥭥�ɽ������(&PR_rev)
	elseif($_GET['mode'] == "rev" || $_GET['mode'] == "rev_bf" || $_GET['mode'] == "rev_rui"){
		if(!$EST['rev_fl']){mes("����������󥭥󥰤ϼ»ܤ��ʤ�����ˤʤäƤ��ޤ�","���顼","java");}
		require "$EST[temp_path]rev_rank.html";
	}
}

#(4)�͵���󥭥�ɽ������
if(!$EST['rank_fl']){mes("�͵���󥭥󥰤ϼ»ܤ��ʤ�����ˤʤäƤ��ޤ�","���顼","java");}
if(!isset($_GET['mode'])){$_GET['mode']="rank";}
require "$EST[temp_path]rank.html";
exit;

#(t1)��å��������̽���(mes)
#��:mes($arg1,$arg2,$arg3);
#��ǽ:��å��������̤���Ϥ���
#����:$arg1=>ɽ�������å�����
#     $arg2=>�ڡ����Υ����ȥ�(��ά���ϡ֥�å��������̡�)
#     $arg3=>��JavaScript�ˤ������ץܥ���ɽ��=java
#            ��$ENV{'HTTP_REFERER'}��Ȥ����=env
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
		$BACK_URL="��<a href=\"$_SERVER[HTTP_REFERER]\">���</a>��";
	}
	elseif(!$arg3){$BACK_URL="";}
	else{$BACK_URL="��<a href=\"$arg3\">���</a>��";}
	require "$EST[temp_path]mes.html";
	exit;
}

?>