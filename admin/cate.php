<?php
/*-----------�ޤJ�ɮװ�--------------*/
$xoopsOption['template_main'] = "tadbook3_adm_cate.html";
include_once "header.php";
include_once "../function.php";

/*-----------function��--------------*/
//tad_book3_cate�s����
function tad_book3_cate_form($tbcsn=""){
  global $xoopsDB,$xoopsTpl;
  include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
  include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");

  //����w�]��
  if(!empty($tbcsn)){
    $DBV=get_tad_book3_cate($tbcsn);
  }else{
    $DBV=array();
  }

  //�w�]�ȳ]�w

  $tbcsn=(!isset($DBV['tbcsn']))?"":$DBV['tbcsn'];
  $of_tbsn=(!isset($DBV['of_tbsn']))?"":$DBV['of_tbsn'];
  $sort=(!isset($DBV['sort']))?get_max_sort():$DBV['sort'];
  $title=(!isset($DBV['title']))?"":$DBV['title'];
  $description=(!isset($DBV['description']))?"":$DBV['description'];


  if(!file_exists(XOOPS_ROOT_PATH."/modules/tadtools/fck.php")){
    redirect_header("index.php",3, _MA_NEED_TADTOOLS);
  }
  include_once XOOPS_ROOT_PATH."/modules/tadtools/fck.php";
  $fck=new FCKEditor264("tad_book3","description",$description);
  $fck->setwidth(600);
  $fck->setHeight(150);
  $editor=$fck->render();

  $op=(empty($tbcsn))?"insert_tad_book3_cate":"update_tad_book3_cate";

  $xoopsTpl->assign('sort',$sort);
  $xoopsTpl->assign('title',$title);
  $xoopsTpl->assign('editor',$editor);
  $xoopsTpl->assign('tbcsn',$tbcsn);
  $xoopsTpl->assign('next_op',$op);
  $xoopsTpl->assign('op','tad_book3_cate_form');

}


//�s�W��ƨ�tad_book3_cate��
function insert_tad_book3_cate(){
  global $xoopsDB;
  $myts =& MyTextSanitizer::getInstance();
  $_POST['title']=$myts->addSlashes($_POST['title']);
  $_POST['description']=$myts->addSlashes($_POST['description']);
  $sql = "insert into ".$xoopsDB->prefix("tad_book3_cate")." (`of_tbsn`,`sort`,`title`,`description`) values('{$_POST['of_tbsn']}','{$_POST['sort']}','{$_POST['title']}','{$_POST['description']}')";
  $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  //���o�̫�s�W��ƪ��y���s��
  $tbcsn=$xoopsDB->getInsertId();
  return $tbcsn;
}

//�C�X�Ҧ�tad_book3_cate���
function list_tad_book3_cate(){
  global $xoopsDB,$xoopsModule,$xoopsTpl;

  $all_cate=all_cate();

  $sql = "select * from ".$xoopsDB->prefix("tad_book3_cate")." order by sort";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data="";
  $i=0;
  while(list($tbcsn,$of_tbsn,$sort,$title,$description)=$xoopsDB->fetchRow($result)){
    $data[$i]['tbcsn']=$tbcsn;
    $data[$i]['of_tbsn']=$of_tbsn;
    $data[$i]['sort']=$sort;
    $data[$i]['title']=$title;
    $data[$i]['description']=$description;
    $i++;
  }

  if(empty($data)){
    header("location:{$_SERVER['PHP_SELF']}?op=tad_book3_cate_form");
    exit;
  }
  $xoopsTpl->assign('data',$data);
  $xoopsTpl->assign('jquery',get_jquery(true));


}


//�H�y�������o�Y��tad_book3_cate���
function get_tad_book3_cate($tbcsn=""){
  global $xoopsDB;
  if(empty($tbcsn))return;
  $sql = "select * from ".$xoopsDB->prefix("tad_book3_cate")." where tbcsn='$tbcsn'";
  $result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  $data=$xoopsDB->fetchArray($result);
  return $data;
}

//��stad_book3_cate�Y�@�����
function update_tad_book3_cate($tbcsn=""){
  global $xoopsDB;
  $myts =& MyTextSanitizer::getInstance();
  $_POST['title']=$myts->addSlashes($_POST['title']);
  $_POST['description']=$myts->addSlashes($_POST['description']);

  $sql = "update ".$xoopsDB->prefix("tad_book3_cate")." set  `of_tbsn` = '{$_POST['of_tbsn']}', `sort` = '{$_POST['sort']}', `title` = '{$_POST['title']}', `description` = '{$_POST['description']}' where tbcsn='$tbcsn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
  return $tbcsn;
}

//�R��tad_book3_cate�Y����Ƹ��
function delete_tad_book3_cate($tbcsn=""){
  global $xoopsDB;
  $sql = "delete from ".$xoopsDB->prefix("tad_book3_cate")." where tbcsn='$tbcsn'";
  $xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}


/*-----------����ʧ@�P�_��----------*/
$op = (!isset($_REQUEST['op']))? "":$_REQUEST['op'];
$tbcsn = (!isset($_REQUEST['tbcsn']))? "":intval($_REQUEST['tbcsn']);

switch($op){
  //��s���
  case "update_tad_book3_cate";
  update_tad_book3_cate($tbcsn);
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //�s�W���
  case "insert_tad_book3_cate":
  insert_tad_book3_cate();
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //��J���
  case "tad_book3_cate_form";
  tad_book3_cate_form($tbcsn);
  break;

  //�R�����
  case "delete_tad_book3_cate";
  delete_tad_book3_cate($tbcsn);
  header("location: {$_SERVER['PHP_SELF']}");
  break;

  //�w�]�ʧ@
  default:
  list_tad_book3_cate();
  break;



}

/*-----------�q�X���G��--------------*/
include_once 'footer.php';
?>
