<?php
//  ------------------------------------------------------------------------ //
// ���Ҳե� tad �s�@
// �s�@����G2008-07-05
// $Id: function.php,v 1.1 2008/05/14 01:22:08 tad Exp $
// ------------------------------------------------------------------------- //

/*-----------�ޤJ�ɮװ�--------------*/
include "../../../include/cp_header.php";
include "../function.php";
include "../post_function.php";

/*-----------function��--------------*/



/*-----------����ʧ@�P�_��----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];
$tbsn = (!isset($_REQUEST['tbsn']))? "":intval($_REQUEST['tbsn']);
$tbdsn = (!isset($_REQUEST['tbdsn']))? "":intval($_REQUEST['tbdsn']);
switch($op){
	//��s���
	case "update_tad_book3_docs";
	update_tad_book3_docs($tbdsn);
	header("location: index.php?op=list_docs&tbsn={$tbsn}");
	break;

	//�s�W���
	case "insert_tad_book3_docs":
	insert_tad_book3_docs();
	header("location: index.php?op=list_docs&tbsn={$tbsn}");
	break;

	//��J���
	case "tad_book3_docs_form";
	$main=tad_book3_docs_form($tbdsn,$tbsn);
	break;


	//�w�]�ʧ@
	default:
	$main=tad_book3_docs_form($tbdsn,$tbsn);
	break;
}

/*-----------�q�X���G��--------------*/
xoops_cp_header();
echo "<link rel='stylesheet' type='text/css' media='screen' href='../module.css' />";
admin_toolbar(2);
echo $main;
xoops_cp_footer();

?>
