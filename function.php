<?php
//  ------------------------------------------------------------------------ //
// ���Ҳե� tad �s�@
// �s�@����G2008-07-05
// $Id: function.php,v 1.1 2008/05/14 01:22:08 tad Exp $
// ------------------------------------------------------------------------- //
//�ޤJTadTools���禡�w
if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php")){
 redirect_header("http://www.tad0616.net/modules/tad_uploader/index.php?of_cat_sn=50",3, _TAD_NEED_TADTOOLS);
}
include_once XOOPS_ROOT_PATH."/modules/tadtools/tad_function.php";

define("_TADBOOK3_BOOK_DIR",XOOPS_ROOT_PATH."/uploads/tad_book3");
define("_TADBOOK3_BOOK_URL",XOOPS_URL."/uploads/tad_book3");


//tad_book3�s����
function tad_book3_form($tbsn=""){
	global $xoopsDB,$xoopsUser;
	include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");

	//����w�]��
	if(!empty($tbsn)){
		$DBV=get_tad_book3($tbsn);
	}else{
		$DBV=array();
	}

	//�w�]�ȳ]�w

	$tbsn=(!isset($DBV['tbsn']))?"":$DBV['tbsn'];
	$tbcsn=(!isset($DBV['tbcsn']))?"":$DBV['tbcsn'];
	$sort=(!isset($DBV['sort']))?get_max_doc_sort($tbcsn):$DBV['sort'];
	$title=(!isset($DBV['title']))?"":$DBV['title'];
	$description=(!isset($DBV['description']))?"":$DBV['description'];
	$author=(!isset($DBV['author']))?"":$DBV['author'];
	$read_group=(!isset($DBV['read_group']))?"":$DBV['read_group'];
	$passwd=(!isset($DBV['passwd']))?"":$DBV['passwd'];
	$enable=(!isset($DBV['enable']))?"1":$DBV['enable'];
	$pic_name=(!isset($DBV['pic_name']))?"":$DBV['pic_name'];
	$counter=(!isset($DBV['counter']))?"":$DBV['counter'];
	$create_date=(!isset($DBV['create_date']))?"":$DBV['create_date'];



	if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/fck.php")){
    redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/fck.php";
  $fck=new FCKEditor264("tad_book3","description",$description);
  $fck->setwidth(600);
  $fck->setHeight(250);
  $editor=$fck->render();

	$author_arr=(empty($author))?array($xoopsUser->getVar("uid")):explode(",",$author);

	$cate_select=cate_select($tbcsn);

  $member_handler =& xoops_gethandler('member');
  $usercount = $member_handler->getUserCount(new Criteria('level', 0, '>'));

  if ($usercount < 1000) {

  	$select = new XoopsFormSelect('', 'author',$author_arr, 5, true);
    $member_handler =& xoops_gethandler('member');
    $criteria = new CriteriaCompo();
    $criteria->setSort('uname');
    $criteria->setOrder('ASC');
    $criteria->setLimit(1000);
    $criteria->setStart(1);

    $select->addOptionArray($member_handler->getUserList($criteria));
    $user_menu=$select->render();
  }else{
    $user_menu="<textarea name='author_str' style='width:100%;'>$author</textarea>
    <div>user uid, ex:\"1;27;103\"</div>";
  }

  $group_arr=(empty($read_group))?array(""):explode(",",$read_group);
  $SelectGroup=new XoopsFormSelectGroup("", "read_group", false,$group_arr, 5, true);
  $SelectGroup->addOption("", _MA_TADBOOK3_ALL_OPEN, false);
	$group_menu=$SelectGroup->render();

	$op=(empty($tbsn))?"insert_tad_book3":"update_tad_book3";
	//$op="replace_tad_book3";
	$main="

  <form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
  <table class='form_tbl'>

	<input type='hidden' name='tbsn' value='{$tbsn}'>
	<tr><td class='title'>"._MA_TADBOOK3_TBCSN_MENU."</td>
	<td class='col' colspan='6'><select name='tbcsn' size=1>
		$cate_select
	</select>
  "._MA_TADBOOK3_NEW_PCSN."
  <input type='text' name='new_tbcsn' style='width:150px;'>
  "._MA_TADBOOK3_SORT."
  <input type='text' name='sort' size='3' value='{$sort}'></td>
	</tr>
	<tr>
	<td class='title'>"._MA_TADBOOK3_TITLE."</td>
	<td class='col'><input type='text' name='title' style='width:200px;' value='{$title}'></td>
	<td class='title'>"._MA_TADBOOK3_PIC_NAME."</td>
	<td class='col' colspan='3'><input type='file' name='pic_name' style='width:150px;'></td>
	</tr>

	<tr>
	<td class='col' colspan=6>$editor</td></tr>

	<tr><td class='title' rowspan=2>"._MA_TADBOOK3_AUTHOR."</td>
	<td class='col' rowspan=2>$user_menu</td>
	<td class='title' rowspan=2>"._MA_TADBOOK3_READ_GROUP."</td>
	<td class='col' rowspan=2>$group_menu</td>
	<td class='title'>"._MA_TADBOOK3_ENABLE."</td>
	<td class='col'>
	<input type='radio' name='enable' value='1' ".chk($enable,'1','1').">"._MI_TADBOOK3_ENABLE."
	<input type='radio' name='enable' value='0' ".chk($enable,'0').">"._MI_TADBOOK3_UNABLE."</td>
	</tr>
	<tr>
	<td class='title'>"._MA_TADBOOK3_PASSWD."</td>
	<td class='col'><input type='text' name='passwd' size='10' value='{$passwd}'></td>
	</tr>
	<tr>
	<td class='bar' colspan='6'>
  <input type='hidden' name='op' value='{$op}'>
  <input type='submit' value='"._TAD_SAVE."'></td>
	</tr>
  </table>
  </form>";

	$main=div_3d(_MA_INPUT_BOOK_FORM,$main);

	return $main;
}


