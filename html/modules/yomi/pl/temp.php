<?php



function PR_other_kt($path=""){
	#����¾�Υ��ƥ���ɽ��(&PR_other_kt)
	global $gane_other, $ganes, $Ekt, $Eend, $EST;
	$Ekt_row=4;$tr_flag=0;$td_flag=1;
	#$Ekt_row=>ɽ�����
	if(count($gane_other)>=0){
		?><a name=other></a>�ڤ���¾�Υ��ƥ����<font id=small>[<a href="<?php echo $path?>#top">���ڡ���TOP��</a>]</font><center><table style="width:90%" cellpadding=3 id=mid>
<?php
		foreach ($gane_other as $PRkt_no){
			if($td_flag==1){echo "<tr>";}
			echo "<td class=\"yomi-s\">��<a href=\"".yomi_makelink($PRkt_no)."$Eend\">$ganes[$PRkt_no]</a></td>\n";
			if($td_flag==4){echo "</tr>";}
			if($Ekt_row>$td_flag){$td_flag++;}
			else{$td_flag=1;}
		}
		if($td_flag!=1){echo "</tr>";}
		?></table></center>
<hr>
<?php
	}
}

#���������ղ�(&put_icon)
function put_icon(){
	global $Slog, $EST;
	$times=time();
	$mark=explode("_",$Slog[3]);
	if($Slog[13] == 0 && $times - $Slog[11]<86400*$EST['new_time']){
		##����ޡ���
		?>
		<a href="<?php echo $EST['script']?>?mode=new">
		<img src="<?php echo $EST['img_path_url']?>new.gif" alt="<?php echo $EST['name_new']?>" align=bottom></a>
		<?php
	}
	elseif($times - $Slog[11]<86400*$EST['new_time']){
	##�����ޡ���
		?>
		<a href="<?php echo $EST['script']?>?mode=renew">
		<img src="<?php echo $EST['img_path_url']?>renew.gif" alt=<?php echo $EST['name_renew']?> align=bottom></a>
		<?php
	}
	if($mark[0]){
		##m1�ޡ���(�ǥե���ȡ���������)
		?>
		<a href="<?php echo $EST['script']?>?mode=m1">
		<img src="<?php echo $EST['img_path_url']?>m1.gif" alt=<?php echo $EST['name_m1']?> align=bottom></a>
		<?php
	}
	if($mark[1]){
		##m2�ޡ���(�ǥե���ȡ���ߥ��)
		?>
		<a href="<?php echo $EST['script']?>?mode=m2">
		<img src="<?php echo $EST['img_path_url']?>m2.gif" alt=<?php echo $EST['name_m2']?> align=bottom></a>
		<?php
	}
}
// ���إ��ƥ���Υ������
function Child_count($arg0){
	global $ganes, $Clog, $gane_ref, $Ekt, $Ekt_l, $Eend;
	$PR_below_kt = array();
	reset($ganes);
	while(list($key,)=each ($ganes)){
		if(preg_match("/^$arg0\_(\d+)$/", $key)){array_push($PR_below_kt,$key);}
	}
	return count($PR_below_kt);
}

function PRbelow_kt($arg0,$arg1,$arg2){
#ľ�����ƥ����ɽ��(&PRbelow_kt)
#local(@arg,$T3,$td_flag,$key,$value,$area,@AREA,@PR_below_kt);
	global $ganes, $Clog, $gane_ref, $Ekt, $Ekt_l, $Eend, $EST;
	$PR_below_kt = array();
	#����¾������
	$T3=1;
	echo "\n<center><table style=\"width:90%;\" id=mid>";
	$td_flag=0;
	reset($ganes);
	while(list($key,)=each ($ganes)){
		if(preg_match("/^$arg0\_(\d+)$/", $key)){array_push($PR_below_kt,$key);}
		elseif(preg_match("/^$arg0\_(\d+)\_/", $key, $match)){
			$check["${arg0}_$match[1]"]=1;
		}
	}
	$child_count = count($PR_below_kt);
	sort ($PR_below_kt);
	reset($PR_below_kt);
	foreach ($PR_below_kt as $key){
		if($T3==0){echo "</td></tr>"; $T3=1;}
		if($T3==1){echo "\n<tr><td class=\"yomi-s\">"; $td_flag=1;}
		else{echo "</td>\n<td class=\"yomi-s\">";}
		if(!isset($Clog[$key])){$Clog[$key]=0;}
		echo " ��<a href=\"".yomi_makelink($key)."$Eend\">$ganes[$key]";
		if(isset($check[$key])){echo "*";}
		echo "</a>";
		if($arg1){echo"<i>($Clog[$key])</i>";}
		if($T3<$arg2){$T3++;}
		else{$T3=0;}
	}
	if($gane_ref[$arg0]){
		$AREA=explode("&",$gane_ref[$arg0]);
		foreach ($AREA as $tmp){
			if($T3==0){echo "</td></tr>"; $T3=1;}
			if($T3==1){echo "\n<tr><td class=\"yomi-s\">";  $td_flag=1;}
			else{echo "</td>\n<td class=\"yomi-s\">";}
			echo " ��<a href=\"".yomi_makelink($tmp)."$Eend\">$ganes[$tmp]@</a> \n";
			if($T3<$arg2){$T3++;}
			else{$T3=0;}
		}
	}
	if(!$td_flag){echo "<tr><td class=\"yomi-s\">";}
	if($T3){echo "</td></tr>";}
	echo "\n</table></center>";
	return $child_count;
}

