<?php
$fp=fopen("pl/cfg.php", "w");
##�Ķ������ѹ��ѥƥ�ץ졼�ȥե�����
$out = <<<EOM
<?php
#���̤ζ�������
\$EST=array(
'host'=>XOOPS_DB_HOST, #MySQL�Υۥ���̾
'sqlid'=>XOOPS_DB_USER,#MySQL�Υ桼��̾
'sqldb'=>XOOPS_DB_NAME,#MySQL�Υǡ����١���̾
'sqlpass'=>XOOPS_DB_PASS, #MySQL�Υѥ����
'sqltb'=>XOOPS_DB_PREFIX.'_yomi_', #�ơ��֥�Υץ�ե��å���
'pass'=>'$EST[pass]', #�����ѥѥ����(WEB��������ϰŹ沽����ޤ�)
'home'=>'$EST[home]', #���������󥸥�ΥȥåפؤΥѥ�orURL
'script'=>'$EST[script]', #yomi.php�Υե�����̾
'search'=>'$EST[search]', #�����Ѥ�PHP�ե�����̾
'rank'=>'$EST[rank]', #��󥭥��Ѥ�PHP�ե�����̾
'admin'=>'$EST[admin]', #���������Ѥ�PHP�ե�����̾

'login_ip'=>'$EST[login_ip]', #������˥塼��¹ԤǤ���IP���ɥ쥹������ꥹ��

'top'=>'$EST[top]', #���������󥸥�Υȥå�(CGI=0/HTML=1)

##�桼����������
'user_change_kt'=>'$EST[user_change_kt]', #���ƥ����ѹ�(��ǽ=0/�Բ�=1)
'user_check'=>'$EST[user_check]', #����Ͽ�⡼�ɤ�(���ʤ�=0/����=1)

'hyouji'=>'$EST[hyouji]', #���ƥ����ɽ����

'log_path'=>'$EST[log_path]', #���ǥ��쥯�ȥ�Υѥ�(URL���Բ�)
'temp_path'=>'$EST[temp_path]', #�ƥ�ץ졼�ȥǥ��쥯�ȥ�Υѥ�(URL���Բ�)
'html_path'=>'$EST[html_path]', #HTML�ե������ѥǥ��쥯�ȥ�Υѥ�(URL���Բ�)

'html_path_url'=>'$EST[html_path_url]', #HTML�ե�����ؤ�URL

'cgi_path_url'=>'$EST[cgi_path_url]', #PHP�ե�������֤��ǥ��쥯�ȥ��URL

'img_path_url'=>'$EST[img_path_url]', #�����ե�������֤��ǥ��쥯�ȥ��URL

'temp_logfile'=>'$EST[temp_logfile]', #����Ͽ�ѥե�����

'search_name'=>'$EST[search_name]', #���������󥸥��̾��
'admin_name'=>'$EST[admin_name]', #�����ͤ�̾��
'admin_email'=>'$EST[admin_email]', #�����ͤ�E-Mail
'admin_hp'=>'$EST[admin_hp]', #�����ͤΥۡ���ڡ�����URL

'new_time'=>'$EST[new_time]', #���塦�������֤�����

##̾������
'name_new'=>'$EST[name_new]',
'name_renew'=>'$EST[name_renew]',
'name_m1'=>'$EST[name_m1]',
'name_m2'=>'$EST[name_m2]',
'name_rank'=>'$EST[name_rank]',
'name_rank_bf'=>'$EST[name_rank_bf]',
'name_rank_rui'=>'$EST[name_rank_rui]',
'name_rev'=>'$EST[name_rev]',
'name_rev_bf'=>'$EST[name_rev_bf]',
'name_rev_rui'=>'$EST[name_rev_rui]',

##�᡼�������
'mail_to_admin'=>'$EST[mail_to_admin]', #�����ͤ˥᡼�������(���ʤ�=0/����=1)
'mail_to_register'=>'$EST[mail_to_register]', #��Ͽ�Ԥ˥᡼�������(���ʤ�=0/����=1)
'mail_new'=>'$EST[mail_new]', #������Ͽ��λ�᡼�������(���ʤ�=0/����=1)
'mail_ch'=>'$EST[mail_ch]', #��Ͽ�����ѹ���λ�᡼�������(���ʤ�=0/����=1)
'mail_temp'=>'$EST[mail_temp]', #����Ͽ��λ�᡼�������(���ʤ�=0/����=1)
'mail_pass'=>'$EST[mail_pass]', #�ѥ�����ѹ��᡼�������(���ʤ�=0/����=1)

're_pass_fl'=>'$EST[re_pass_fl]', #�ѥ���ɺ�ȯ�Ԥ�(���ʤ�=0/����=1)

'syoukai_br'=>'$EST[syoukai_br]', #�Ҳ�ʸ�β���(̵��=0/ͭ��=1)

#ɸ��Υ�ɽ����(mark/id_new/id_old/time_new/time_old/ac_new/ac_old)
'defo_hyouji'=>'$EST[defo_hyouji]',

##�����׽���
'location'=>'$EST[location]', #(Location��Ȥ�=1/�᥿������Ȥ�=0)

##������ɥ�󥭥󥰤�����
'keyrank'=>'$EST[keyrank]', #(�»ܤ��ʤ�=0/�»ܤ���=1)
'keyrank_min'=>'$EST[keyrank_min]', #��������ɽ����������
'keyrank_kikan'=>'$EST[keyrank_kikan]', #���״���(����)
'keyrank_hyouji'=>'$EST[keyrank_hyouji]', #ɽ����
'keyrank_cut'=>'$EST[keyrank_cut]', #�������Ȥ˻�����ʲ��Υǡ����Ϻ��

#�͵�(OUT)��󥭥󥰤�����
'rank_fl'=>'$EST[rank_fl]', #(�»ܤ��ʤ�=0/�»ܤ���=1)
'rank_min'=>'$EST[rank_min]', #��󥯥��󤵤�����㥢��������
'rank_kikan'=>'$EST[rank_kikan]', #���״���(����)
'rank_time'=>'$EST[rank_time]', #��󥭥󥰹�������(����)
'rank_best'=>'$EST[rank_best]', #��󥭥󥰥ǡ����κ����ݻ����
'rank_ref'=>'$EST[rank_ref]', #�����оݤ�URL(rank.php��yomi.php���֤��ǥ��쥯�ȥ��URL)�ΰ���(���ꤷ�ʤ�����̤����)

#��������(IN)��󥭥󥰤�����
'rev_fl'=>'$EST[rev_fl]', #(�»ܤ��ʤ�=0/�»ܤ���=1)
'rev_min'=>'$EST[rev_min]', #��󥯥��󤵤�����㥢��������
'rev_kikan'=>'$EST[rev_kikan]', #���״���(����)
'rev_best'=>'$EST[rev_best]', #��󥭥󥰥ǡ����κ����ݻ����
'rev_url'=>'$EST[rev_url]', #����������󥭥󥰻��Υ�󥯥�������URL

##�����ͤؤ����Υե����������
'no_link_min'=>'$EST[no_link_min]', #��𤹤������
'no_link_ip'=>'$EST[no_link_ip]', #���Τ���ݤ���IP���ɥ쥹(�ΰ���)�ꥹ��

##�������������󥿤�(���Ѥ��ʤ�=0/���Ѥ���=1)
'count'=>'$EST[count]',

#���إ��ƥ���ǡ���(ɽ�����ʤ�=0/ɽ������=1)
'kt_child_show'=>'$EST[kt_child_show]',

#���������������ѵ��Ĥ���URL
'allow_search_form'=>'$EST[allow_search_form]',
);

