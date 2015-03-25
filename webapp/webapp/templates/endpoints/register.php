<?php $head = new Template(); $head->load("head.php")->display(); ?>
    <div id="page">
        <div class="container">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-4 col-lg-4 col-lg-offset-4">
                    <form class="form api-form" role="form" action="/api/register/" method="POST" enctype="multipart/form-data">
                        <h2 class="page-header"><?php echo getTranslation('Register an Account'); ?></h2>
                        <p class="small"><?php echo getTranslation("These data will not be visible in public."); ?></p>
                        <div class="form-group">
                            <label class="control-label" for="id_username"><?php echo getTranslation('YourUsername:'); ?>*</label>
                            <input type="text" name="username" id="id_username" class="form-control" value="" placeholder="" required="" autofocus="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="id_email"><?php echo getTranslation('YourEmail:'); ?>*</label>
                            <input type="email" name="email" id="id_email" class="form-control" value="" placeholder="" required="" autofocus="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="id_password"><?php echo getTranslation('YourPassword:'); ?>*</label>
                            <input type="password" name="password" id="id_password" class="form-control" value="" placeholder="" required="" autofocus="">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="id_password"><?php echo getTranslation('YourPasswordAgain:'); ?>*</label>
                            <input type="password" name="password_repeat" id="id_password_repeat" class="form-control" value="" placeholder="" required="" autofocus="">
                        </div>
                        <p class="small"><?php echo getTranslation("Fields with * are required."); ?></p>
                        <br>
                        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo getTranslation('Register'); ?></button>
                      </form>
                </div>
            </div>
        </div>
    </div>
<?php $footer = new Template(); $footer->load("footer.php")->display();