function mokuji($arg){
	#�ܼ�����(&mokuji)
	#local(@arg,$st_no,$end_no,$Rmokuji,$max_page,$i,$j,$bf_page,$af_page,$bf_url,$md_url,$af_url,
	global $EST, $ganes;
	$Rmokuji = "";
	$url=$arg[4];
	$bf_page=$arg[0] - 1; $af_page=$arg[0] + 1;
	$bf_url="$url?page="; $md_url=""; $af_url=$arg[3];
	$end_no=$arg[0]*$arg[2];
	$st_no=$end_no - $arg[2] +1; if($end_no>=$arg[1]){$end_no=$arg[1];}
	$max_page=(int)($arg[1] / $arg[2]);
	if($arg[1] % $arg[2]){$max_page++;}
	if ($arg[1]>1) $Rmokuji="�� $st_no - $end_no ( $arg[1] ���� )�� ";
	if($arg[1] > $arg[2]){ #�ܼ�����
		$Rmokuji .= "[ ";
		if($arg[0]>1){
			$Rmokuji .= "<a href=\"$bf_url$md_url$bf_page$af_url\">�����ڡ���</a> ";
		}
		$Rmokuji .= "/ ";
		#make <=
		$max_page_f=(int)($max_page/10);
		if($max_page%10){$max_page_a=1;}else{$max_page_a=0;}
		$pre_page_f=(int)($arg[0]/10);
		if($arg[0]%10){$pre_page_a=1;}else{$pre_page_a=0;}
		if($max_page>10 && $arg[0]>10 && $pre_page_f>0){
			$md_url=""; $j=$pre_page_f*10-19+$pre_page_a*10;
			$Rmokuji .="<a href=\"$bf_url$md_url$j$af_url\">&lt;=</a> ";
		}
		if($pre_page_a){$hyouji_page_st=$pre_page_f*10+1;}
		else{$hyouji_page_st=$pre_page_f*10-9;}
		$hyouji_page_end=$hyouji_page_st+9;
		for($i=1; $i<=$max_page; $i++){
			if($hyouji_page_end<$i){break;}
			if($hyouji_page_st<=$i){
				if($i != $arg[0]){
					$md_url="";
					$j=$i;
					$Rmokuji .="<a href=\"$bf_url$md_url$j$af_url\">$i</a> ";
				}
				else{$Rmokuji .="<b>$i</b> ";}
			}
		}
		#make =>
		if($max_page_f-($pre_page_f+$pre_page_a-1)!=1 or $max_page_a){
			if($max_page>10 && $max_page>$arg[0] && $max_page_f>($pre_page_f+$pre_page_a-1)){
				$md_url="";
				$j=$pre_page_f*10+1+$pre_page_a*10;
				$Rmokuji .="<a href=\"$bf_url$md_url$j$af_url\">=&gt;</a> ";
			}
		}
		$Rmokuji .="/ ";
		if($arg[0] < $max_page){
			if($EST['html'] && $ganes{$_GET['kt']}){$md_url="$_GET[kt]" . "p";}
			$Rmokuji .="<a href=\"$bf_url$md_url$af_page$af_url\">���ڡ�����</a> ";
		}
		$Rmokuji .="]";
	}
	return $Rmokuji;
}

