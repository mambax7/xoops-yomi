<?php
// Short URL の定義 XOOPS_URL/[ここの部分を定義]
if (!defined('YOMI_SHORTURL')) define('YOMI_SHORTURL',"directory");

// .htaccess の参考
// XOOPS_ROOT_PATH の .htaccess
// RewriteEngine on
// RewriteRule ^directory(/.*)?$ modules/yomi/index.php$1 [L]

// 以下は修正の必要はありません。
$_SERVER['REQUEST_URI'] = str_replace("/".YOMI_SHORTURL,"/modules/yomi",$_SERVER['REQUEST_URI']);
?>