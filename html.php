<?php
use XoopsModules\Tadtools\SyntaxHighlighter;
use XoopsModules\Tadtools\Utility;

require_once 'header.php';
set_time_limit(0);
ini_set('memory_limit', '150M');

require_once $GLOBALS['xoops']->path('/modules/system/include/functions.php');
$op = system_CleanVars($_REQUEST, 'op', '', 'string');
$tbdsn = system_CleanVars($_REQUEST, 'tbdsn', 0, 'int');
$header = system_CleanVars($_REQUEST, 'header', 1, 'int');
$artical = get_tad_book3_docs($tbdsn);
foreach ($artical as $key => $value) {
    $$key = $value;
}
$doc_sort = mk_category($category, $page, $paragraph, $sort);
$book = get_tad_book3($tbsn);
//高亮度語法
$SyntaxHighlighter = new SyntaxHighlighter();
$syntaxhighlighter_code = $SyntaxHighlighter->render();
$bootstrap = Utility::get_bootstrap('return');

$html = '<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
  <meta charset="utf-8">
  <title>' . $book['title'] . '-' . $doc_sort['main'] . '-' . $title . '</title>
  ' . $bootstrap . '
  <link rel="stylesheet" type="text/css" href="reset.css" >
  <style type="text/css">
    body{
      font-size: 12pt;
    }

    .page{
      font-size: 12pt;
      line-height:2;
      padding: 2cm;
      background-image: url(' . XOOPS_URL . '/modules/tad_book3/images/paper_bg.jpg);
      background-repeat: repeat-x;
    }

    .page_content{
      font-size: 12pt;
    }

    .page_title{
      border-bottom: 1px solid black;
      text-align:right;
      color:black;
      margin-bottom:20px;
    }
  </style>
  </head>
  <body>' . $syntaxhighlighter_code;

$html .= view_page($tbdsn, $header);
$html .= '
  </body>
</html>';
die($html);

//觀看某一頁
function view_page($tbdsn = '', $header = 1)
{
    global $xoopsDB, $book, $artical, $doc_sort;

    foreach ($artical as $key => $value) {
        $$key = $value;
    }

    if (!empty($from_tbdsn)) {
        $form_page = get_tad_book3_docs($from_tbdsn);
        $content .= $form_page['content'];
    }

    if (!chk_power($book['read_group'])) {
        header('location:index.php');
        exit;
    }

    if (!empty($book['passwd']) and $_SESSION['passwd'] != $book['passwd']) {
        $data .= _MD_TADBOOK3_INPUT_PASSWD;

        return $data;
        exit;
    }
    $page_title = $header ? "<div class='page_title'>{$book['title']}</div>" : '';

    $main = "
    <div class='page'>
      $page_title
      <div class='page_content'>
        <h{$doc_sort['level']}>{$doc_sort['main']} {$title}</h{$doc_sort['level']}>
        $content
      </div>
    </div>
    ";

    return $main;
}
