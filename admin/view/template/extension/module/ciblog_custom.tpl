<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ciblog_custom" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ciblog_custom" class="form-horizontal">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
        </div>
        <div class="panel-body">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
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
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-blog-image-width"><?php echo $entry_blog_image; ?></label>
            <div class="col-sm-10">
              <div class="row">
                <div class="col-sm-6">
                  <div class="input-group"><span class="input-group-addon"><?php echo $text_w; ?></span><input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-blog-image-width" class="form-control" /></div>
                </div>
                <div class="col-sm-6">
                  <div class="input-group"><span class="input-group-addon"><?php echo $text_h; ?></span><input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-blog-image-height" class="form-control" /></div>
                </div>
              </div>
              <?php if ($error_blog_image) { ?>
              <div class="text-danger"><?php echo $error_blog_image; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-limit"><span data-toggle="tooltip" title="<?php echo $help_limit; ?>"><?php echo $entry_limit; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_select_blog; ?></h3>
        </div>
        <div class="panel-body">

          <fieldset>
            <legend><?php echo $text_category; ?></legend>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
              <div class="col-sm-10">
                <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                <div id="ciblog-category" class="well well-sm" style="height: 150px; overflow: auto;">
                  <?php foreach ($ciblog_categories as $ciblog_category) { ?>
                  <div id="ciblog-category<?php echo $ciblog_category['ciblog_category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $ciblog_category['name']; ?>
                    <input type="hidden" name="ciblog_category[]" value="<?php echo $ciblog_category['ciblog_category_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-category_sort_order"><?php echo $entry_category_sort_order; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ciblog_category_sort_order" value="<?php echo $ciblog_category_sort_order; ?>" placeholder="<?php echo $entry_category_sort_order; ?>" id="input-category_sort_order" class="form-control" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend><?php echo $text_author; ?></legend>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-author"><span data-toggle="tooltip" title="<?php echo $help_author; ?>"><?php echo $entry_author; ?></span></label>
              <div class="col-sm-10">
                <input type="text" name="author" value="" placeholder="<?php echo $entry_author; ?>" id="input-author" class="form-control" />
                <div id="ciblog-author" class="well well-sm" style="height: 150px; overflow: auto;">
                  <?php foreach ($ciblog_authors as $ciblog_author) { ?>
                  <div id="ciblog-author<?php echo $ciblog_author['ciblog_author_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $ciblog_author['name']; ?>
                    <input type="hidden" name="ciblog_author[]" value="<?php echo $ciblog_author['ciblog_author_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-author_sort_order"><?php echo $entry_author_sort_order; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ciblog_author_sort_order" value="<?php echo $ciblog_author_sort_order; ?>" placeholder="<?php echo $entry_author_sort_order; ?>" id="input-author_sort_order" class="form-control" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend><?php echo $text_custom; ?></legend>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-custom"><span data-toggle="tooltip" title="<?php echo $help_custom; ?>"><?php echo $entry_custom; ?></span></label>
              <div class="col-sm-10">
                <input type="text" name="custom" value="" placeholder="<?php echo $entry_custom; ?>" id="input-custom" class="form-control" />
                <div id="ciblog-custom" class="well well-sm" style="height: 150px; overflow: auto;">
                  <?php foreach ($ciblog_customs as $ciblog_custom) { ?>
                  <div id="ciblog-custom<?php echo $ciblog_custom['ciblog_post_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $ciblog_custom['name']; ?>
                    <input type="hidden" name="ciblog_custom[]" value="<?php echo $ciblog_custom['ciblog_post_id']; ?>" />
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-custom_sort_order"><?php echo $entry_custom_sort_order; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ciblog_custom_sort_order" value="<?php echo $ciblog_custom_sort_order; ?>" placeholder="<?php echo $entry_custom_sort_order; ?>" id="input-custom_sort_order" class="form-control" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend><?php echo $text_topview; ?></legend>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-topview"><?php echo $entry_topview; ?></label>
              <div class="col-sm-3">
                <div class="btn-group" data-toggle="buttons">
                  <label class="btn btn-default <?php echo $ciblog_topview ? 'active' : ''; ?>">
                    <input name="ciblog_topview" <?php echo $ciblog_topview ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_yes; ?>
                  </label>
                  <label class="btn btn-default <?php echo !$ciblog_topview ? 'active' : ''; ?>">
                    <input name="ciblog_topview" <?php echo !$ciblog_topview ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_no; ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-topview_sort_order"><?php echo $entry_topview_sort_order; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ciblog_topview_sort_order" value="<?php echo $ciblog_topview_sort_order; ?>" placeholder="<?php echo $entry_topview_sort_order; ?>" id="input-topview_sort_order" class="form-control" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend><?php echo $text_latest; ?></legend>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-ciblog_latest"><?php echo $entry_latest; ?></label>
              <div class="col-sm-3">
                <div class="btn-group" data-toggle="buttons">
                  <label class="btn btn-default <?php echo $ciblog_latest ? 'active' : ''; ?>">
                    <input name="ciblog_latest" <?php echo $ciblog_latest ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_yes; ?>
                  </label>
                  <label class="btn btn-default <?php echo !$ciblog_latest ? 'active' : ''; ?>">
                    <input name="ciblog_latest" <?php echo !$ciblog_latest ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_no; ?>
                  </label>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-latest_sort_order"><?php echo $entry_latest_sort_order; ?></label>
              <div class="col-sm-10">
                <input type="text" name="ciblog_latest_sort_order" value="<?php echo $ciblog_latest_sort_order; ?>" placeholder="<?php echo $entry_latest_sort_order; ?>" id="input-latest_sort_order" class="form-control" />
              </div>
            </div>
          </fieldset>
        </div>
      </div>
    </form>
  </div>
  <script type="text/javascript"><!--
    // Category
    $('input[name=\'category\']').autocomplete({
      'source': function(request, response) {
        $.ajax({
          url: 'index.php?route=extension/ciblog/cicategory/autocomplete&<?php echo $var_octoken; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
          dataType: 'json',
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item['name'],
                value: item['ciblog_category_id']
              }
            }));
          }
        });
      },
      'select': function(item) {
        $('input[name=\'category\']').val('');

        $('#ciblog-category' + item['value']).remove();

        $('#ciblog-category').append('<div id="ciblog-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="ciblog_category[]" value="' + item['value'] + '" /></div>');
      }
    });

    $('#ciblog-category').delegate('.fa-minus-circle', 'click', function() {
      $(this).parent().remove();
    });

    // Author
    $('input[name=\'author\']').autocomplete({
      'source': function(request, response) {
        $.ajax({
          url: 'index.php?route=extension/ciblog/ciauthor/autocomplete&<?php echo $var_octoken; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
          dataType: 'json',
          success: function(json) {
            response($.map(json, function(item) {
              return {
                label: item['name'],
                value: item['ciblog_author_id']
              }
            }));
          }
        });
      },
      'select': function(item) {
        $('input[name=\'author\']').val('');

        $('#ciblog-author' + item['value']).remove();

        $('#ciblog-author').append('<div id="ciblog-author' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="ciblog_author[]" value="' + item['value'] + '" /></div>');
      }
    });

    $('#ciblog-author').delegate('.fa-minus-circle', 'click', function() {
      $(this).parent().remove();
    });

    // Custom
    $('input[name=\'custom\']').autocomplete({
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
        $('input[name=\'custom\']').val('');

        $('#ciblog-custom' + item['value']).remove();

        $('#ciblog-custom').append('<div id="ciblog-custom' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="ciblog_custom[]" value="' + item['value'] + '" /></div>');
      }
    });

    $('#ciblog-custom').delegate('.fa-minus-circle', 'click', function() {
      $(this).parent().remove();
    });
  //--></script>
</div>
<?php echo $footer; ?>