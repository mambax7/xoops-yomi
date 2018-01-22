<?php

include_once XOOPS_ROOT_PATH . '/modules/yomi/include/func.inc.php';

function b_yomi_show_items($Slog,$title_m,$message_m,$w_m,$h_m)
{
	//ini_set('allow_url_fopen',1);
	
	// �ѿ����å�
	foreach($Slog as $key => $val)
	{
		$$key = $val;
	}
	//$Slog['id'],$Slog['title'],$Slog['url'],$Slog['message'],$Slog['banner'],$Slog['last_time']
	$ret = "";
	// Yomi�������ξܺٲ��̤򳫤����
	$jump_url= XOOPS_URL."/modules/yomi/single_link.php?item_id=".$id;
	$target = " target='_self'";
	// ľ���оݥ����Ȥذ�ư������
	//$jump_url= XOOPS_URL."/modules/yomi/jump.php?id=".$id;
	//$target = " target='_blank'";
	
	// �����ȥ�ʸ��������
	$title_t = mb_strimwidth($title, 0, $title_m + 1, "..");
	
	// ����ʸ��������
	if ($message_m == 0)
		$message_t = "";
	else
		$message_t = mb_strimwidth($message, 0, $message_m + 1, "..");
	
	// ' �򥯥�����
	$message = str_replace("'","&#039;",$message);
	$title = str_replace("'","&#039;",$title);
	
	// ����å��������
	list($banner,$img_size) = b_yomi_get_img_cache($banner,$id);
	
	// ���᡼������������
	//$img_size = (strpos($banner,XOOPS_URL) === 0)? getimagesize($banner) : FALSE;
	
	// ���᡼��������Ĵ��
	if (!$w_m && !$h_m)
		$banner = "";
	elseif(!$w_m)
		$size_tag = " height='{$h_m}'";
	elseif(!$h_m)
		$size_tag = " width='{$w_m}'";
	else
	{
		if ($img_size)
		{
			$_w = $img_size[0] / $w_m;
			$_h = $img_size[1] / $h_m;
			$zoom = max($_w,$_h);
			if ($zoom)
			{
				$w_m = floor($img_size[0] / $zoom);
				$h_m = floor($img_size[1] / $zoom);
			}
		}
		$size_tag = " width='{$w_m}' height='{$h_m}'";
	}
	if ($banner) $ret .= "<div class='xoops_yomi_block_image'><a href='$jump_url' alt='$title' title='$title'$target><img src='$banner' {$size_tag}/></a></div>";
	$ret .= "<div class='xoops_yomi_block_title'><a href=\"".$jump_url."\" title='$title'$target>".$title_t."</a></div>";
	if ($message_t) $ret .= "<div title='$message' class='xoops_yomi_block_message'>".$message_t."</div>";
	if ($pt) $ret .= "<div class='xoops_yomi_block_pt'>{$pt}pt <span class='xoops_yomi_block_in_out'>(IN:{$in}, OUT:{$out})</span></div>";
	return $ret;
}
function b_yomi_show_cols($result,$cols,$title_m,$message_m,$w_m,$h_m)
{
	global $xoopsDB;
	if (defined('HYP_K_TAI_RENDER') && HYP_K_TAI_RENDER) {
		$cols = 1;
	}
	$xoops_yomi_style_tag = "";
	//echo XOOPS_ROOT_PATH."/modules/yomi/style_load.php<br />";
	//include_once (XOOPS_ROOT_PATH."/modules/yomi/style_load.php");
	$td_style = "width:".(int)(100/$cols)."%;";
	$td_style = " style=\"$td_style\"";
	$ret = $xoops_yomi_style_tag;
	$ret .= "<table style='width:100%'><tr>";
	$col = 0;
	if (is_array($result))
	{
		foreach($result as $Slog){
			$col++;
			$ret .= "<td$td_style class='xoops_yomi_block_td'>";
			$ret .= b_yomi_show_items($Slog,$title_m,$message_m,$w_m,$h_m);
			$ret .= "</td>";
			if ($col >= $cols)
			{
				$col = 0;
				$ret .= "</tr>\n<tr>";
			}
		}
	}
	else
	{
		while($Slog = $xoopsDB->fetchArray($result)){
			$col++;
			$ret .= "<td$td_style class='xoops_yomi_block_td'>";
			$ret .= b_yomi_show_items($Slog,$title_m,$message_m,$w_m,$h_m);
			$ret .= "</td>";
			if ($col >= $cols)
			{
				$col = 0;
				$ret .= "</tr>\n<tr>";
			}
		}
	}
	$ret = preg_replace("/<\/tr>\n<tr>$/","",$ret);
	// �ޥ���­��ʤ�����­��ʤ�ʬ�����ʤ����ϰʲ���Ԥ򥳥��ȥ����Ȥ��롣
	if ($cols - $col && $col > 0) $ret .= str_repeat("<td$td_style class='xoops_yomi_block_td'></td>",$cols - $col);
	$ret .= "</tr></table>";
	
	return b_yomi_load_css().$ret;
	//return $ret;
}

// URL�����������å���URI���֤�
function b_yomi_get_img_cache($url,$id) {
	return yomi_get_banner_cache ($url, $id);
}

// �������륷���Ȥ��ɤ߹���
function b_yomi_load_css()
{
	static $load = false;
	$ret = "";
	if (!$load)
	{
		$ret = '<link rel="stylesheet" href="'.XOOPS_URL.'/modules/yomi/style.css" type="text/css" media="screen" charset="shift_jis" />'."\n";
	}
	return $ret;
}
?>