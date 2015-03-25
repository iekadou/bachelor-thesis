<?php $head = new Template(); $head->load("head.php")->display();
if (View::get_var('pjaxr')->get_matching() <= 1) { // PJAXR Site and/or Page pattern does not match ?>
    <div id="page">
        <div class="home-hero">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <h1>
                            <?php echo getTranslation('Tags'); ?>
                        </h1>
                        <h2>
                            <?php echo getTranslation('a sample queryset DELICIOUS.'); ?>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 text-center">
                        <?php
                        $letters = ['-', '0-9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
                        if (!isset($DB_CONNECTOR)) {
                            $DB_CONNECTOR = new DBConnector();
                        }
                        $letter_counts = array();
                        for ($i=0; $i < sizeof($letters); $i++) {
                            $letter = $letters[$i];
                            if ($letter == '-') {
                                $letter_count = $DB_CONNECTOR->query("SELECT count(DISTINCT tag) as cnt FROM tas_delicious_time WHERE tag REGEXP '^[^0-9A-Za-z]';");
                            } elseif ($letter == '0-9') {
                                $letter_count = $DB_CONNECTOR->query("SELECT count(DISTINCT tag) as cnt FROM tas_delicious_time WHERE tag REGEXP '^[0-9]';");
                            } else {
                                $letter_count = $DB_CONNECTOR->query("SELECT count(DISTINCT tag) as cnt FROM tas_delicious_time WHERE tag LIKE '".$letter."%';");
                            }
                            $letter_counts[$letter] = $letter_count->fetch_array()['cnt'];
                            echo '<a class="tagLetter';
                            if (View::get_var('current_char')==$letter) { echo ' strong'; }
                            echo '" href="/tags/'.$letter.'/">'.$letter.' ('.$letter_counts[$letter].')';
                            echo '</a><br>';
                        }
                        ?>
                    </div>
                    <div class="col-xs-6 text-center">
<?php } // PJAXR Site and/or Page pattern does not match ?>
                        <div id="content">
                            <?php foreach(View::get_var('objects') as $object) {
                                echo $object->tag.'<br>';
                            }
                            View::get_var('pagination')->render();

                            if (View::get_var('pjaxr')->get_matching() >= 1) { ?>
                                <script>
                                    $('.tagLetter').removeClass('strong');
                                    $('.tagLetter[href="/tags/<?php echo View::get_var('current_char'); ?>/"').addClass('strong');
                                </script>
                            <?php } ?>
                        </div>
<?php if (View::get_var('pjaxr')->get_matching() <= 1) { // PJAXR Site and/or Page pattern does not match  ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } // PJAXR Site and/or Page pattern does not match  ?>
<?php $footer = new Template(); $footer->load("footer.php")->display();