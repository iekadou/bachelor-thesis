<?php $head = new Template(); $head->load("head.php")->display();
$acc_head = new Template(); $acc_head->load("account_head.php")->display(); ?>
<div id="content">
    <h1 class="page-header"><?php echo getTranslation('Profile'); ?></h1>
    <form class="form api-form form-horizontal" role="form" action="/api/profile/" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="userid" value="<?php echo View::get_account()->get_user_id(); ?>">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="id_username"><?php echo getTranslation('YourUsername:'); ?>*</label>
            <div class="col-sm-9"><input type="text" name="username" id="id_username" class="form-control" value="<?php echo View::get_account()->get_user()->get_username(); ?>" placeholder="" required=""></div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="id_email"><?php echo getTranslation('YourEmail:'); ?>*</label>
            <div class="col-sm-9"><input type="email" name="email" id="id_email" class="form-control" value="<?php echo View::get_account()->get_user()->get_email(); ?>" placeholder="" required=""></div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="id_password"><?php echo getTranslation('YourPassword:'); ?>**</label>
            <div class="col-sm-9"><input type="password" name="password" id="id_password" class="form-control" value="" placeholder=""></div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label" for="id_password_again"><?php echo getTranslation('YourPasswordAgain:'); ?>**</label>
            <div class="col-sm-9"><input type="password" name="password_again" id="id_password_again" class="form-control" value="" placeholder=""></div>
        </div>
        <div class="row">
            <div class="col-sm-9 col-sm-offset-3">
                <p class="small"><?php echo getTranslation("Fields with * are required."); ?></p>
                <p class="small"><?php echo getTranslation("If you leave Fields with ** blank, they will not be udpated."); ?></p>
                <br>
            </div>
        </div>
        <div class="fom-control">
            <div class="col-sm-offset-3 col-sm-9">
                <button class="btn btn-primary" type="submit"><?php echo getTranslation('Save'); ?></button>
                <button class="btn btn-default" type="reset"><?php echo getTranslation('Reset'); ?></button>
            </div>
        </div>
    </form>
</div>
<?php $acc_footer = new Template(); $acc_footer->load("account_footer.php")->display();
$footer = new Template(); $footer->load("footer.php")->display();
