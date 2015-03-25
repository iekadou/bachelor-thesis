<?php $head = new Template(); $head->load("head.php")->display(); ?>
<div id="page">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-4 col-lg-4 col-lg-offset-4 text-center">
                <h2><?php echo getTranslation('Logout'); ?></h2>
                <p>
                    <?php echo getTranslation('You are successfully logged out.'); ?>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $footer = new Template(); $footer->load("footer.php")->display();