//�s�W��ƨ�tad_book3��
function insert_tad_book3(){
	global $xoopsDB;
	

	if(!empty($_POST['new_tbcsn'])){
    $tbcsn=add_tad_book3_cate();
	}else{
		$tbcsn=$_POST['tbcsn'];
	}
	
	
	if(!empty($_POST['author_str'])){
    $author=$_POST['author_str'];
  }else{
    $author=implode(",",$_POST['author']);
  }

	$myts =& MyTextSanitizer::getInstance();
	$_POST['title']=$myts->addSlashes($_POST['title']);
	$_POST['description']=$myts->addSlashes($_POST['description']);

	$read_group=(in_array("",$_POST['read_group']))?"":implode(",",$_POST['read_group']);
    $now=date("Y-m-d H:i:s" , xoops_getUserTimestamp(time()));
	$sql = "insert into ".$xoopsDB->prefix("tad_book3")." (`tbcsn`,`sort`,`title`,`description`,`author`,`read_group`,`passwd`,`enable`,`pic_name`,`counter`,`create_date`) values('{$tbcsn}','{$_POST['sort']}','{$_POST['title']}','{$_POST['description']}','{$author}','{$read_group}','{$_POST['passwd']}','{$_POST['enable']}','{$_POST['pic_name']}','{$_POST['counter']}','{$now}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	//���o�̫�s�W��ƪ��y���s��
	$tbsn=$xoopsDB->getInsertId();

	if(!empty($_FILES['pic_name']['name'])){
    mk_thumb($tbsn,"pic_name",120);
	}


	return $tbsn;
}


