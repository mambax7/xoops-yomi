<?php
if (is_readable("shorturl.php")) include("shorturl.php");
// For XOOPS
include("header.php");

include('init.php');
?>
<!-- �� -->
<link rel="stylesheet" href="<?php echo $EST['html_path_url']?>style.css" type="text/css">
<script language="javascript">
<!--
function ch_type(sel){
// onchange�ǥ�����ɥ��򳫤���ˡ������
var form=document.form1;
var open_type=sel.options[sel.selectedIndex].value;
if(open_type==1){form.target="_blank";}
else{form.target="";}
}
//-->
</script>

<a name=top></a>
<!-- ��˥塼�С� -->
<div align=right><font id=small>
<?php
menu_bar();
?>
</font></div>
<hr>
<!-- �ʥӥ��������С� -->
<?php echo YOMI_HOME_URL?> &gt; 
<table width=100%><tr><td id="title-bar">
<b>�����ȥޥå�</b>
</td></tr></table>
<?php
#�إå����ڡ���
head_sp();
?>
<!-- �ڡ������ʤθ����ե����� -->
<hr>
<table width="100%" cellpadding=8 cellspacing=0 border=0>
<tr id="mid-bar">
<td colspan=2>
	<form action="<?php echo $EST['search']?>" method=get  target="" name="form1">
	<input type=hidden name=mode value=search>
	<input type=hidden name=page value=1>
	<input type=hidden name=sort value="<?php echo $_POST['sort']?>">
	<input type=text name=word value="<?php echo $_POST['word']?>" size="20"><input type=submit value=" �� �� "> <input type=reset value="�ꥻ�å�">
	&nbsp;
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
	<font id=small>
	 [<a href="<?php echo $EST['search']?>">More</a>]
	 [<a href="<?php echo $EST['search']?>?window=_blank">New Window</a>]
	</font>
	<input type=hidden name=hyouji value="30">
</td>
</tr>
<tr><td></form>
</td><td align=right>	<font id=small>
	[<a href="<?php echo $EST['cgi_path_url']?>regist_ys.php?mode=help">�إ��</a>] 
	[<a href="<?php echo $EST['cgi_path_url']?>regist_ys.php?mode=enter">���������</a>] 
</font></td></tr>
</table>
<ul>
<table cellpadding=3>
<?php
gane_guide(); #����ʸ�����
foreach($ganes as $key=>$val){
	echo "<tr valign=bottom nowrap><td nowrap>";
	if(!strstr($key,"_")){ #�ȥåץ��ƥ���ξ��
		echo "<br><br>"; 
		echo "<font color=\"#FFFFFF\">$key</font><font size=\"+1\">��</font><a href=\"".yomi_makelink($key)."\"><font size=\"+1\"><b>$val</b></font></a>\n"; #�طʿ��ȹ�碌��
	}
	else{
		echo "<font color=\"#FFFFFF\">$key</font><a href=\"".yomi_makelink($key)."\">$val</a>\n"; #�طʿ��ȹ�碌��
	}
	echo "</td><td nowrap>";
	if (isset($KTEX[$key])) {
		echo "$KTEX[$key]</td></tr>";
	} else {
		echo "</td></tr>";
	}
}
?>
</table>
</ul>
<hr>
<?php
#�եå����ڡ���
foot_sp();
include("footer.php");
?>
<?php
exit;
?>