#�����Բ��������ɽ��(������ѹ��򤷤ʤ��Ǥ�������������������󤻡����󤻤ϲ�)
function cp_cr(){
	global $ver,$x_ver,$EST;
	?><div style="text-align:right;font-size:11px"><a href="http://xoops.hypweb.net/" target="_blank">Yomi-Search [ XOOPS ] Ver. <?php echo $x_ver?></a><br />Based on - <a href="http://yomi.pekori.to/" target="_blank">Yomi-Search Ver<?php echo $ver?></a> - <a href="http://sql.s28.xrea.com:8080/" target="_blank">Powered by PHP</a></div>
	<?php
	//For XOOPS
	echo "</td></tr></table>";
	CloseTable();
	xoops_cp_footer();
	//
}

#(c1)���å����ν񤭹���(&set_cookie)
function set_cookie($CK_data){
	$ttl = time() + 864000; // 10days
	#���å����Υե����ޥå�(@CK_data�Υ����Х��ѿ�)
	#[0]=�ѥ����(��Ͽ����)/[1]=ID(��Ͽ����)/[2]=�ѹ���/[3]=�������ѥѥ����
	#[4]=ľ��ǧ��(1or0)/[5]=�������(,�Ƕ��ڤ�)/[6]=���إ��ƥ���ɽ��(1or0)/[7]�����Ƚ�/[8]�ޡ���ͥ��
	$PRcookie=join(':', $CK_data);
	$PRcookie=str_replace(" ", "", $PRcookie);
	$PRcookie=str_replace(";", "", $PRcookie);
	setcookie("ysp", $PRcookie, $ttl, '/');
}

#(c1.1)���å���������(&set_fo_cookie)
function set_fo_cookie(){
	setcookie("ysp", "", 0, '/');
}

#(c2)���å������ɤ߹���(&get_cookie)
function get_cookie(){
	#local($cookie,@cookie,$data);
	$data = (isset($_COOKIE["ysp"]))? $_COOKIE["ysp"] : '';
	$CK_data=array_pad(explode(":",$data), 9, '');
	$CK_data[1] = intval($CK_data[1]);
	return $CK_data;
}

#��ư��󥯤�����(&auto_link)
function auto_link($url){
	return (preg_replace("/([^=^\"]|^)(http\:[\w\.\~\-\/\?\&\+\=\:\@\%\;\#]+)/","$1<a href=\"$2\">$2<\/a>", $url));
}

#(t1)�ե륫�ƥ���̾������(full_kt)
function full_kt($arg){
	#local(@kt,$Rkt_name,$i,$j);
	$Rkt_name=$j="";
	global $ganes;
	$kt=explode("_",$arg);
	foreach ($kt as $i){
		$j .= $i;
		$Rkt_name .= $ganes[$j] . ":";
		$j .= "_";
	}
	$Rkt_name=substr($Rkt_name,0,-1);
	return $Rkt_name;
}

#Location ����(&location)
function location($T_location){
	global $EST;
	if(!$T_location){mes("����褬���Ĥ���ޤ���","���顼","java");}
	if($EST[location]){
		header('HTTP/1.1 301 Moved Permanently');
		header('Status: 301 Moved Permanently');
		header('Location: ' . $T_location);
		exit;
	}else{
		?><html><head><title></title>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?php echo $T_location?>">
</head><body>
</body>
</html>
<?php
exit;
	}
}

function get_time($time="", $time_fl=""){
	if(!$time){$time=time();}
	if(!$time_fl){
		$PR_data=date("Y/m/d",$time);
	}
	else{$PR_data=date("Y/m/d(D) H:i",$time);}
	return $PR_data;
}

