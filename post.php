<?php
//  ------------------------------------------------------------------------ //
// ���Ҳե� tad �s�@
// �s�@����G2008-07-05
// $Id: function.php,v 1.1 2008/05/14 01:22:08 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------�ޤJ�ɮװ�--------------*/
include "header.php";
include "post_function.php";
$xoopsOption['template_main'] = "tb3_index_tpl.html";
/*-----------function��--------------*/



/*-----------����ʧ@�P�_��----------*/
$_REQUEST['op']=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$tbsn = (!isset($_REQUEST['tbsn']))? "":intval($_REQUEST['tbsn']);
$tbdsn = (!isset($_REQUEST['tbdsn']))? "":intval($_REQUEST['tbdsn']);

switch($_REQUEST['op']){
	//��s���
	case "update_tad_book3_docs";
	update_tad_book3_docs($tbdsn);
	header("location: page.php?tbdsn={$tbdsn}");
	break;

	//�s�W���
	case "insert_tad_book3_docs":
	$tbdsn=insert_tad_book3_docs();
	header("location: page.php?tbdsn={$tbdsn}");
	break;

	//��J���
	case "tad_book3_docs_form";
	$main=tad_book3_docs_form($tbdsn,$tbsn);
	break;

	//�R�����
	case "delete_tad_book3_docs";
	delete_tad_book3_docs($tbdsn);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//�w�]�ʧ@
	default:
	$main=tad_book3_docs_form($tbdsn,$tbsn);
	break;
}

/*-----------�q�X���G��--------------*/
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl->assign( "css" , "<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/tad_book3/module.css' />") ;
$xoopsTpl->assign( "toolbar" , toolbar($interface_menu)) ;
$xoopsTpl->assign( "content" , $main) ;
include_once XOOPS_ROOT_PATH.'/footer.php';

?>
