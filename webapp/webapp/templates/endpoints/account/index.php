<?php $head = new Template(); $head->load("head.php")->display();
$acc_head = new Template(); $acc_head->load("account_head.php")->display(); ?>
<div id="content">
    <h1 class="page-header"><?php echo getTranslation('Dashboard'); ?></h1>
    <p><?php echo getTranslation('Here you can find a overview of your inventories on Pjaxr.io.'); ?></p>
    <h2 class="sub-header">Kühlschrank</h2>
    <div class="row placeholders">
        <div class="col-xs-6 col-xs-offset-3 col-sm-3 col-sm-offset-0 col-md-2 col-md-offset-0 placeholder text-center">
            <div class="img-responsive avatar" style="color: #a00;"><div class="initials">2<span class="fa fa-bolt"></span></div></div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-vertical-aligned">
                <thead>
                <tr>
                    <th><?php echo getTranslation('Name'); ?></th>
                    <th><?php echo getTranslation('Current'); ?></th>
                    <th><?php echo getTranslation('Desired'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Milch</td>
                    <td style="color: #a00;">1</td>
                    <td>3</td>
                </tr>
                <tr>
                    <td>Pizza</td>
                    <td style="color: #0a0;">5</td>
                    <td>3</td>
                </tr>
                <tr>
                    <td>Haferflocken</td>
                    <td style="color: #a00;">1</td>
                    <td>2</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <h2 class="sub-header">Putzschrank</h2>
    <div class="row placeholders">
        <div class="col-xs-6 col-xs-offset-3 col-sm-3 col-sm-offset-0 col-md-2 col-md-offset-0 placeholder text-center">
            <div class="img-responsive avatar" style="color: #0a0;"><div class="initials">3<span class="fa fa-check"></span></div></div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-vertical-aligned">
                <thead>
                <tr>
                    <th><?php echo getTranslation('Name'); ?></th>
                    <th><?php echo getTranslation('Current'); ?></th>
                    <th><?php echo getTranslation('Desired'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Lappen</td>
                    <td style="color: #0a0;">3</td>
                    <td>3</td>
                </tr>
                <tr>
                    <td>Schwamm</td>
                    <td style="color: #0a0;">4</td>
                    <td>3</td>
                </tr>
                <tr>
                    <td>Glasreiniger</td>
                    <td style="color: #0a0;">3</td>
                    <td>2</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $acc_footer = new Template(); $acc_footer->load("account_footer.php")->display();
$footer = new Template(); $footer->load("footer.php")->display();
