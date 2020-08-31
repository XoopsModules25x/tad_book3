<?php
use Xmf\Request;
use XoopsModules\Tadtools\CkEditor;
use XoopsModules\Tadtools\FormValidator;
use XoopsModules\Tadtools\SweetAlert;
use XoopsModules\Tadtools\Utility;
use XoopsModules\Tadtools\Ztree;
/*-----------引入檔案區--------------*/
$xoopsOption['template_main'] = 'tadbook3_admin.tpl';
require_once __DIR__ . '/header.php';
require_once dirname(__DIR__) . '/function.php';

/*-----------function區--------------*/
//tad_book3_cate編輯表單
function tad_book3_cate_form($tbcsn = '')
{
    global $xoopsDB, $xoopsUser, $xoopsTpl;

    //抓取預設值
    if (!empty($tbcsn)) {
        $DBV = get_tad_book3_cate($tbcsn);
    } else {
        $DBV = [];
    }

    //預設值設定

    //設定「tbcsn」欄位預設值
    $tbcsn = (!isset($DBV['tbcsn'])) ? '' : $DBV['tbcsn'];

    //設定「of_tbsn」欄位預設值
    $of_tbsn = (!isset($DBV['of_tbsn'])) ? '' : $DBV['of_tbsn'];

    //設定「title」欄位預設值
    $title = (!isset($DBV['title'])) ? '' : $DBV['title'];

    //設定「sort」欄位預設值
    $sort = (!isset($DBV['sort'])) ? tad_book3_cate_max_sort() : $DBV['sort'];

    //設定「description」欄位預設值
    $description = (!isset($DBV['description'])) ? '' : $DBV['description'];

    $op = (empty($tbcsn)) ? 'insert_tad_book3_cate' : 'update_tad_book3_cate';
    //$op="replace_tad_book3_cate";

    $FormValidator = new FormValidator('#myForm', true);
    $FormValidator->render();

    $xoopsTpl->assign('op', 'tad_book3_cate_form');
    $xoopsTpl->assign('next_op', $op);
    $xoopsTpl->assign('tbcsn', $tbcsn);
    $xoopsTpl->assign('sort', $sort);
    $xoopsTpl->assign('title', $title);
    $xoopsTpl->assign('description', $description);

    $ck = new CkEditor('tad_book3', 'description', $description);
    $ck->setHeight(200);
    $editor = $ck->render();
    $xoopsTpl->assign('editor', $editor);
}

//新增資料到tad_book3_cate中
function insert_tad_book3_cate()
{
    global $xoopsDB, $xoopsUser;

    $myts = \MyTextSanitizer::getInstance();
    $_POST['title'] = $myts->addSlashes($_POST['title']);
    $_POST['description'] = $myts->addSlashes($_POST['description']);
    $_POST['of_tbsn'] = (int) $_POST['of_tbsn'];
    $_POST['sort'] = (int) $_POST['sort'];

    $sql = 'insert into ' . $xoopsDB->prefix('tad_book3_cate') . "
    (`of_tbsn` , `title` , `sort` , `description`)
    values('{$_POST['of_tbsn']}' , '{$_POST['title']}' , '{$_POST['sort']}' , '{$_POST['description']}')";
    $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    //取得最後新增資料的流水編號
    $tbcsn = $xoopsDB->getInsertId();

    return $tbcsn;
}

//更新tad_book3_cate某一筆資料
function update_tad_book3_cate($tbcsn = '')
{
    global $xoopsDB, $xoopsUser;

    $myts = \MyTextSanitizer::getInstance();
    $_POST['title'] = $myts->addSlashes($_POST['title']);
    $_POST['description'] = $myts->addSlashes($_POST['description']);

    $sql = 'update ' . $xoopsDB->prefix('tad_book3_cate') . " set
     `of_tbsn` = '{$_POST['of_tbsn']}' ,
     `title` = '{$_POST['title']}' ,
     `sort` = '{$_POST['sort']}' ,
     `description` = '{$_POST['description']}'
    where tbcsn='$tbcsn'";
    $xoopsDB->queryF($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    return $tbcsn;
}

//取得tad_book3_cate無窮分類列表
function list_tad_book3_cate_tree($show_tbcsn = 0)
{
    global $xoopsTpl, $xoopsDB;
    $path = get_tad_book3_cate_path($show_tbcsn);
    $path_arr = array_keys($path);
    $sql = 'SELECT tbcsn,of_tbsn,title FROM ' . $xoopsDB->prefix('tad_book3_cate') . ' ORDER BY `sort`';
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);

    $count = tad_book3_cate_count();
    $data[] = "{ id:0, pId:0, name:'All', url:'index.php', target:'_self', open:true}";
    while (list($tbcsn, $of_tbsn, $title) = $xoopsDB->fetchRow($result)) {
        $font_style = $show_tbcsn == $tbcsn ? ", font:{'background-color':'yellow', 'color':'black'}" : '';
        $open = in_array($tbcsn, $path_arr) ? 'true' : 'false';
        $display_counter = empty($count[$tbcsn]) ? '' : " ({$count[$tbcsn]})";
        $data[] = "{ id:{$tbcsn}, pId:{$of_tbsn}, name:'{$title}{$display_counter}', url:'main.php?tbcsn={$tbcsn}', target:'_self', open:{$open} {$font_style}}";
    }
    $json = implode(',', $data);

    $Ztree = new Ztree('link_tree', $json, '', 'save_sort.php', 'of_tbsn', 'tbcsn');
    $ztree_code = $Ztree->render();
    $xoopsTpl->assign('ztree_code', $ztree_code);
}

