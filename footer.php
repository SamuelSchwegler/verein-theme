<?php
    $json = file_get_contents(get_stylesheet_directory().'/config.json');
    $config = json_decode($json, 0);
?>
            <div id="footer">
                <div class="row">
                    <div class="col">
                        <h3>Links</h3>
                        <p>
                            <?php foreach($config->footer_links as $from => $link) { ?>
                                <a href="<?php echo $link; ?>"><?php echo $link; ?></a><br>
                            <?php } ?>
                        </p>
                    </div>
                    <div class="col">
                        <h3>Kontakt</h3>
                        <?php echo $config->footer_address->name; ?></br>
                        <?php echo $config->footer_address->street; ?></br>
                        <?php echo ($config->footer_address->postcode ?? '')." ".$config->footer_address->city; ?>
                    </div>
                    <div class="col"></div>
                </div>
            </div>
        </div>
    </body>
</html>
