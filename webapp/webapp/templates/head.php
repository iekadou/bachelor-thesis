<?php if (!View::get_var('pjaxr')->get_matching()) { ?>
<html lang="{{lang}}">
    <head>
        <?php } else { ?><pjaxr-head><?php } ?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="minimum-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="/favicon.ico">

        <title>{{title}}</title>
        <?php if (View::get_var('pjaxr')->get_matching()) { ?></pjaxr-head><?php } else { ?>
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/font-awesome.min.css" rel="stylesheet">
        <link href="/css/toastr.min.css" rel="stylesheet">
        <link href="/css/main.css" rel="stylesheet">
        <link href="/css/dashboard.css" rel="stylesheet">

        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
<?php } ?>
<?php if (!View::get_var('pjaxr')->get_matching()) { ?>
    <body data-pjaxr-namespace="<?php echo View::get_var('pjaxr')->get_current_namespace(); ?>">
    <div id="loading-icon" style="width: 100%; height: 100%; position: fixed; z-index: 111000000; background-color: rgba(255, 255, 255, 0.5); display: none; top: 0; left: 0;"><span class="fa fa-refresh fa-spin" style="position: absolute; left: 50%; top: 50%; font-size:100px; margin: -50px 0 0 -50px;"></span></div>
    <div id="site">
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#pjaxr-navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/"><img src="/img/pjaxr.png" alt="Pjaxr.io Logo"></a>
                </div>
                <ul id="pjaxr-navbar" class="nav navbar-nav navbar-collapse collapse navbar-right">
                    <li><a href="#"><div id="render_time" class="pull-left">{{render_time}} s {{QUERY_COUNT}} queries on {{QUERY_COUNT}} connections</div></a></li>
                    <li><a href="#"><?php echo date("d.m.Y - H:i:s", time()); ?></a></li>
                    <li><a href="#">
                    <?php
                        global $DB_CONNECTOR;
                        if (!isset($DB_CONNECTOR)) {
                            $DB_CONNECTOR = new DBConnector();
                        }
                        $rand_query = $DB_CONNECTOR->query("SELECT tag FROM tas_delicious_time ORDER BY RAND() LIMIT 1");
                        $tag = $rand_query->fetch_array()['tag'];
                        $count_query = $DB_CONNECTOR->query("SELECT Count(*) as cnt FROM tas_delicious_time WHERE tag = '".$tag."'");
                        if ($count_query->num_rows >= 1) {
                            echo 'random tag: <strong>'. $tag.'</strong> ('.$count_query->fetch_array()['cnt'].' times tagged)';
                        }
                        ?></a></li>
                    <li><a href="<?php echo UrlsPy::get_url('tags'); ?>"><?php echo getTranslation("tags"); ?></a></li>
                    <?php $navbar = new Template(); $navbar->load("navbar.php")->display(); ?>
                </ul>
            </div>
        </nav>
<?php } else { ?>
    <pjaxr-namespace><?php echo View::get_var('pjaxr')->get_current_namespace(); ?></pjaxr-namespace>
    <pjaxr-body>
<?php }
