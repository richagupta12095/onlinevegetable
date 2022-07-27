<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="ci-content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-cicomment').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php echo $cimenu; ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-ciblog_post"><?php echo $entry_ciblog_post; ?></label>
                <input type="text" name="filter_ciblog_post" value="<?php echo $filter_ciblog_post; ?>" placeholder="<?php echo $entry_ciblog_post; ?>" id="input-ciblog_post" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-rating"><?php echo $entry_rating; ?></label>
                <select name="filter_rating" id="input-rating" class="form-control">
                  <option value="*"></option>
                  <option value="1" <?php if ($filter_rating==1) { echo 'selected="selected"'; } ?>>1</option>
                  <option value="2" <?php if ($filter_rating==2) { echo 'selected="selected"'; } ?>>2</option>
                  <option value="3" <?php if ($filter_rating==3) { echo 'selected="selected"'; } ?>>3</option>
                  <option value="4" <?php if ($filter_rating==4) { echo 'selected="selected"'; } ?>>4</option>
                  <option value="5" <?php if ($filter_rating==5) { echo 'selected="selected"'; } ?>>5</option>
                </select>
              </div>
              <?php /* <div class="form-group">
                <label class="control-label" for="input-language"><?php echo $entry_language; ?></label>
                <select name="filter_language_id" id="input-language" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($languages as $language) { ?>
                  <option value="<?php echo $language['language_id']; ?>" <?php if ($filter_language_id==$language['language_id']) { echo 'selected="selected"'; } ?>><?php echo $language['name']; ?></option>
                  <?php } ?>
                </select>
              </div> */ ?>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-author"><?php echo $entry_author; ?></label>
                <input type="text" name="filter_author" value="<?php echo $filter_author; ?>" placeholder="<?php echo $entry_author; ?>" id="input-author" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <?php /* <div class="form-group">
                <label class="control-label" for="input-store"><?php echo $entry_store; ?></label>
                <select name="filter_store_id" id="input-store" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($stores as $store) { ?>
                  <option value="<?php echo $store['store_id']; ?>" <?php if ($filter_store_id==$store['store_id']) { echo 'selected="selected"'; } ?>><?php echo $store['name']; ?></option>
                  <?php } ?>
                </select>
              </div> */ ?>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-cicomment">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_ciblog_post; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_ciblog_post; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_ciblog_post; ?>"><?php echo $column_ciblog_post; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.author') { ?>
                    <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.email') { ?>
                    <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.rating') { ?>
                    <a href="<?php echo $sort_rating; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_rating; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_rating; ?>"><?php echo $column_rating; ?></a>
                    <?php } ?></td>
                  <?php /*<td class="text-right"><?php if ($sort == 'r.store_id') { ?>
                    <a href="<?php echo $sort_store_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_store; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_store_id; ?>"><?php echo $column_store; ?></a>
                    <?php } ?></td> */ ?>
                  <?php /*<td class="text-right"><?php if ($sort == 'r.language_id') { ?>
                    <a href="<?php echo $sort_language_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_language; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_language_id; ?>"><?php echo $column_language; ?></a>
                    <?php } ?></td> */ ?>
                  <td class="text-left"><?php if ($sort == 'r.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'r.date_modified') { ?>
                    <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($cicomments) { ?>
                <?php foreach ($cicomments as $cicomment) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($cicomment['ciblog_comment_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $cicomment['ciblog_comment_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $cicomment['ciblog_comment_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php if ($cicomment['ciblog_post_href']) { ?><a href="<?php echo $cicomment['ciblog_post_href']; ?>" target="_blank"><?php echo $cicomment['ciblog_post']; ?></a><?php } else { ?><?php echo $cicomment['ciblog_post']; ?><?php } ?></td>
                  <td class="text-left"><?php echo $cicomment['author']; ?></td>
                  <td class="text-left"><?php echo $cicomment['email']; ?></td>
                  <td class="text-left">
                    <div class="rating">
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <?php if ($cicomment['rating'] < $i) { ?>
                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
                    <?php } else { ?>
                    <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
                    <?php } ?>
                    <?php } ?>
                  </div>
                  </td>
                  <?php /*<td class="text-right"><?php echo $cicomment['store']; ?></td> */ ?>
                  <?php /*<td class="text-right"><?php echo $cicomment['language']; ?></td> */ ?>
                  <td class="text-left"><label class="label label-<?php echo $cicomment['status'] ? 'success' : 'danger'; ?>"><?php echo $cicomment['status1']; ?></label></td>
                  <td class="text-left"><?php echo $cicomment['date_added']; ?></td>
                  <td class="text-left"><?php echo $cicomment['date_modified']; ?></td>
                  <td class="text-right"><a href="<?php echo $cicomment['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
<style type="text/css">
  .rating .fa-stack i {
      font-size: 24px;
      color: <?php echo !empty($reviewgraph_color) ? $reviewgraph_color : '#FC0'; ?>;
  }
</style>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=extension/ciblog/cicomment&<?php echo $var_octoken; ?>=<?php echo $token; ?>';

	var filter_ciblog_post = $('input[name=\'filter_ciblog_post\']').val();

	if (filter_ciblog_post) {
		url += '&filter_ciblog_post=' + encodeURIComponent(filter_ciblog_post);
	}

	var filter_author = $('input[name=\'filter_author\']').val();

	if (filter_author) {
		url += '&filter_author=' + encodeURIComponent(filter_author);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }
  var filter_rating = $('select[name=\'filter_rating\']').val();

  if (filter_rating != '*') {
    url += '&filter_rating=' + encodeURIComponent(filter_rating);
  }
  <?php /*var filter_language_id = $('select[name=\'filter_language_id\']').val();

  if (filter_language_id != '*') {
    url += '&filter_language_id=' + encodeURIComponent(filter_language_id);
  } */ ?>
  <?php /* var filter_store_id = $('select[name=\'filter_store_id\']').val();

	if (filter_store_id != '*') {
		url += '&filter_store_id=' + encodeURIComponent(filter_store_id);
	} */ ?>

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

  if (filter_date_added) {
    url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
  }

  var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}

	location = url;
});
//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>