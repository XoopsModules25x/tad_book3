<h3 class="my"><{$smarty.const._MD_INPUT_DOC_FORM}></h3>

<form action="<{$action}>" method="post" id="myForm" enctype="multipart/form-data" role="form" class="form-horizontal">

    <div class="form-group row">
        <label class="col-sm-1 control-label col-form-label text-md-right">
            <{$smarty.const._MD_TADBOOK3_TITLE}>
        </label>
        <div class="col-sm-5">
            <select name="tbsn" class="form-control">
                <{$book_select}>
            </select>
        </div>

        <label class="col-sm-1 control-label col-form-label text-md-right">
            <{$smarty.const._MD_TADBOOK3_STATUS}>
        </label>
        <div class="col-sm-2">
            <div class="form-check-inline radio-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="enable" id="enable1" value="1" <{if $enable=="1"}>checked<{/if}>>
                    <{$smarty.const._MD_TADBOOK3_ENABLE}>
                </label>
            </div>
            <div class="form-check-inline radio-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="enable" id="enable0" value="0" <{if $enable=="0"}>checked<{/if}>>
                    <{$smarty.const._MD_TADBOOK3_UNABLE}>
                </label>
            </div>
        </div>
        <label class="col-sm-1 control-label col-form-label text-md-right">
            <{if $from_tbdsn}>
                <a href="page.php?tbdsn=<{$from_tbdsn}>" target="_blank"><{$smarty.const._MD_TADBOOK3_FROM_TBDSN}></a>
            <{else}>
                <{$smarty.const._MD_TADBOOK3_FROM_TBDSN}>
            <{/if}>
        </label>
        <div class="col-sm-2">
            <input type="text" name="from_tbdsn" id="from_tbdsn" value="<{$from_tbdsn}>" class="form-control" placeholder="<{$smarty.const._MD_TADBOOK3_FROM_TBDSN_DESC}>">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-1 control-label col-form-label text-md-right">
            <{$smarty.const._MD_TADBOOK3_DOC_TITLE}>
        </label>
        <div class="col-sm-5">
            <input type="text" name="title" id="title" value="<{$title}>" class="form-control" placeholder="<{$smarty.const._MD_TADBOOK3_DOC_TITLE}>">
        </div>

        <label class="col-sm-1 control-label col-form-label text-md-right">
        <{$smarty.const._MD_TADBOOK3_CATEGORY}>
        </label>
        <div class="col-sm-5">
            <select name="category" size=1 class="form-control" style="width: 23%; display: inline;">
                <{$category_menu_category}>
            </select>
            <select name="page" size=1 class="form-control" style="width: 23%; display: inline;">
                <{$category_menu_page}>
            </select>
            <select name="paragraph" size=1 class="form-control" style="width: 23%; display: inline;">
                <{$category_menu_paragraph}>
            </select>
            <select name="sort" size=1 class="form-control" style="width: 23%; display: inline;">
                <{$category_menu_sort}>
            </select>
        </div>
    </div>


    <div class="form-group row">
        <div class="col-sm-12">
        <{$editor}>
        </div>
    </div>

    <div class="bar">
        <input type="hidden" name="tbdsn" value="<{$tbdsn}>">
        <input type="hidden" name="op" value="<{$op}>">
        <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
    </div>

</form>