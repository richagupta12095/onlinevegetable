<div id="ciblog-head">
  <div class="row">
    <div class="col-sm-4 text-center">
      <i class="fa fa-leaf fa-5x"></i>
    </div>
    <div class="col-sm-8">
      <ul class="ciblog-menu">
        <li>
          <a href="<?php echo $cidashboard; ?>"><i class="fa fa-tachometer" aria-hidden="true"></i> <br/> <?php echo $text_cidashboard; ?></a>
        </li>
        <li>
          <a href="<?php echo $ciauthor; ?>"><i class="fa fa-user" aria-hidden="true"></i> <br/> <?php echo $text_ciauthor; ?></a>
        </li>
        <li>
          <a href="<?php echo $cicategory; ?>"><i class="fa fa-link" aria-hidden="true"></i> <br/> <?php echo $text_cicategory; ?></a>
        </li>
        <li>
          <a href="<?php echo $ciblogpost; ?>"><i class="fa fa-pencil-square" aria-hidden="true"></i> <br/> <?php echo $text_ciblogpost; ?></a>
        </li>
        <li>
          <a href="<?php echo $cicomment; ?>"><i class="fa fa-comment" aria-hidden="true"></i> <br/> <?php echo $text_cicomment; ?></a>
        </li>
        <li>
          <a href="<?php echo $cisubscriber; ?>"><i class="fa fa-users" aria-hidden="true"></i> <br/> <?php echo $text_cisubscriber; ?></a>
        </li>
        <li>
          <a href="<?php echo $cisetting; ?>"><i class="fa fa-cogs" aria-hidden="true"></i> <br/> <?php echo $text_cisetting; ?></a>
        </li>
        <br style="clear: both;">
      </ul>
    </div>
  </div>
</div>
<script type="text/javascript">
  // Set last page opened on the menu
  $('#menu-ciblog a[href], #ciblog-head a[href]').on('click', function() {
    sessionStorage.setItem('menu', $(this).attr('href'));
  });

  if (sessionStorage.getItem('menu')) {
    // Sets active and open to selected page in the left column menu.
    $('#menu-ciblog a[href=\'' + sessionStorage.getItem('menu') + '\']').addClass('active open');
    $('#ciblog-head a[href=\'' + sessionStorage.getItem('menu') + '\']').addClass('active open');
  }
</script>