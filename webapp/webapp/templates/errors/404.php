<?php $head = new Template(); $head->load("head.php")->display(); ?>
    <div id="page">
        <div class="home-hero">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <h1>
                            <?php echo getTranslation('404'); ?>
                        </h1>
                        <h2>
                            <?php echo getTranslation('Not found.'); ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $footer = new Template(); $footer->load("footer.php")->display();
