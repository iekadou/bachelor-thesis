<?php $head = new Template(); $head->load("head.php")->display(); ?>
    <div id="page">
        <div class="home-hero">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <h1>
                            <?php echo getTranslation('PJAXR'); ?>
                        </h1>
                        <h2>
                            <?php echo getTranslation('speeds up your page.'); ?>
                        </h2>
                        <img src="/img/pjaxr-flow.png" alt="Pjaxr" class="img-responsive" style="display: inline-block;">
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row padding-50-0">
                <div class="col-sm-4 text-center">
                    <p><span class="fa fa-tachometer font-size-120"></span></p>
                    <h2><?php echo getTranslation('Speed'); ?></h2>
                    <p>
                        <?php echo getTranslation('load times are reduced<br>by pjaxr.'); ?>
                    </p>
                </div>
                <div class="col-sm-4 text-center">
                    <p><span class="fa fa-pie-chart font-size-120"></span></p>
                    <h2><?php echo getTranslation('Traffic'); ?></h2>
                    <p>
                        <?php echo getTranslation('traffic is reduced<br>by pjaxr.'); ?>
                    </p>
                </div>
                <div class="col-sm-4 text-center">
                    <p><span class="fa fa-line-chart font-size-120"></span></p>
                    <h2><?php echo getTranslation('Requests'); ?></h2>
                    <p>
                        <?php echo getTranslation('less requests are required<br>by pjaxr.'); ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row padding-50-0">
                <div class="col-xs-12 text-center">
                    <h2>
                        <?php echo getTranslation('This page is powered by'); ?>
                    </h2>
                </div>
                <div class="col-xs-10 col-xs-offset-1 col-sm-5 col-sm-offset-1 text-center">
                    <a href="#" class="btn btn-block btn-primary"><?php echo getTranslation('php-pjaxr'); ?></a>
                </div>
                <div class="col-xs-12 visible-xs"><br></div>
                <div class="col-xs-10 col-xs-offset-1 col-sm-offset-0 col-sm-5 text-center">
                    <a href="#" class="btn btn-block btn-female"><?php echo getTranslation('jquery-pjaxr'); ?></a>
                </div>
            </div>
        </div>
    </div>
<?php $footer = new Template(); $footer->load("footer.php")->display();