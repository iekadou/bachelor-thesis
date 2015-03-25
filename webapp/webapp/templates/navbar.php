<?php if (View::get_account()->is_logged_in() != true) { ?>
    <li class="dropdown" id="inner_navbar">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo getTranslation("Login"); ?> <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <form class="navbar-form api-form" role="form" action="<?php echo getUrl('api:login'); ?>" method="POST">
                    <fieldset>
                    <div class="form-group required clearfix">
                            <div class="controls">
                                <input id="id_navbar_identification" class="form-control" maxlength="64" name="identification" type="text" placeholder="<?php echo getTranslation("email/username"); ?>">
                            </div>
                        </div>
                        <div class="form-group required clearfix">
                            <div class="controls">
                                <input id="id_navbar_password" class="form-control" name="password" type="password" placeholder="<?php echo getTranslation("password"); ?>">
                            </div>
                         </div>
                    </fieldset>
                    <button href="#" class="btn btn-primary btn-block"><?php echo getTranslation("Login"); ?></button>
                    <li class="divider"></li>
                    <li><a href="<?php echo getUrl('register'); ?>" class="btn btn-default btn-block"><?php echo getTranslation("Register"); ?></a></li>
                    <li><a href="#" class="btn btn-default btn-block"><?php echo getTranslation("Forgot Password"); ?></a></li>
                </form>
            </li>
        </ul>
    </li>
<?php } else { ?>
    <li class="dropdown" id="inner_navbar">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo View::get_account()->get_user()->get_username(); ?> <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li class="{{dashboard_active}}"><a href="<?php echo getUrl('account:dashboard'); ?>" class="btn btn-default"><?php echo getTranslation('Dashboard'); ?></a></li>
            <li class="{{profile_active}}"><a href="<?php echo getUrl('account:profile'); ?>" class="btn btn-default"><?php echo getTranslation('Profile'); ?></a></li>
            <li><a href="<?php echo getUrl('logout'); ?>" class="btn btn-default"><?php echo getTranslation("Logout")?></a></li>
        </ul>
    </li>
<?php } ?>
