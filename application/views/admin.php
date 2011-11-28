<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $template_title ?></title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le styles -->
    <?php Assets::css('bootstrap'); ?>
    <?php Assets::css('main'); ?>
    <?php Assets::css('jquery-ui'); ?>
    <?php isset($css) AND Assets::css($css); ?>
    <?php echo Assets::show_css(); ?>
    <style type="text/css">
      body {
        padding-top: 60px;
      }
    </style>

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
  </head>

  <body>

    <div class="topbar">
      <div class="topbar-inner">
        <div class="container-fluid">
          <?php echo anchor('admin', $template_title, array('class' => 'brand')); ?>
          <?php echo Menu_Builder::get_menu('top_menu'); ?>
          <p class="pull-right">
            <a class="notification" href="#" data-placement="below" rel='twipsy' title='Tiene 3859 items pendientes.'>3859</a>
            <a href="#"><?php echo $this->acl->first_name.' '.$this->acl->last_name ?></a>&nbsp;&nbsp;&nbsp;
            <a href="#" class="btn primary">Logout</a>
          </p>
        </div>
      </div>
    </div>

    <?php echo Message::get() ?>
    <div class="container-fluid">
      <div class="sidebar">
        <?php echo isset($template_menu) ? $template_menu : ''?>
        <?php echo isset($template_filters) ? $template_filters : ''; ?>
      </div>
      <div class="content">
        <?php echo $template_main ?>
        <footer>
          <p>&copy; Company 2011</p>
        </footer>
      </div>
    </div>
    <?php Assets::js('jquery') ?>
    <?php Assets::js('jquery-ui') ?>
    <?php Assets::js('bootstrap-twipsy') ?>
    <?php Assets::js('main') ?>
    <?php echo Assets::show_js(); ?>    

  </body>
</html>