//�s�W��ƨ�tad_book3_cate��
function add_tad_book3_cate(){
	global $xoopsDB,$xoopsModuleConfig;
	if(empty($_POST['new_tbcsn']))return;
	$myts =& MyTextSanitizer::getInstance();
	$title=$myts->addSlashes($_POST['new_tbcsn']);
	$sort=get_max_sort();
	$sql = "insert into ".$xoopsDB->prefix("tad_book3_cate")." (`of_tbsn`,`sort`,`title`) values('0','{$sort}','{$title}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	//���o�̫�s�W��ƪ��y���s��
	$tbcsn=$xoopsDB->getInsertId();
	return $tbcsn;
}


//�۰ʨ��o�s�Ƨ�
function get_max_sort(){
	global $xoopsDB,$xoopsModule;
	$sql = "select max(sort) from ".$xoopsDB->prefix("tad_book3_cate")." where of_tbsn=''";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	list($sort)=$xoopsDB->fetchRow($result);
	return ++$sort;
}


//��stad_book3�Y�@�����
function update_tad_book3($tbsn=""){
	global $xoopsDB;

	if(!empty($_POST['new_tbcsn'])){
    $tbcsn=add_tad_book3_cate();
	}else{
		$tbcsn=$_POST['tbcsn'];
	}
	
	
	if(!empty($_POST['author_str'])){
    $author=$_POST['author_str'];
  }else{
    $author=implode(",",$_POST['author']);
  }

	$myts =& MyTextSanitizer::getInstance();
	$_POST['title']=$myts->addSlashes($_POST['title']);
	$_POST['description']=$myts->addSlashes($_POST['description']);

	$read_group=(in_array("",$_POST['read_group']))?"":implode(",",$_POST['read_group']);
	$sql = "update ".$xoopsDB->prefix("tad_book3")." set  `tbcsn` = '{$tbcsn}', `sort` = '{$_POST['sort']}', `title` = '{$_POST['title']}', `description` = '{$_POST['description']}', `author` = '{$author}', `read_group` = '{$read_group}', `passwd` = '{$_POST['passwd']}', `enable` = '{$_POST['enable']}' where tbsn='$tbsn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	if(!empty($_FILES['pic_name']['name'])){
    mk_thumb($tbsn,"pic_name",120);
	}
	return $tbsn;
}


//�۰ʨ��o�s�Ƨ�
function get_max_doc_sort($tbcsn=""){
	global $xoopsDB,$xoopsModule;
	$sql = "select max(sort) from ".$xoopsDB->prefix("tad_book3")." where tbcsn='{$tbcsn}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	list($sort)=$xoopsDB->fetchRow($result);
	return ++$sort;
}




//�Y�ϤW��
function mk_thumb($tbsn="",$col_name="",$width=100){
	global $xoopsDB;
	include_once XOOPS_ROOT_PATH."/modules/tadtools/upload/class.upload.php";

	if(file_exists(_TADBOOK3_BOOK_DIR."/book_{$tbsn}.png")){
		unlink(_TADBOOK3_BOOK_DIR."/book_{$tbsn}.png");
	}
  $handle = new upload($_FILES[$col_name]);
  if ($handle->uploaded) {
      $handle->file_new_name_body   = "book_{$tbsn}";
      $handle->image_convert = 'png';
      $handle->image_resize         = true;
      $handle->image_x              = $width;
      $handle->image_ratio_y        = true;
      $handle->file_overwrite 			= true;
      $handle->process(_TADBOOK3_BOOK_DIR);
      $handle->auto_create_dir = true;
      if ($handle->processed) {
          $handle->clean();
          $sql = "update ".$xoopsDB->prefix("tad_book3")." set pic_name = 'book_{$tbsn}.png' where tbsn='$tbsn'";
					$xoopsDB->queryF($sql);
          return true;
      }else{
        die($handle->error);
      }
  }
  return false;
}


