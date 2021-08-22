<div class="accordion">
	<div class="accordion-header border-main-color">
		<span class="accordion-title main-color"><?php block_field( 'title' ); ?></span>
        <span class="accordion-action main-color">+</span>
    </div>
	<div class="accordion-content">
		<?php block_field( 'content' ); ?>
        <?php if(block_value('page')->ID !== get_the_ID()) { ?>
            <a class="post-box-action button main-background" href="<?php echo get_permalink(block_value('page')); ?>">mehr erfahren</a>
        <?php } ?>
	</div>
</div>