#�ѥ���ɥ����å�(&pass_check)
function pass_check(){
	#IP/�ۥ���̾�����¤�������
	global $EST,$is_admin;
	if($EST[login_ip]){
		$ip_list=array();
		$fl=0;
		if(!$_SERVER['REMOTE_HOST']){$_SERVER['REMOTE_HOST']=gethostbyaddr($_SERVER['REMOTE_ADDR']);}
		$ip_list=explode(",",$EST['login_ip']);
		foreach ($ip_list as $ip){
			if(strstr($_SERVER['REMOTE_ADDR'],$ip)){$fl=1; break;}
			elseif(strstr($_SERVER['REMOTE_HOST'],$ip)){$fl=1; break;}
		}
		if(!$fl){mes("���ꤷ��IP���ɥ쥹/�ۥ��ȥ��ɥ쥹�ʳ�����δ���ǧ�ڤ϶ػߤ���Ƥ��ޤ�","���顼","java");}
	}
	if ($is_admin != 1){
		$cr_pass=crypt($_POST['pass'],$EST['pass']);
		if($EST[pass] != $cr_pass || (!$_POST['pass'])){ #�ѥ���ɤ��԰���
			if(!$_SERVER['REMOTE_HOST']){$_SERVER['REMOTE_HOST']=gethostbyaddr($_SERVER['REMOTE_ADDR']);}
			$date=date("Y/m/d H:i");
			$fp = fopen("$EST[log_path]pass_check.cgi", "a");
			fputs($fp, "$_SERVER[REMOTE_ADDR]<>$_SERVER[REMOTE_HOST]<>$date<>$_POST[pass]<>\n");
			fclose($fp);
			mes("�ѥ���ɤ�ǧ�ڤ˼��Ԥ��ޤ�����<br>IP:$_SERVER[REMOTE_ADDR]<br>HOST:$_SERVER[REMOTE_HOST]<br>PASS:$_POST[pass]<br>DATE:$date","�ѥ����ǧ�ڥ��顼","java");
		}
	}
}

function yomi_getmicrotime()
{
	list($msec, $sec) = explode(" ",microtime());
	return ((float)$sec + (float)$msec);
}

function yomi_makelink($val="")
{
	global $EST;
	//error_reporting(E_ALL);

	$mode = "?mode=";
	if (preg_match("/^[\d_]+$/",$val))
	{
		if ($EST['shorturl']) $val = str_replace("_","/",$val);
		$mode = "?mode=kt&amp;kt=";
	}

	if (empty($EST['shorturl']))
		return $EST['script'].$mode.$val;
	else
		return XOOPS_URL."/".$EST['shorturl']."/".$val."/";

}

function make_serach_box($Ssearch_kt = "")
{
	global $EST,$ganes;
?>
<!-- �ڡ������ʤθ����ե����� -->
<hr>
<table width="100%" cellpadding=8 cellspacing=0 border=0>
<tr id="mid-bar">
<td colspan=2>
	<form action="<?php echo $EST['search']?>" method=get  target="" name="form1">
	<input type=hidden name=mode value=search>
	<input type=hidden name=page value=1>
	<input type=hidden name=sort value=<?php echo $EST['defo_hyouji']?>>
	<font id=small>
	[<a href="<?php echo $EST['search']?>">More</a>]
	[<a href="<?php echo $EST['search']?>?window=_blank">New Window</a>]
	</font>
	<br />
	<input type=text name=word value="" size="20"> <input type=submit value=" �� �� "> <input type=reset value="�ꥻ�å�">
	&nbsp;
<?php
if($Ssearch_kt && isset($ganes[$_GET['kt']])){
	?>
	<select name=search_kt>
		<option value="<?php echo $Ssearch_kt?>-b_all" selected>���Υ��ƥ���ʲ����鸡��
		<option value="<?php echo $Ssearch_kt?>">���Υ��ƥ��꤫�鸡��
		<option value="">������
	</select>
<?php
}
?>
	<select name=method>
		<option value="and" selected>���٤Ƥθ��ޤ�
		<option value="or">�����줫�θ��ޤ�
	</select>
	<select name=engine>
<?php
search_form();
?>
	</select>
	<select name=open_type onchange=ch_type(this)>
		<option value="0"selected>���ڡ�����
		<option value="1">�����
	</select>
	<input type=hidden name=hyouji value="30">
</td>
</tr>
<tr><td></form>
	 <font id=small>
	 [<a href="#other">¾�Υ��ƥ���</a>]
	 [<a href="<?php echo $EST['cgi_path_url']?>sitemap.php">�����ȥޥå�</a>]
	 </font>
</td><td align=right>	<font id=small>
	[<a href="<?php echo $EST['cgi_path_url']?>regist_ys.php?mode=help">�إ��</a>]
<?php
if(empty($gane_UR[$_GET['kt']]) && $_GET['mode'] == "kt"){
	?>
	[<a href="<?php echo $EST['cgi_path_url']?>regist_ys.php?mode=regist&kt=<?php echo $_GET['kt']?>">���Υ��ƥ���˿�����Ͽ</a>]
<?php
}
?>
</font></td></tr>
</table>
<?php
}
#-- end of temp.php --#
?>