<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="ci-content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ciblog_post" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ciblog_post" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
            <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
            <li><a href="#tab-comment" data-toggle="tab"><?php echo $tab_comment; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="ciblog_post_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($ciblog_post_description[$language['language_id']]) ? $ciblog_post_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-small_description<?php echo $language['language_id']; ?>"><?php echo $entry_small_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="ciblog_post_description[<?php echo $language['language_id']; ?>][small_description]" placeholder="<?php echo $entry_small_description; ?>" id="input-small_description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($ciblog_post_description[$language['language_id']]) ? $ciblog_post_description[$language['language_id']]['small_description'] : ''; ?></textarea>

                       <?php if (isset($error_small_description[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_small_description[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="ciblog_post_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote" data-toggle="summernote" data-lang="<?php echo $summernote; ?>"><?php echo isset($ciblog_post_description[$language['language_id']]) ? $ciblog_post_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="ciblog_post_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($ciblog_post_description[$language['language_id']]) ? $ciblog_post_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="ciblog_post_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($ciblog_post_description[$language['language_id']]) ? $ciblog_post_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="ciblog_post_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($ciblog_post_description[$language['language_id']]) ? $ciblog_post_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-tag<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_tag; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="ciblog_post_description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo isset($ciblog_post_description[$language['language_id']]) ? $ciblog_post_description[$language['language_id']]['tag'] : ''; ?>" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" class="form-control" />
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-add_video_url"><?php echo $entry_add_video_url; ?></label>
                <div class="col-sm-10">
                  <select name="add_video_url" id="input-add_video_url" class="form-control">
                    <?php if ($add_video_url) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-video_url"><span data-toggle="tooltip" title="<?php echo $help_video_url; ?>"><?php echo $entry_video_url; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="video_url" value="<?php echo $video_url; ?>" placeholder="<?php echo $entry_video_url; ?>" id="input-video_url" class="form-control" />
                  <?php if ($error_video_url) { ?>
                  <div class="text-danger"><?php echo $error_video_url; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php if(VERSION <= '2.3.0.2') { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                  <?php if ($error_keyword) { ?>
                  <div class="text-danger"><?php echo $error_keyword; ?></div>
                  <?php } ?>
                </div>
              </div>
              <?php } else { ?>
              <div class="form-group">
                <div class="col-sm-12">
                  <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_keyword; ?></div>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <td class="text-left"><?php echo $entry_store; ?></td>
                          <td class="text-left"><?php echo $entry_keyword; ?></td>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($stores as $store) { ?>
                      <tr>
                        <td class="text-left"><?php echo $store['name']; ?></td>
                        <td class="text-left"><?php foreach ($languages as $language) { ?>
                          <div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></span>
                            <input type="text" name="keyword[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" value="<?php if (isset($keyword[$store['store_id']][$language['language_id']])) { ?><?php echo $keyword[$store['store_id']][$language['language_id']]; ?><?php } ?>" placeholder="<?php echo $entry_keyword; ?>" class="form-control" />
                          </div>
                          <?php if (isset($error_keyword[$store['store_id']][$language['language_id']])) { ?>
                          <div class="text-danger"><?php echo $error_keyword[$store['store_id']][$language['language_id']]; ?></div>
                          <?php } ?>
                          <?php } ?></td>
                      </tr>
                      <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <?php } ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-availale"><?php echo $entry_date_available; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="date_available" value="<?php echo $date_available; ?>" placeholder="<?php echo $entry_date_available; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="col-sm-3">
                  <div class="input-group datetime">
                    <input type="text" name="date_added" value="<?php echo $date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD HH:mm:ss" id="input-date-added" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                <div class="col-sm-3">
                  <div class="input-group datetime">
                    <input type="text" name="date_modified" value="<?php echo $date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD HH:mm:ss" id="input-date-modified" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-allow_comment"><?php echo $entry_comment; ?></label>
                <div class="col-sm-10">
                  <select name="allow_comment" id="input-allow_comment" class="form-control">
                    <?php if ($allow_comment) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select>
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
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-links">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-ciblog_author"><span data-toggle="tooltip" title="<?php echo $help_author; ?>"><?php echo $entry_author; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="ciblog_author" value="<?php echo $ciblog_author; ?>" placeholder="<?php echo $entry_author; ?>" id="input-ciblog_author" class="form-control" />
                  <input type="hidden" name="ciblog_author_id" value="<?php echo $ciblog_author_id; ?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-cicategory"><span data-toggle="tooltip" title="<?php echo $help_cicategory; ?>"><?php echo $entry_cicategory; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="cicategory" value="" placeholder="<?php echo $entry_cicategory; ?>" id="input-cicategory" class="form-control" />
                  <div id="ciblog_post-cicategory" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($ciblog_post_categories as $ciblog_post_category) { ?>
                    <div id="ciblog_post-cicategory<?php echo $ciblog_post_category['ciblog_category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $ciblog_post_category['name']; ?>
                      <input type="hidden" name="ciblog_post_category[]" value="<?php echo $ciblog_post_category['ciblog_category_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $ciblog_post_store)) { ?>
                        <input type="checkbox" name="ciblog_post_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="ciblog_post_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-related"><span data-toggle="tooltip" title="<?php echo $help_related; ?>"><?php echo $entry_related; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" class="form-control" />
                  <div id="ciblog_post-related" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($ciblog_post_relateds as $ciblog_post_related) { ?>
                    <div id="ciblog_post-related<?php echo $ciblog_post_related['ciblog_post_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $ciblog_post_related['name']; ?>
                      <input type="hidden" name="ciblog_post_related[]" value="<?php echo $ciblog_post_related['ciblog_post_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-related_product"><span data-toggle="tooltip" title="<?php echo $help_related_product; ?>"><?php echo $entry_related_product; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="related_product" value="" placeholder="<?php echo $entry_related_product; ?>" id="input-related_product" class="form-control" />
                  <div id="ciblog_post-related_product" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($ciblog_post_related_products as $ciblog_post_related_product) { ?>
                    <div id="ciblog_post-related_product<?php echo $ciblog_post_related_product['ciblog_post_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $ciblog_post_related_product['name']; ?>
                      <input type="hidden" name="ciblog_post_related_product[]" value="<?php echo $ciblog_post_related_product['ciblog_post_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-image">
              <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_image; ?></td>
                      <td class="text-left"><?php echo $entry_alt; ?></td>
                      <td class="text-left"><?php echo $entry_title; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" /></td>
                      <td class="text-left">
                        <?php foreach ($languages as $language) { ?>
                        <div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="ciblog_post_description[<?php echo $language['language_id']; ?>][image_alt]" value="<?php echo isset($ciblog_post_description[$language['language_id']]) ? $ciblog_post_description[$language['language_id']]['image_alt'] : ''; ?>" placeholder="<?php echo $entry_alt; ?>" class="form-control" /></div>
                        <?php } ?>
                      </td>

                      <td class="text-left">
                        <?php foreach ($languages as $language) { ?>
                        <div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="ciblog_post_description[<?php echo $language['language_id']; ?>][image_title]" value="<?php echo isset($ciblog_post_description[$language['language_id']]) ? $ciblog_post_description[$language['language_id']]['image_title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" /></div>
                        <?php } ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="table-responsive">
                <table id="images" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_additional_image; ?></td>
                      <td class="text-left"><?php echo $entry_alt; ?></td>
                      <td class="text-left"><?php echo $entry_title; ?></td>
                      <td class="text-left"><?php echo $entry_sort_order; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $image_row = 0; ?>
                    <?php foreach ($ciblog_post_images as $ciblog_post_image) { ?>
                    <tr id="image-row<?php echo $image_row; ?>">
                      <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $ciblog_post_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="ciblog_post_image[<?php echo $image_row; ?>][image]" value="<?php echo $ciblog_post_image['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                      <td class="text-left">
                        <?php foreach ($languages as $language) { ?>
                        <div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="ciblog_post_image[<?php echo $image_row; ?>][info][<?php echo $language['language_id']; ?>][alt]" value="<?php echo isset($ciblog_post_image['info'][$language['language_id']]) ? $ciblog_post_image['info'][$language['language_id']]['alt'] : ''; ?>" placeholder="<?php echo $entry_alt; ?>" class="form-control" /></div>
                        <?php } ?>
                      </td>
                      <td class="text-left">
                        <?php foreach ($languages as $language) { ?>
                        <div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="ciblog_post_image[<?php echo $image_row; ?>][info][<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($ciblog_post_image['info'][$language['language_id']]) ? $ciblog_post_image['info'][$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" /></div>
                        <?php } ?>
                      </td>
                      <td class="text-left"><input type="text" name="ciblog_post_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $ciblog_post_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                      <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $image_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="4"></td>
                      <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $button_image_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-design">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_store; ?></td>
                      <td class="text-left"><?php echo $entry_layout; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($stores as $store) { ?>
                    <tr>
                      <td class="text-left"><?php echo $store['name']; ?></td>
                      <td class="text-left"><select name="ciblog_post_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($ciblog_post_layout[$store['store_id']]) && $ciblog_post_layout[$store['store_id']] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="tab-comment">
               <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $column_comment_email; ?></td>
                      <td class="text-left"><?php echo $column_comment_author; ?></td>
                      <td class="text-left"><?php echo $column_comment_text; ?></td>
                      <td class="text-left"><?php echo $column_comment_status; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($ciblog_post_comments as $ciblog_post_comment) { ?>
                    <tr>
                    <td class="text-left"><?php echo $ciblog_post_comment['email']; ?></td>
                    <td class="text-left"><?php echo $ciblog_post_comment['author']; ?></td>
                    <td class="text-left"><?php echo $ciblog_post_comment['text']; ?></td>
                    <td class="text-left"><label class="label label-<?php echo $ciblog_post_comment['status'] ? 'success' : 'danger'; ?>"><?php echo $ciblog_post_comment['status1']; ?></label></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php echo $text_editor; ?>
  <script type="text/javascript"><!--
// Author
$('input[name=\'ciblog_author\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=extension/ciblog/ciauthor/autocomplete&<?php echo $var_octoken; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        json.unshift({
          ciblog_author_id: 0,
          name: '<?php echo $text_none; ?>'
        });

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
    $('input[name=\'ciblog_author\']').val(item['label']);
    $('input[name=\'ciblog_author_id\']').val(item['value']);
  }
});
// Category
$('input[name=\'cicategory\']').autocomplete({
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
		$('input[name=\'cicategory\']').val('');

		$('#ciblog_post-cicategory' + item['value']).remove();

		$('#ciblog_post-cicategory').append('<div id="ciblog_post-cicategory' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="ciblog_post_category[]" value="' + item['value'] + '" /></div>');
	}
});

$('#ciblog_post-cicategory').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

// Related
$('input[name=\'related\']').autocomplete({
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
    $('input[name=\'related\']').val('');

    $('#ciblog_post-related' + item['value']).remove();

    $('#ciblog_post-related').append('<div id="ciblog_post-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="ciblog_post_related[]" value="' + item['value'] + '" /></div>');
  }
});

$('#ciblog_post-related').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

// Related Product
$('input[name=\'related_product\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&<?php echo $var_octoken; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'related_product\']').val('');

		$('#ciblog_post-related_product' + item['value']).remove();

		$('#ciblog_post-related_product').append('<div id="ciblog_post-related_product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="ciblog_post_related_product[]" value="' + item['value'] + '" /></div>');
	}
});

$('#ciblog_post-related_product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});
//--></script>

  <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
	html  = '<tr id="image-row' + image_row + '">';
	html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="ciblog_post_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
  html += '  <td class="text-left"><?php foreach ($languages as $language) { ?><div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="ciblog_post_image[' + image_row + '][info][<?php echo $language['language_id']; ?>][alt]" value="" placeholder="<?php echo $entry_alt; ?>" class="form-control" /></div><?php } ?></td>';
  html += '  <td class="text-left"><?php foreach ($languages as $language) { ?><div class="input-group"><span class="input-group-addon"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /></span><input type="text" name="ciblog_post_image[' + image_row + '][info][<?php echo $language['language_id']; ?>][title]" value="" placeholder="<?php echo $entry_title; ?>" class="form-control" /></div><?php } ?></td>';
	html += '  <td class="text-left"><input type="text" name="ciblog_post_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#images tbody').append(html);

	image_row++;
}
//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>