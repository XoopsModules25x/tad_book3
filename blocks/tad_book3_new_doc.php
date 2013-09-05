<?php
//  ------------------------------------------------------------------------ //
// ���Ҳե� tad �s�@
// �s�@����G2008-07-05
// $Id: function.php,v 1.1 2008/05/14 01:22:08 tad Exp $
// ------------------------------------------------------------------------- //

//�϶��D�禡 (�|�C�X�̷s�o���峹)
function tad_book3_new_doc($options){
	global $xoopsDB;
	
	//$show_time=$options[0]*86400;
	
    $now=date("Y-m-d H:i:s" , xoops_getUserTimestamp(time()));
	
    $block="";
	$sql = "select a.`tbdsn`,a.`tbsn`,a.`category`,a.`page`,a.`paragraph`,a.`sort`,a.`title`,a.`last_modify_date`,b.`title` from ".$xoopsDB->prefix("tad_book3_docs")." as a left join ".$xoopsDB->prefix("tad_book3")." as b on a.`tbsn`=b.`tbsn` where a.`enable`='1' and  TO_DAYS('{$now}') - TO_DAYS( FROM_UNIXTIME(a.`last_modify_date`)) <= {$options[0]} order by a.`last_modify_date` desc";
	//die($sql);
	$result = $xoopsDB->query($sql);
	
	//$today=date("Y-m-d H:i:s",xoops_getUserTimestamp(time()));
	
	while(list($tbdsn,$tbsn,$category,$page,$paragraph,$sort,$title,$last_modify_date,$book_title)=$xoopsDB->fetchRow($result)){
		$last_modify_date=date("Y-m-d H:i:s",xoops_getUserTimestamp($last_modify_date));
	  //if($today > $show_time+$last_modify_date)continue;
	  $doc_sort=mk_category($category,$page,$paragraph,$sort);
		$block.="<div>{$doc_sort['main']} <a href='".XOOPS_URL."/modules/tad_book3/page.php?tbdsn=$tbdsn' $style>{$title}</a></div>";
	}
	return $block;
}

//�϶��s��禡
function tad_book3_new_doc_edit($options){
	$form="
	"._MB_TADBOOK3_TAD_BOOK3_NEW_DOC_EDIT_BITEM0."
	<INPUT type='text' name='options[0]' value='{$options[0]}'>
	";
	return $form;
}

if(!function_exists("mk_category")){
	//���`�榡��
	function mk_category($category="",$page="",$paragraph="",$sort=""){
		if(!empty($sort)){
			$main="{$category}-${page}-{$paragraph}-{$sort}";
			$level=4;
		}elseif(!empty($paragraph)){
			$main="{$category}-${page}-{$paragraph}";
			$level=3;
		}elseif(!empty($page)){
			$main="{$category}-${page}";
			$level=2;
		}elseif(!empty($category)){
			$main="{$category}.";
			$level=1;
		}else{
			$main="";
			$level=0;
		}
		$all['main']=$main;
		$all['level']=$level;
		return $all;
	}
}
?>
