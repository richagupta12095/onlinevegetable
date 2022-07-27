<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-store" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-store" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-cogs"></i> <?php echo $tab_general; ?></a></li>
            <li><a href="#tab-page" data-toggle="tab"><i class="fa fa-info-circle"></i> <?php echo $tab_page; ?></a></li>
            <li><a href="#tab-blog_listing" data-toggle="tab"><i class="fa fa-list-alt"></i> <?php echo $tab_blog_listing; ?></a></li>
            <li><a href="#tab-blog_page" data-toggle="tab"><i class="fa fa-newspaper-o"></i> <?php echo $tab_blog_page; ?></a></li>
            <li><a href="#tab-blog_related" data-toggle="tab"><i class="fa fa-renren"></i> <?php echo $tab_blog_related; ?></a></li>
            <li><a href="#tab-blog_category" data-toggle="tab"><i class="fa fa-link"></i> <?php echo $tab_blog_category; ?></a></li>
            <li><a href="#tab-blog_search" data-toggle="tab"><i class="fa fa-search"></i> <?php echo $tab_blog_search; ?></a></li>
            <li><a href="#tab-blog_author" data-toggle="tab"><i class="fa fa-user"></i> <?php echo $tab_blog_author; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php echo $ciblog_store_status ? 'active' : ''; ?> yes">
                      <input name="ciblog_store_status" <?php echo $ciblog_store_status ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><?php echo $text_enabled; ?>
                    </label>
                    <label class="btn btn-default <?php echo !$ciblog_store_status ? 'active' : ''; ?> no">
                      <input name="ciblog_store_status" <?php echo !$ciblog_store_status ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"> <?php echo $text_disabled; ?>
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_can_like; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php echo $ciblog_store_can_like=='NOONE' ? 'active' : ''; ?>">
                      <input name="ciblog_store_can_like" <?php echo $ciblog_store_can_like=='NOONE' ? 'checked="checked"' : ''; ?> autocomplete="off" value="NOONE" type="radio"><?php echo $text_no_one; ?>
                    </label>
                    <label class="btn btn-default <?php echo $ciblog_store_can_like=='LOGGED' ? 'active' : ''; ?>">
                      <input name="ciblog_store_can_like" <?php echo $ciblog_store_can_like=='LOGGED' ? 'checked="checked"' : ''; ?> autocomplete="off" value="LOGGED" type="radio"> <?php echo $text_only_customer; ?>
                    </label>
                    <label class="btn btn-default <?php echo $ciblog_store_can_like=='BOTH' ? 'active' : ''; ?>">
                      <input name="ciblog_store_can_like" <?php echo $ciblog_store_can_like=='BOTH' ? 'checked="checked"' : ''; ?> autocomplete="off" value="BOTH" type="radio"> <?php echo $text_guest_customer; ?>
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_rating_show; ?></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php echo $ciblog_store_rating_show ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_rating_show" <?php echo $ciblog_store_rating_show ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                    <label class="btn btn-default <?php echo !$ciblog_store_rating_show ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_rating_show" <?php echo !$ciblog_store_rating_show ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_post_count; ?>"><?php echo $entry_post_count; ?></span></label>
                <div class="col-sm-10">
                  <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default <?php echo $ciblog_store_post_count ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_post_count" <?php echo $ciblog_store_post_count ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                    <label class="btn btn-default <?php echo !$ciblog_store_post_count ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_post_count" <?php echo !$ciblog_store_post_count ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-page">
              <fieldset>
                <legend><?php echo $entry_keyword; ?></legend>
                <?php if(VERSION <= '2.3.0.2') { ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="ciblog_store_page_keyword" value="<?php echo $ciblog_store_page_keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                    <?php if ($error_page_keyword) { ?>
                    <div class="text-danger"><?php echo $error_page_keyword; ?></div>
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
                              <input type="text" name="ciblog_store_page_keyword[<?php echo $store['store_id']; ?>][<?php echo $language['language_id']; ?>]" value="<?php if (isset($ciblog_store_page_keyword[$store['store_id']][$language['language_id']])) { ?><?php echo $ciblog_store_page_keyword[$store['store_id']][$language['language_id']]; ?><?php } ?>" placeholder="<?php echo $entry_keyword; ?>" class="form-control" />
                            </div>
                            <?php if (isset($error_page_keyword[$store['store_id']][$language['language_id']])) { ?>
                            <div class="text-danger"><?php echo $error_page_keyword[$store['store_id']][$language['language_id']]; ?></div>
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
              </fieldset>
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $language['flag']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-blog_title<?php echo $language['language_id']; ?>"><?php echo $entry_blog_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="ciblog_store_page_description[<?php echo $language['language_id']; ?>][blog_title]" value="<?php echo isset($ciblog_store_page_description[$language['language_id']]) ? $ciblog_store_page_description[$language['language_id']]['blog_title'] : ''; ?>" placeholder="<?php echo $entry_blog_title; ?>" id="input-blog_title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_page_description[$language['language_id']]['blog_title'])) { ?>
                      <div class="text-danger"><?php echo $error_page_description[$language['language_id']]['blog_title']; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="ciblog_store_page_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote" data-toggle="summernote" data-lang="<?php echo $summernote; ?>"><?php echo isset($ciblog_store_page_description[$language['language_id']]) ? $ciblog_store_page_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="ciblog_store_page_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($ciblog_store_page_description[$language['language_id']]) ? $ciblog_store_page_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_page_description[$language['language_id']]['meta_title'])) { ?>
                      <div class="text-danger"><?php echo $error_page_description[$language['language_id']]['meta_title']; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="ciblog_store_page_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($ciblog_store_page_description[$language['language_id']]) ? $ciblog_store_page_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="ciblog_store_page_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($ciblog_store_page_description[$language['language_id']]) ? $ciblog_store_page_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane" id="tab-blog_listing">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blog-image-listing-width"><?php echo $entry_blog_image_listing; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_w; ?></span><input type="text" name="ciblog_store_blog_image_listing_width" value="<?php echo $ciblog_store_blog_image_listing_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-blog-image-listing-width" class="form-control" /></div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_h; ?></span><input type="text" name="ciblog_store_blog_image_listing_height" value="<?php echo $ciblog_store_blog_image_listing_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" /></div>
                    </div>
                  </div>
                  <?php if ($error_blog_image_listing) { ?>
                  <div class="text-danger"><?php echo $error_blog_image_listing; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label class="col-sm-4 control-label" for="input-blog_limit"><span data-toggle="tooltip" title="<?php echo $help_blog_limit; ?>"><?php echo $entry_blog_limit; ?></span></label>
                    <div class="col-sm-8">
                      <input type="text" name="ciblog_store_blog_limit" value="<?php echo $ciblog_store_blog_limit; ?>" placeholder="<?php echo $entry_blog_limit; ?>" id="input-blog_limit" class="form-control" />
                      <?php if ($error_blog_limit) { ?>
                      <div class="text-danger"><?php echo $error_blog_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label class="col-sm-4 control-label" for="input-blog_row"><?php echo $entry_blog_row; ?></label>
                    <div class="col-sm-8">
                      <select id="input-blog_row" class="form-control" name="ciblog_store_blog_row">
                        <?php foreach ($display_rows as $display_row) { ?>
                        <option value="<?php echo $display_row; ?>" <?php if ($ciblog_store_blog_row == $display_row) { ?>selected="selected"<?php } ?>><?php echo $display_row; ?></option>
                        <?php } ?>
                      </select>
                      <?php if ($error_blog_row) { ?>
                      <div class="text-danger"><?php echo $error_blog_row; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blog_description_length"><?php echo $entry_blog_description_length; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ciblog_store_blog_description_length" value="<?php echo $ciblog_store_blog_description_length; ?>" placeholder="<?php echo $entry_blog_description_length; ?>" id="input-blog_description_length" class="form-control" />
                  <?php if ($error_blog_description_length) { ?>
                  <div class="text-danger"><?php echo $error_blog_description_length; ?></div>
                  <?php } ?>
                </div>
              </div>
              <fieldset>
                <legend><?php echo $text_switch_buttons; ?></legend>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_title; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blog_show_title ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blog_show_title" <?php echo $ciblog_store_blog_show_title ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blog_show_title ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blog_show_title" <?php echo !$ciblog_store_blog_show_title ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_description; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blog_show_description ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blog_show_description" <?php echo $ciblog_store_blog_show_description ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blog_show_description ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blog_show_description" <?php echo !$ciblog_store_blog_show_description ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_image_show_listing; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blog_image_show_listing ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blog_image_show_listing" <?php echo $ciblog_store_blog_image_show_listing ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blog_image_show_listing ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blog_image_show_listing" <?php echo !$ciblog_store_blog_image_show_listing ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_date_publish; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blog_show_date_publish ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blog_show_date_publish" <?php echo $ciblog_store_blog_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blog_show_date_publish ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blog_show_date_publish" <?php echo !$ciblog_store_blog_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_total_view; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blog_show_total_view ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blog_show_total_view" <?php echo $ciblog_store_blog_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blog_show_total_view ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blog_show_total_view" <?php echo !$ciblog_store_blog_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_author; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blog_show_author ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blog_show_author" <?php echo $ciblog_store_blog_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blog_show_author ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blog_show_author" <?php echo !$ciblog_store_blog_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_like_show_total; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blog_like_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blog_like_show_total" <?php echo $ciblog_store_blog_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blog_like_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blog_like_show_total" <?php echo !$ciblog_store_blog_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_show_total; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blog_comment_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blog_comment_show_total" <?php echo $ciblog_store_blog_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blog_comment_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blog_comment_show_total" <?php echo !$ciblog_store_blog_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-blog_page">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blog-image-thumb-width"><?php echo $entry_blog_image_thumb; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_w; ?></span><input type="text" name="ciblog_store_blog_image_thumb_width" value="<?php echo $ciblog_store_blog_image_thumb_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-blog-image-thumb-width" class="form-control" /></div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_h; ?></span><input type="text" name="ciblog_store_blog_image_thumb_height" value="<?php echo $ciblog_store_blog_image_thumb_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" /></div>
                    </div>
                  </div>
                  <?php if ($error_blog_image_thumb) { ?>
                  <div class="text-danger"><?php echo $error_blog_image_thumb; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blog-image-popup-width"><?php echo $entry_blog_image_popup; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_w; ?></span><input type="text" name="ciblog_store_blog_image_popup_width" value="<?php echo $ciblog_store_blog_image_popup_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-blog-image-popup-width" class="form-control" /></div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_h; ?></span><input type="text" name="ciblog_store_blog_image_popup_height" value="<?php echo $ciblog_store_blog_image_popup_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" /></div>
                    </div>
                  </div>
                  <?php if ($error_blog_image_popup) { ?>
                  <div class="text-danger"><?php echo $error_blog_image_popup; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blog-image-additioanl-width"><?php echo $entry_blog_image_additional; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_w; ?></span><input type="text" name="ciblog_store_blog_image_additional_width" value="<?php echo $ciblog_store_blog_image_additional_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-blog-image-additioanl-width" class="form-control" /></div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_h; ?></span><input type="text" name="ciblog_store_blog_image_additional_height" value="<?php echo $ciblog_store_blog_image_additional_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" /></div>
                    </div>
                  </div>
                  <?php if ($error_blog_image_additional) { ?>
                  <div class="text-danger"><?php echo $error_blog_image_additional; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_image_show_thumb; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blog_image_show_thumb ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blog_image_show_thumb" <?php echo $ciblog_store_blog_image_show_thumb ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blog_image_show_thumb ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blog_image_show_thumb" <?php echo !$ciblog_store_blog_image_show_thumb ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_show_date_publish; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_show_date_publish ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_show_date_publish" <?php echo $ciblog_store_blogpage_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_show_date_publish ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_show_date_publish" <?php echo !$ciblog_store_blogpage_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_show_total_view; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_show_total_view ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_show_total_view" <?php echo $ciblog_store_blogpage_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_show_total_view ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_show_total_view" <?php echo !$ciblog_store_blogpage_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_show_author; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_show_author ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_show_author" <?php echo $ciblog_store_blogpage_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_show_author ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_show_author" <?php echo !$ciblog_store_blogpage_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_like_show_total; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_like_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_like_show_total" <?php echo $ciblog_store_blogpage_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_like_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_like_show_total" <?php echo !$ciblog_store_blogpage_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_like_allow; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_like_allow ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_like_allow" <?php echo $ciblog_store_blogpage_like_allow ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_like_allow ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_like_allow" <?php echo !$ciblog_store_blogpage_like_allow ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_show_social_share; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_show_social_share ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_show_social_share" <?php echo $ciblog_store_blogpage_show_social_share ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_show_social_share ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_show_social_share" <?php echo !$ciblog_store_blogpage_show_social_share ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_allow; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_comment_allow ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_comment_allow" <?php echo $ciblog_store_blogpage_comment_allow ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_comment_allow ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_comment_allow" <?php echo !$ciblog_store_blogpage_comment_allow ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_allow_guest; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_comment_allow_guest ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_comment_allow_guest" <?php echo $ciblog_store_blogpage_comment_allow_guest ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_comment_allow_guest ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_comment_allow_guest" <?php echo !$ciblog_store_blogpage_comment_allow_guest ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_show_total; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_comment_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_comment_show_total" <?php echo $ciblog_store_blogpage_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_comment_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_comment_show_total" <?php echo !$ciblog_store_blogpage_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_show; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_comment_show ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_comment_show" <?php echo $ciblog_store_blogpage_comment_show ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_comment_show ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_comment_show" <?php echo !$ciblog_store_blogpage_comment_show ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_snippet; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_comment_snippet ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_comment_snippet" <?php echo $ciblog_store_blogpage_comment_snippet ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_comment_snippet ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_comment_snippet" <?php echo !$ciblog_store_blogpage_comment_snippet ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label class="col-sm-4 control-label" for="input-blogpage_comment_limit"><span data-toggle="tooltip" title="<?php echo $help_blog_comment_limit; ?>"><?php echo $entry_blog_comment_limit; ?></span></label>
                    <div class="col-sm-8">
                      <input type="text" name="ciblog_store_blogpage_comment_limit" value="<?php echo $ciblog_store_blogpage_comment_limit; ?>" placeholder="<?php echo $entry_blog_comment_limit; ?>" id="input-blogpage_comment_limit" class="form-control" />
                      <?php if ($error_blogpage_comment_limit) { ?>
                      <div class="text-danger"><?php echo $error_blogpage_comment_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_captcha; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_comment_captcha ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_comment_captcha" <?php echo $ciblog_store_blogpage_comment_captcha ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_comment_captcha ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_comment_captcha" <?php echo !$ciblog_store_blogpage_comment_captcha ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                      <?php if ($error_config_captcha) { ?>
                      <div class="text-danger"><?php echo $error_config_captcha; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_approve; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_comment_approve == 'NO' ? 'active' : ''; ?>">
                          <input name="ciblog_store_blogpage_comment_approve" <?php echo $ciblog_store_blogpage_comment_approve == 'NO' ? 'checked="checked"' : ''; ?> autocomplete="off" value="NO" type="radio"><?php echo $text_no; ?>
                        </label>
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_comment_approve == 'LOGGED' ? 'active' : ''; ?>">
                          <input name="ciblog_store_blogpage_comment_approve" <?php echo $ciblog_store_blogpage_comment_approve == 'LOGGED' ? 'checked="checked"' : ''; ?> autocomplete="off" value="LOGGED" type="radio"> <?php echo $text_only_customer; ?>
                        </label>
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_comment_approve == 'BOTH' ? 'active' : ''; ?>">
                          <input name="ciblog_store_blogpage_comment_approve" <?php echo $ciblog_store_blogpage_comment_approve == 'BOTH' ? 'checked="checked"' : ''; ?> autocomplete="off" value="BOTH" type="radio"> <?php echo $text_guest_customer; ?>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_alert; ?></label>
                    <div class="col-sm-8">
                      <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default <?php echo $ciblog_store_blogpage_comment_alert ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogpage_comment_alert" <?php echo $ciblog_store_blogpage_comment_alert ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                        <label class="btn btn-default <?php echo !$ciblog_store_blogpage_comment_alert ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogpage_comment_alert" <?php echo !$ciblog_store_blogpage_comment_alert ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-blog_related">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blogrelated-image-listing-width"><?php echo $entry_blog_image_listing; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_w; ?></span><input type="text" name="ciblog_store_blogrelated_image_listing_width" value="<?php echo $ciblog_store_blogrelated_image_listing_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-blogrelated-image-listing-width" class="form-control" /></div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_h; ?></span><input type="text" name="ciblog_store_blogrelated_image_listing_height" value="<?php echo $ciblog_store_blogrelated_image_listing_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" /></div>
                    </div>
                  </div>
                  <?php if ($error_blogrelated_image_listing) { ?>
                  <div class="text-danger"><?php echo $error_blogrelated_image_listing; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blogrelated_row"><?php echo $entry_blog_row; ?></label>
                <div class="col-sm-10">
                  <select id="input-blogrelated_row" class="form-control" name="ciblog_store_blogrelated_row">
                    <?php foreach ($display_rows as $display_row) { ?>
                    <option value="<?php echo $display_row; ?>" <?php if ($ciblog_store_blogrelated_row == $display_row) { ?>selected="selected"<?php } ?>><?php echo $display_row; ?></option>
                    <?php } ?>
                  </select>
                  <?php if ($error_blogrelated_row) { ?>
                  <div class="text-danger"><?php echo $error_blogrelated_row; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blogrelated_description_length"><?php echo $entry_blog_description_length; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ciblog_store_blogrelated_description_length" value="<?php echo $ciblog_store_blogrelated_description_length; ?>" placeholder="<?php echo $entry_blog_description_length; ?>" id="input-blogrelated_description_length" class="form-control" />
                  <?php if ($error_blogrelated_description_length) { ?>
                  <div class="text-danger"><?php echo $error_blogrelated_description_length; ?></div>
                  <?php } ?>
                </div>
              </div>
              <fieldset>
                <legend><?php echo $text_switch_buttons; ?></legend>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_title; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogrelated_show_title ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogrelated_show_title" <?php echo $ciblog_store_blogrelated_show_title ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogrelated_show_title ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogrelated_show_title" <?php echo !$ciblog_store_blogrelated_show_title ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_description; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogrelated_show_description ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogrelated_show_description" <?php echo $ciblog_store_blogrelated_show_description ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogrelated_show_description ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogrelated_show_description" <?php echo !$ciblog_store_blogrelated_show_description ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_image_show_listing; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogrelated_image_show_listing ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogrelated_image_show_listing" <?php echo $ciblog_store_blogrelated_image_show_listing ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogrelated_image_show_listing ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogrelated_image_show_listing" <?php echo !$ciblog_store_blogrelated_image_show_listing ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_date_publish; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogrelated_show_date_publish ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogrelated_show_date_publish" <?php echo $ciblog_store_blogrelated_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogrelated_show_date_publish ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogrelated_show_date_publish" <?php echo !$ciblog_store_blogrelated_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_total_view; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogrelated_show_total_view ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogrelated_show_total_view" <?php echo $ciblog_store_blogrelated_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogrelated_show_total_view ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogrelated_show_total_view" <?php echo !$ciblog_store_blogrelated_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_author; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogrelated_show_author ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogrelated_show_author" <?php echo $ciblog_store_blogrelated_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogrelated_show_author ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogrelated_show_author" <?php echo !$ciblog_store_blogrelated_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_like_show_total; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogrelated_like_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogrelated_like_show_total" <?php echo $ciblog_store_blogrelated_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogrelated_like_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogrelated_like_show_total" <?php echo !$ciblog_store_blogrelated_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_show_total; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogrelated_comment_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogrelated_comment_show_total" <?php echo $ciblog_store_blogrelated_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogrelated_comment_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogrelated_comment_show_total" <?php echo !$ciblog_store_blogrelated_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-blog_category">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blogcategory-image-listing-width"><?php echo $entry_blog_image_listing; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_w; ?></span><input type="text" name="ciblog_store_blogcategory_image_listing_width" value="<?php echo $ciblog_store_blogcategory_image_listing_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-blogcategory-image-listing-width" class="form-control" /></div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_h; ?></span><input type="text" name="ciblog_store_blogcategory_image_listing_height" value="<?php echo $ciblog_store_blogcategory_image_listing_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" /></div>
                    </div>
                  </div>
                  <?php if ($error_blogcategory_image_listing) { ?>
                  <div class="text-danger"><?php echo $error_blogcategory_image_listing; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label class="col-sm-4 control-label" for="input-blogcategory_limit"><span data-toggle="tooltip" title="<?php echo $help_blog_limit; ?>"><?php echo $entry_blog_limit; ?></span></label>
                    <div class="col-sm-8">
                      <input type="text" name="ciblog_store_blogcategory_limit" value="<?php echo $ciblog_store_blogcategory_limit; ?>" placeholder="<?php echo $entry_blog_limit; ?>" id="input-blogcategory_limit" class="form-control" />
                      <?php if ($error_blogcategory_limit) { ?>
                      <div class="text-danger"><?php echo $error_blogcategory_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label class="col-sm-4 control-label" for="input-blogcategory_row"><?php echo $entry_blog_row; ?></label>
                    <div class="col-sm-8">
                      <select id="input-blogcategory_row" class="form-control" name="ciblog_store_blogcategory_row">
                        <?php foreach ($display_rows as $display_row) { ?>
                        <option value="<?php echo $display_row; ?>" <?php if ($ciblog_store_blogcategory_row == $display_row) { ?>selected="selected"<?php } ?>><?php echo $display_row; ?></option>
                        <?php } ?>
                      </select>
                      <?php if ($error_blogcategory_row) { ?>
                      <div class="text-danger"><?php echo $error_blogcategory_row; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blogcategory_description_length"><?php echo $entry_blog_description_length; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ciblog_store_blogcategory_description_length" value="<?php echo $ciblog_store_blogcategory_description_length; ?>" placeholder="<?php echo $entry_blog_description_length; ?>" id="input-blogcategory_description_length" class="form-control" />
                  <?php if ($error_blogcategory_description_length) { ?>
                  <div class="text-danger"><?php echo $error_blogcategory_description_length; ?></div>
                  <?php } ?>
                </div>
              </div>
              <fieldset>
                <legend><?php echo $text_switch_buttons; ?></legend>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_title; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogcategory_show_title ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogcategory_show_title" <?php echo $ciblog_store_blogcategory_show_title ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogcategory_show_title ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogcategory_show_title" <?php echo !$ciblog_store_blogcategory_show_title ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_description; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogcategory_show_description ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogcategory_show_description" <?php echo $ciblog_store_blogcategory_show_description ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogcategory_show_description ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogcategory_show_description" <?php echo !$ciblog_store_blogcategory_show_description ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_image_show_listing; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogcategory_image_show_listing ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogcategory_image_show_listing" <?php echo $ciblog_store_blogcategory_image_show_listing ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogcategory_image_show_listing ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogcategory_image_show_listing" <?php echo !$ciblog_store_blogcategory_image_show_listing ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_date_publish; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogcategory_show_date_publish ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogcategory_show_date_publish" <?php echo $ciblog_store_blogcategory_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogcategory_show_date_publish ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogcategory_show_date_publish" <?php echo !$ciblog_store_blogcategory_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_total_view; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogcategory_show_total_view ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogcategory_show_total_view" <?php echo $ciblog_store_blogcategory_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogcategory_show_total_view ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogcategory_show_total_view" <?php echo !$ciblog_store_blogcategory_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_author; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogcategory_show_author ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogcategory_show_author" <?php echo $ciblog_store_blogcategory_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogcategory_show_author ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogcategory_show_author" <?php echo !$ciblog_store_blogcategory_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_like_show_total; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogcategory_like_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogcategory_like_show_total" <?php echo $ciblog_store_blogcategory_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogcategory_like_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogcategory_like_show_total" <?php echo !$ciblog_store_blogcategory_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_show_total; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogcategory_comment_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogcategory_comment_show_total" <?php echo $ciblog_store_blogcategory_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogcategory_comment_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogcategory_comment_show_total" <?php echo !$ciblog_store_blogcategory_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-blog_search">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blogsearch-image-listing-width"><?php echo $entry_blog_image_listing; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_w; ?></span><input type="text" name="ciblog_store_blogsearch_image_listing_width" value="<?php echo $ciblog_store_blogsearch_image_listing_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-blogsearch-image-listing-width" class="form-control" /></div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_h; ?></span><input type="text" name="ciblog_store_blogsearch_image_listing_height" value="<?php echo $ciblog_store_blogsearch_image_listing_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" /></div>
                    </div>
                  </div>
                  <?php if ($error_blogsearch_image_listing) { ?>
                  <div class="text-danger"><?php echo $error_blogsearch_image_listing; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label class="col-sm-4 control-label" for="input-blogsearch_limit"><span data-toggle="tooltip" title="<?php echo $help_blog_limit; ?>"><?php echo $entry_blog_limit; ?></span></label>
                    <div class="col-sm-8">
                      <input type="text" name="ciblog_store_blogsearch_limit" value="<?php echo $ciblog_store_blogsearch_limit; ?>" placeholder="<?php echo $entry_blog_limit; ?>" id="input-blogsearch_limit" class="form-control" />
                      <?php if ($error_blogsearch_limit) { ?>
                      <div class="text-danger"><?php echo $error_blogsearch_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label class="col-sm-4 control-label" for="input-blogsearch_row"><?php echo $entry_blog_row; ?></label>
                    <div class="col-sm-8">
                      <select id="input-blogsearch_row" class="form-control" name="ciblog_store_blogsearch_row">
                        <?php foreach ($display_rows as $display_row) { ?>
                        <option value="<?php echo $display_row; ?>" <?php if ($ciblog_store_blogsearch_row == $display_row) { ?>selected="selected"<?php } ?>><?php echo $display_row; ?></option>
                        <?php } ?>
                      </select>
                      <?php if ($error_blogsearch_row) { ?>
                      <div class="text-danger"><?php echo $error_blogsearch_row; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blogsearch_description_length"><?php echo $entry_blog_description_length; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ciblog_store_blogsearch_description_length" value="<?php echo $ciblog_store_blogsearch_description_length; ?>" placeholder="<?php echo $entry_blog_description_length; ?>" id="input-blogsearch_description_length" class="form-control" />
                  <?php if ($error_blogsearch_description_length) { ?>
                  <div class="text-danger"><?php echo $error_blogsearch_description_length; ?></div>
                  <?php } ?>
                </div>
              </div>
              <fieldset>
                <legend><?php echo $text_switch_buttons; ?></legend>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_title; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogsearch_show_title ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogsearch_show_title" <?php echo $ciblog_store_blogsearch_show_title ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogsearch_show_title ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogsearch_show_title" <?php echo !$ciblog_store_blogsearch_show_title ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_description; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogsearch_show_description ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogsearch_show_description" <?php echo $ciblog_store_blogsearch_show_description ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogsearch_show_description ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogsearch_show_description" <?php echo !$ciblog_store_blogsearch_show_description ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_image_show_listing; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogsearch_image_show_listing ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogsearch_image_show_listing" <?php echo $ciblog_store_blogsearch_image_show_listing ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogsearch_image_show_listing ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogsearch_image_show_listing" <?php echo !$ciblog_store_blogsearch_image_show_listing ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_date_publish; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogsearch_show_date_publish ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogsearch_show_date_publish" <?php echo $ciblog_store_blogsearch_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogsearch_show_date_publish ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogsearch_show_date_publish" <?php echo !$ciblog_store_blogsearch_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_total_view; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogsearch_show_total_view ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogsearch_show_total_view" <?php echo $ciblog_store_blogsearch_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogsearch_show_total_view ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogsearch_show_total_view" <?php echo !$ciblog_store_blogsearch_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_author; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogsearch_show_author ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogsearch_show_author" <?php echo $ciblog_store_blogsearch_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogsearch_show_author ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogsearch_show_author" <?php echo !$ciblog_store_blogsearch_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_like_show_total; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogsearch_like_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogsearch_like_show_total" <?php echo $ciblog_store_blogsearch_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogsearch_like_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogsearch_like_show_total" <?php echo !$ciblog_store_blogsearch_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_show_total; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogsearch_comment_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogsearch_comment_show_total" <?php echo $ciblog_store_blogsearch_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogsearch_comment_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogsearch_comment_show_total" <?php echo !$ciblog_store_blogsearch_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-blog_author">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blogauthor-image-listing-width"><?php echo $entry_blog_image_listing; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_w; ?></span><input type="text" name="ciblog_store_blogauthor_image_listing_width" value="<?php echo $ciblog_store_blogauthor_image_listing_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-blogauthor-image-listing-width" class="form-control" /></div>
                    </div>
                    <div class="col-sm-6">
                      <div class="input-group"><span class="input-group-addon"><?php echo $text_h; ?></span><input type="text" name="ciblog_store_blogauthor_image_listing_height" value="<?php echo $ciblog_store_blogauthor_image_listing_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" /></div>
                    </div>
                  </div>
                  <?php if ($error_blogauthor_image_listing) { ?>
                  <div class="text-danger"><?php echo $error_blogauthor_image_listing; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group required">
                    <label class="col-sm-4 control-label" for="input-blogauthor_limit"><span data-toggle="tooltip" title="<?php echo $help_blog_limit; ?>"><?php echo $entry_blog_limit; ?></span></label>
                    <div class="col-sm-8">
                      <input type="text" name="ciblog_store_blogauthor_limit" value="<?php echo $ciblog_store_blogauthor_limit; ?>" placeholder="<?php echo $entry_blog_limit; ?>" id="input-blogauthor_limit" class="form-control" />
                      <?php if ($error_blogauthor_limit) { ?>
                      <div class="text-danger"><?php echo $error_blogauthor_limit; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                   <div class="form-group required">
                    <label class="col-sm-4 control-label" for="input-blogauthor_row"><?php echo $entry_blog_row; ?></label>
                    <div class="col-sm-8">
                      <select id="input-blogauthor_row" class="form-control" name="ciblog_store_blogauthor_row">
                        <?php foreach ($display_rows as $display_row) { ?>
                        <option value="<?php echo $display_row; ?>" <?php if ($ciblog_store_blogauthor_row == $display_row) { ?>selected="selected"<?php } ?>><?php echo $display_row; ?></option>
                        <?php } ?>
                      </select>
                      <?php if ($error_blogauthor_row) { ?>
                      <div class="text-danger"><?php echo $error_blogauthor_row; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-blogauthor_description_length"><?php echo $entry_blog_description_length; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="ciblog_store_blogauthor_description_length" value="<?php echo $ciblog_store_blogauthor_description_length; ?>" placeholder="<?php echo $entry_blog_description_length; ?>" id="input-blogauthor_description_length" class="form-control" />
                  <?php if ($error_blogauthor_description_length) { ?>
                  <div class="text-danger"><?php echo $error_blogauthor_description_length; ?></div>
                  <?php } ?>
                </div>
              </div>
              <fieldset>
                <legend><?php echo $text_switch_buttons; ?></legend>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_title; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogauthor_show_title ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogauthor_show_title" <?php echo $ciblog_store_blogauthor_show_title ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogauthor_show_title ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogauthor_show_title" <?php echo !$ciblog_store_blogauthor_show_title ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_description; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogauthor_show_description ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogauthor_show_description" <?php echo $ciblog_store_blogauthor_show_description ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogauthor_show_description ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogauthor_show_description" <?php echo !$ciblog_store_blogauthor_show_description ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_image_show_listing; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogauthor_image_show_listing ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogauthor_image_show_listing" <?php echo $ciblog_store_blogauthor_image_show_listing ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogauthor_image_show_listing ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogauthor_image_show_listing" <?php echo !$ciblog_store_blogauthor_image_show_listing ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_date_publish; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogauthor_show_date_publish ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogauthor_show_date_publish" <?php echo $ciblog_store_blogauthor_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogauthor_show_date_publish ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogauthor_show_date_publish" <?php echo !$ciblog_store_blogauthor_show_date_publish ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_total_view; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogauthor_show_total_view ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogauthor_show_total_view" <?php echo $ciblog_store_blogauthor_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogauthor_show_total_view ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogauthor_show_total_view" <?php echo !$ciblog_store_blogauthor_show_total_view ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_show_author; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogauthor_show_author ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogauthor_show_author" <?php echo $ciblog_store_blogauthor_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogauthor_show_author ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogauthor_show_author" <?php echo !$ciblog_store_blogauthor_show_author ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_like_show_total; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogauthor_like_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogauthor_like_show_total" <?php echo $ciblog_store_blogauthor_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogauthor_like_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogauthor_like_show_total" <?php echo !$ciblog_store_blogauthor_like_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="col-sm-4 control-label"><?php echo $entry_blog_comment_show_total; ?></label>
                      <div class="col-sm-8">
                        <div class="btn-group" data-toggle="buttons">
                          <label class="btn btn-default <?php echo $ciblog_store_blogauthor_comment_show_total ? 'active' : ''; ?> yes" data-toggle="tooltip" title="<?php echo $text_yes; ?>"><input name="ciblog_store_blogauthor_comment_show_total" <?php echo $ciblog_store_blogauthor_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="1" type="radio"><i class="fa fa-check"></i></label>
                          <label class="btn btn-default <?php echo !$ciblog_store_blogauthor_comment_show_total ? 'active' : ''; ?> no" data-toggle="tooltip" title="<?php echo $text_no; ?>"><input name="ciblog_store_blogauthor_comment_show_total" <?php echo !$ciblog_store_blogauthor_comment_show_total ? 'checked="checked"' : ''; ?> autocomplete="off" value="0" type="radio"><i class="fa fa-close"></i></label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6">
          </div>
          <div class="col-sm-6">
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php echo $text_editor; ?>
<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>