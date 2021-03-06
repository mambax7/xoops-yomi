<?php
/*
 * Created on 2008/04/22 by nao-pon http://hypweb.net/
 * $Id: index.php,v 1.1 2008/04/22 08:12:34 nao-pon Exp $
 */

include "../../../mainfile.php";
require_once '../../../include/cp_header.php' ;

// branch for altsys
if( defined( 'XOOPS_TRUST_PATH' ) && ! empty( $_GET['lib'] ) ) {
	$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
	$mydirpath = dirname( dirname( __FILE__ ) ) ;

	// common libs (eg. altsys)
	$lib = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , $_GET['lib'] ) ;
	$page = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_GET['page'] ) ;
	
	if( file_exists( XOOPS_TRUST_PATH.'/libs/'.$lib.'/'.$page.'.php' ) ) {
		include XOOPS_TRUST_PATH.'/libs/'.$lib.'/'.$page.'.php' ;
	} else if( file_exists( XOOPS_TRUST_PATH.'/libs/'.$lib.'/index.php' ) ) {
		include XOOPS_TRUST_PATH.'/libs/'.$lib.'/index.php' ;
	} else {
		die( 'wrong request' ) ;
	}
	exit ;
}

?>