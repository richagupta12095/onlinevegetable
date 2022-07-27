<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="ci-content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-cicomment" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-cicomment" class="form-horizontal">
          <?php /* <div class="form-group">
            <label class="col-sm-2 control-label" for="input-language_id"><?php echo $entry_language; ?></label>
            <div class="col-sm-10">
              <select name="language_id" id="input-language_id" class="form-control">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($languages as $language) { ?>
                <?php if ($language_id == $language['language_id']) { ?>
                <option value="<?php echo $language['language_id']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div> */ ?>
          <?php /* <div class="form-group">
            <label class="col-sm-2 control-label" for="input-store_id"><?php echo $entry_store; ?></label>
            <div class="col-sm-10">
              <select name="store_id" id="input-store_id" class="form-control">
                <?php foreach ($stores as $store) { ?>
                <?php if ($store_id == $store['store_id']) { ?>
                <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div> */ ?>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-author"><?php echo $entry_author; ?></label>
            <div class="col-sm-10">
              <input type="text" name="author" value="<?php echo $author; ?>" placeholder="<?php echo $entry_author; ?>" id="input-author" class="form-control" />
              <?php if ($error_author) { ?>
              <div class="text-danger"><?php echo $error_author; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-ciblog_post"><span data-toggle="tooltip" title="<?php echo $help_ciblog_post; ?>"><?php echo $entry_ciblog_post; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="ciblog_post" value="<?php echo $ciblog_post; ?>" placeholder="<?php echo $entry_ciblog_post; ?>" id="input-ciblog_post" class="form-control" />
              <input type="hidden" name="ciblog_post_id" value="<?php echo $ciblog_post_id; ?>" />
              <?php if ($error_ciblog_post) { ?>
              <div class="text-danger"><?php echo $error_ciblog_post; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-text"><?php echo $entry_text; ?></label>
            <div class="col-sm-10">
              <textarea name="text" cols="60" rows="8" placeholder="<?php echo $entry_text; ?>" id="input-text" class="form-control"><?php echo $text; ?></textarea>
              <?php if ($error_text) { ?>
              <div class="text-danger"><?php echo $error_text; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_rating; ?></label>
            <div class="col-sm-10">
              <div>
                <?php for($i=1;$i<=5;$i++) { ?>
                <label class="radio-inline">
                  <input type="radio" name="rating" value="<?php echo $i; ?>" <?php if ($rating == $i) { echo 'checked="checked"'; } ?> /> <?php echo $i; ?>
                </label>
                <?php } ?>
              </div>
              <?php if ($error_rating) { ?>
              <div class="text-danger"><?php echo $error_rating; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
            <div class="col-sm-3">
              <div class="input-group datetime">
                <input type="text" name="date_added" value="<?php echo $date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD HH:mm:ss" id="input-date-added" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
            <div class="col-sm-3">
              <div class="input-group datetime">
                <input type="text" name="date_modified" value="<?php echo $date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD HH:mm:ss" id="input-date-modified" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'ciblog_post\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=extension/ciblog/ciblogpost/autocomplete&<?php echo $var_octoken; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['ciblog_post_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'ciblog_post\']').val(item['label']);
		$('input[name=\'ciblog_post_id\']').val(item['value']);
	}
});
//--></script></div>
<?php echo $footer; ?>