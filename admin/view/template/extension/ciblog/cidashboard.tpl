<?php echo $header; ?><?php echo $column_left; ?>
<div id="content" class="ci-content">
  <div class="page-header">
    <div class="container-fluid">
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
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="tile">
          <div class="tile-body"><i class="fa fa-rss"></i>
            <h2 class="pull-right"><?php echo $ciblog_total; ?></h2>
          </div>
          <div class="tile-heading"><?php echo $text_total_blogs; ?> </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="tile">
          <div class="tile-body"><i class="fa fa-link"></i>
            <h2 class="pull-right"><?php echo $cicategory_total; ?></h2>
          </div>
          <div class="tile-heading"><?php echo $text_total_categories; ?> </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="tile">
          <div class="tile-body"><i class="fa fa-comment"></i>
            <h2 class="pull-right"><?php echo $cicomment_total; ?></h2>
          </div>
          <div class="tile-heading"><?php echo $text_total_comments; ?> </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="tile">
          <div class="tile-body"><i class="fa fa-users"></i>
            <h2 class="pull-right"><?php echo $cisubscriber_total; ?></h2>
          </div>
          <div class="tile-heading"><?php echo $text_total_subscribers; ?> </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-comment"></i> <?php echo $text_recent_comments; ?></h3>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <td style="width: 50px;" class="text-center"><?php echo $column_number; ?></td>
                  <td><?php echo $column_blog; ?></td>
                  <td><?php echo $column_author; ?></td>
                  <td><?php echo $column_status; ?></td>
                  <td><?php echo $column_date_added; ?></td>
                  <td><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($cicomments) { $i = 1; ?>
                <?php foreach ($cicomments as $cicomment) { ?>
                <tr>
                  <td class="text-center"><?php echo $i; ?></td>
                  <td><a class="cia" href="<?php echo $cicomment['ciblog_post_href']; ?>"><?php echo $cicomment['ciblog_post']; ?></a></td>
                  <td><?php echo $cicomment['author']; ?></td>
                  <td><?php echo $cicomment['status']; ?></td>
                  <td><?php echo $cicomment['date_added']; ?></td>
                  <td><a class="cia btn btn-success" href="<?php echo $cicomment['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php $i++; } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-rss"></i> <?php echo $text_recent_blogs; ?></h3>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <td style="width: 50px;" class="text-center"><?php echo $column_number; ?></td>
                  <td><?php echo $column_blog; ?></td>
                  <td><?php echo $column_status; ?></td>
                  <td><?php echo $column_date_added; ?></td>
                  <td><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($ciblogposts) { $i = 1; ?>
                <?php foreach ($ciblogposts as $ciblogpost) { ?>
                <tr>
                  <td class="text-center"><?php echo $i; ?></td>
                  <td><?php echo $ciblogpost['name']; ?></td>
                  <td><?php echo $ciblogpost['status']; ?></td>
                  <td><?php echo $ciblogpost['date_added']; ?></td>
                  <td><a class="cia btn btn-success" href="<?php echo $ciblogpost['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php $i++; } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>