//book���v
function book_shadow($tbsn="",$pic="",$title="",$description="",$link="",$tool=""){
	$url=(empty($link))?"":"<a href='$link'>";
  $url2=(empty($link))?"":"</a>";

  $myts =& MyTextSanitizer::getInstance();
  $description=$myts->htmlSpecialChars($description);
  $title=$myts->htmlSpecialChars($title);
  
  
  $book_title=(empty($title))?"":"<div style='text-align:center;'>
			{$url}{$title}{$url2}
		</div>";
		

	$data="
	<div style='width:145px;height:250px;float:left;padding:0px;border:0px;margin-right:10px;' id='tr_{$tbsn}'>
	$tool
	<div id='tb3_shadow'><div>
  <a href='$link'><img src='{$pic}'  alt='$description' title='$description' /></a>
  </div></div>
	$book_title
	</div>
	
	";
	return $data;
}



//�ˬd�峹�K�X
function check_passwd($tbsn=""){
	global $xoopsDB;
	$sql = "select passwd from ".$xoopsDB->prefix("tad_book3")." where tbsn='$tbsn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	list($passwd)=$xoopsDB->fetchRow($result);
	if($_POST['passwd']==$passwd){
		$_SESSION['passwd']=$passwd;
	}
	header("location:".XOOPS_URL."/modules/tad_book3/index.php?op=list_docs&tbsn=$tbsn");
	exit;
}