//秀出所有分類及書籍
function list_tad_book3($tbcsn = '')
{
    global $xoopsDB, $xoopsTpl;

    $and_tbcsn = !empty($tbcsn) ? "and `tbcsn`='{$tbcsn}'" : '';
    $sql = 'select * from  ' . $xoopsDB->prefix('tad_book3') . " where 1 $and_tbcsn order by `sort`";
    //getPageBar($原sql語法, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
    $PageBar = Utility::getPageBar($sql, 10, 10);
    $bar = $PageBar['bar'];
    $sql = $PageBar['sql'];
    $total = $PageBar['total'];
    $result = $xoopsDB->query($sql) or Utility::web_error($sql, __FILE__, __LINE__);
    $i = 0;
    $books = [];
    while (false !== ($data = $xoopsDB->fetchArray($result))) {
        $books[$i] = $data;
        $books[$i]['cate'] = get_tad_book3_cate($data['tbcsn']);
        $uid_name = [];
        $author_arr = explode(',', $data['author']);
        foreach ($author_arr as $uid) {
            $uidname = \XoopsUser::getUnameFromId($uid, 1);
            $uidname = (empty($uidname)) ? XoopsUser::getUnameFromId($uid, 0) : $uidname;
            $uid_name[] = $uidname;
        }
        $books[$i]['author'] = implode(' , ', $uid_name);
        $books[$i]['read_groups'] = Utility::txt_to_group_name($read_group, _MD_TADBOOK3_ALL_OPEN);
        $i++;
    }
    $xoopsTpl->assign('books', $books);
    $xoopsTpl->assign('bar', $bar);
    $xoopsTpl->assign('total', $total);
    $cate = '';
    if ($tbcsn) {
        $cate = get_tad_book3_cate($tbcsn);
    }
    //die(var_export($cate));
    $xoopsTpl->assign('cate', $cate);
    $xoopsTpl->assign('tbcsn', $tbcsn);

    $SweetAlert = new SweetAlert();
    $SweetAlert->render('delete_tad_book3_cate_func', 'main.php?op=delete_tad_book3_cate&tbcsn=', 'tbcsn');

    //刪除書籍
    $SweetAlert2 = new SweetAlert();
    $SweetAlert2->render('delete_tad_book3_func', 'main.php?op=delete_tad_book3&tbsn=', 'tbsn');
}

/*-----------執行動作判斷區----------*/
$op = Request::getString('op');
$tbsn = Request::getInt('tbsn');
$tbdsn = Request::getInt('tbdsn');
$link_sn = Request::getInt('link_sn');

switch ($op) {
    /*---判斷動作請貼在下方---*/
    //替換資料
    case 'replace_tad_book3_cate':
        replace_tad_book3_cate();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //新增資料
    case 'insert_tad_book3_cate':
        $tbcsn = insert_tad_book3_cate();
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //更新資料
    case 'update_tad_book3_cate':
        update_tad_book3_cate($tbcsn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //刪除資料
    case 'delete_tad_book3_cate':
        delete_tad_book3_cate($tbcsn);
        header("location: {$_SERVER['PHP_SELF']}");
        exit;

    //刪除資料
    case 'delete_tad_book3':
        delete_tad_book3($tbsn);
        header('location: ../index.php');
        exit;

    //輸入表格
    case 'tad_book3_cate_form':
        list_tad_book3_cate_tree($tbcsn);
        tad_book3_cate_form($tbcsn);
        break;

    //預設動作
    default:
        list_tad_book3_cate_tree($tbcsn);
        list_tad_book3($tbcsn);
        $op = 'list_tad_book3';
        break;
        /*---判斷動作請貼在上方---*/
}

/*-----------秀出結果區--------------*/
$xoopsTpl->assign("now_op", $op);
$xoTheme->addStylesheet(XOOPS_URL . '/modules/tad_book3/css/module.css');
require_once __DIR__ . '/footer.php';