EOM;
fputs($fp, $out);

##sub EST_reg�򹹿�
$out = <<<EOM
function EST_reg(){
#��Ͽ�����ط�������(regist_ys.php)
#����ɬ�ܤ�Fxxx=��/����ʸ������Mxxx=ʸ����
#���ƥ���������=kt_max/���򲼸�=kt_min/���URL��Ͽ=nijyu_url(��ǽ=0/�Բ�=1)
#�ػߥ��=kt_no_word
#������Ͽ����=no_regist(�����ͤΤ�=1/���٤Ƥ�ˬ���=0/��Ͽ�桼�����Τ�=2)
#�����ͤΤߤ�����������Ǥ���⡼��=no_mente(ON=1/OFF=0)
#�Хʡ�URL��Ͽ����=bana_url(ON=1/OFF=0)
#�ɲô�˾���ƥ������=add_kt(ON=1/OFF=0)/�����ͤؤΥ����ȹ���=to_admin(ON=1/OFF=0)
#������Ͽ������ߥ��Ϣ�����=sougo(ON=1/OFF=0)
#��Ͽ�ԤΥ�å�����=look_mes(����=1/���ʤ�=0)
global \$EST_reg;
\$EST_reg=array(

EOM;
fputs($fp, $out);
ksort($EST_reg);
foreach($EST_reg as $key=>$val){
	fputs($fp, "'$key'=>'$val',\n");
}
fputs($fp,");\n}\n");

##%ganes�򹹿�
fputs($fp,"\$ganes=array(\n");
foreach($ganes as $key=>$val){
	$val=str_replace(array(" ","'"), array("","\\'"), $val);
	fputs($fp, "'$key'=>'$val',\n");
}
fputs($fp, ");\n\n");

##sub gane_st�򹹿�
$out = <<<EOM
function gane_st(){
##���ƥ���°��
#�ȥåץڡ�����ɽ������(���֥��ƥ���ξ��Τ�)
global \$gane_top, \$gane_ref, \$gane_UR, \$gane_other;
\$gane_top=array(

EOM;
fputs($fp, $out);
foreach($gane_top as $key=>$val){
	fputs($fp, "'$key'=>'$val',\n");
}
$out = <<<EOM
);
#��Ϣ���ƥ�������
#([��]'A�Υ��ƥ����ֹ�'=>'B�Υ��ƥ����ֹ�&C�Υ��ƥ����ֹ�')
#�ξ��ˤ�A�Υ��ƥ����ɽ�������ݤ�B��C�Υ��ƥ��꤬��Ϣ���ƥ���Ȥ���ɽ�������
\$gane_ref=array(

EOM;
fputs($fp, $out);
foreach($gane_ref as $key=>$val){
	fputs($fp, "'$key'=>'$val',\n");
}
$out = <<<EOM
);
#ˬ��Ԥ���Ͽ�ԲĤΥ��ƥ���
\$gane_UR=array(

EOM;
fputs($fp, $out);
foreach($gane_UR as $key=>$val){
	fputs($fp, "'$key'=>'$val',\n");
}
$out = <<<EOM
);
#����¾�Υ��ƥ����ɽ�����륫�ƥ���
\$gane_other=array(

EOM;
fputs($fp, $out);
foreach($gane_other as $val){
	fputs($fp, "'$val',");
}
fputs($fp, ");\n}\n");

##sub gane_guide�򹹿�
$out = <<<EOM
function gane_guide(){
##���ƥ����̤�����ɽ��(�֡��פ�ɬ�פʤ���к�����Ƥ⹽���ޤ���)
global \$KTEX;
\$KTEX=array(

EOM;
fputs($fp, $out);
foreach($KTEX as $key=>$val){
	$val = str_replace("'","\\'",$val);
	fputs($fp, "'$key'=>'$val',\n");
}
fputs($fp, ");\n}\n");

##sub search_form�򹹿�
$out = <<<EOM
##�����ե����������
function search_form(){

EOM;
fputs($fp, $out);
#�����˽�($PR_search_form)
fputs($fp, $PR_search_form);
fputs($fp, "} #end of &search_form\n");
##sub menu_bar�򹹿�
$out = <<<EOM
##��˥塼�С�������
function menu_bar(){

EOM;
fputs($fp, $out);
#�����˽�($PR_menu_bar)
fputs($fp, $PR_menu_bar);
fputs($fp, "} #end of &menu_bar\n");
##sub head_sp�򹹿�
$out = <<<EOM
##��˥塼�С�������
function head_sp(){

EOM;
fputs($fp, $out);
#�����˽�($PR_head_sp)
fputs($fp, $PR_head_sp);
fputs($fp, "} #end of &head_sp\n");
##sub foot_sp�򹹿�
$out = <<<EOM
##��˥塼�С�������
function foot_sp(){

EOM;
fputs($fp, $out);
#�����˽�($PR_foot_sp)
fputs($fp, $PR_foot_sp);
fputs($fp, "} #end of &foot_sp\n");
fputs($fp, "?>");
fclose($fp);
?>