//�H�y�������o�Y��tad_book3���
function get_tad_book3($tbsn=""){
	global $xoopsDB;
	if(empty($tbsn))return;
	$sql = "select * from ".$xoopsDB->prefix("tad_book3")." where tbsn='$tbsn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}

//���o�Ҧ�����
function all_cate(){
	global $xoopsDB,$xoopsModule;
	$sql = "select tbcsn,title from ".$xoopsDB->prefix("tad_book3_cate")." order by sort";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while(list($tbcsn,$title)=$xoopsDB->fetchRow($result)){
	  $main[$tbcsn]=$title;
	}
	return $main;
}

//�������
function cate_select($cate_sn=""){
	$all_cate=all_cate();
	foreach($all_cate as $tbcsn=>$title){
	  $selected=($cate_sn==$tbcsn)?"selected":"";
	  $main.="<option value=$tbcsn $selected>$title</option>";
	}
	return $main;
}

//���o�Ҧ��ѦW
function all_books(){
	global $xoopsDB,$xoopsModule;
	$sql = "select tbsn,title from ".$xoopsDB->prefix("tad_book3")." order by sort";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while(list($tbsn,$title)=$xoopsDB->fetchRow($result)){
	  $main[$tbsn]=$title;
	}
	return $main;
}

//�ѦW���
function book_select($book_sn=""){
	$all_books=all_books();
	foreach($all_books as $tbsn=>$title){
	  $selected=($book_sn==$tbsn)?"selected":"";
	  $main.="<option value=$tbsn $selected>$title</option>";
	}
	return $main;
}


//���ͳ��`���
function category_menu($num=""){
  $opt="";
	for($i=0;$i<=50;$i++){
	  $selected=($num==$i)?"selected":"";
		$opt.="<option value='{$i}' $selected>$i</option>";
	}
	return $opt;
}

//���o�e��峹
function near_docs($tbsn="",$doc_sn=""){
	global $xoopsDB;
	$sql = "select tbdsn,title,category,page,paragraph,sort from ".$xoopsDB->prefix("tad_book3_docs")." where tbsn='$tbsn' order by category,page,paragraph,sort";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$get_next=false;
	while(list($tbdsn,$title,$category,$page,$paragraph,$sort)=$xoopsDB->fetchRow($result)){
	  $doc_sort=mk_category($category,$page,$paragraph,$sort);
	  if($doc_sn==$tbdsn){
	  	$doc['main']="{$tbdsn};{$doc_sort['main']} {$title}";
	  	$get_next=true;
		}elseif($get_next){
		  $doc['next']="{$tbdsn};{$doc_sort['main']} {$title}";
		  return $doc;
		  break;
	  }else{
      $doc['prev']="{$tbdsn};{$doc_sort['main']} {$title}";
		}
	}
	return $doc;
}


//�峹���
function doc_select($tbsn="",$doc_sn=""){
	global $xoopsDB,$xoopsUser;
	
	if(empty($xoopsUser)){
    $andenable=" and `enable`='1'";
    $now_uid=0;
	}else{
    $andenable="";
    $now_uid=$xoopsUser->uid();
  }
  
	$sql = "select tbdsn,title,category,page,paragraph,sort,enable,uid from ".$xoopsDB->prefix("tad_book3_docs")." where tbsn='$tbsn' $andenable order by category,page,paragraph,sort";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while(list($tbdsn,$title,$category,$page,$paragraph,$sort,$enable,$uid)=$xoopsDB->fetchRow($result)){
	  $selected=($doc_sn==$tbdsn)?"selected":"";
	  $doc_sort=mk_category($category,$page,$paragraph,$sort);

    $stat='';
	  if($enable!='1'){
      if($now_uid!=$uid){
        continue;
      }else{
        $style=" style='color:gray;'";
        $stat="["._MI_TADBOOK3_UNABLE."] ";
      }
    }else{
      $style=" style='color:black;'";
    }
	  $main.="<option value=$tbdsn $selected $style>".str_repeat("&nbsp;",($doc_sort['level']-1)*2)."{$doc_sort['main']} {$stat}{$title}</option>";
	}
	return $main;
}

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

//���o�Ҧ��s��
function get_all_groups(){
	global $xoopsDB;
	$sql = "select groupid,name from ".$xoopsDB->prefix("groups")."";
	$result = $xoopsDB->query($sql);
	while(list($groupid,$name)=$xoopsDB->fetchRow($result)){
		$data[$groupid]=$name;
	}
	return $data;
}

//��r�괫���s��
function txt_to_group_name($enable_group="",$default_txt="",$syb="<br />"){
	$groups_array=get_all_groups();
	if(empty($enable_group)){
    $g_txt=$default_txt;
	}else{
	  $gs=explode(",",$enable_group);
	  $g_txt="";
	  foreach($gs as $gid){
    	$g_txt.=$groups_array[$gid]."{$syb}";
		}
	}
	return $g_txt;
}

//�P�_����O�_���\�ӥΤᤧ���ݸs���[��
function chk_power($enable_group=""){
	global $xoopsDB,$xoopsUser;
	if(empty($enable_group))return true;
	
	//���o�ثe�ϥΪ̪����ݸs��
	if($xoopsUser){
		$User_Groups=$xoopsUser->getGroups();
	}else{
		$User_Groups=array();
	}

	$news_enable_group=explode(",",$enable_group);
	foreach($User_Groups as $gid){
		if(in_array($gid,$news_enable_group)){
			return true;
		}
	}
	return false;
}


//�P�_����O�_���\�ӥΤ�s��
function chk_edit_power($uid_txt=""){
	global $xoopsDB,$xoopsUser;
	if(empty($uid_txt))return false;

	//���o�ثe�ϥΪ̪����ݸs��
	if($xoopsUser){
		$user_id=$xoopsUser->getVar('uid');
	}else{
		$user_id=array();
	}

	$uid_arr=explode(",",$uid_txt);

	if(in_array($user_id,$uid_arr)){
		return true;
	}

	return false;
}


/********************* �w�]��� *********************/
//�ꨤ��r��
function div_3d($title="",$main="",$kind="raised",$style=""){
	$main="<table style='width:auto;{$style}'><tr><td>
	<div class='{$kind}'>
	<h1>$title</h1>
	<b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b>
	<div class='boxcontent'>
 	$main
	</div>
	<b class='b4b'></b><b class='b3b'></b><b class='b2b'></b><b class='b1b'></b>
	</div>
	</td></tr></table>";
	return $main;
}


?>
