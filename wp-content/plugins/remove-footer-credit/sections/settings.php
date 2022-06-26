<form method="post" action="<?php echo ( ( isset( $_SERVER['REQUEST_URI'] ) ) ? esc_attr( $_SERVER['REQUEST_URI'] ) : '' ); ?>" class="settings-form">

	<?php $replace = ''; if ( $this->options['replace'] )  $replace = implode( "\n", $this->options['replace'] ); ?>
	<?php $willLinkback = 'no'; if ( $this->options['willLinkback'] )  $willLinkback = $this->options['willLinkback']; ?>
	<?php $linkbackPostId = ''; if ( $this->options['linkbackPostId'] )  $linkbackPostId = $this->options['linkbackPostId']; ?>

	<h3><?php echo esc_html__( 'Step 1: Enter text/HTML to remove (one per line)', 'remove-footer-credit'); ?></h3>
	<p><textarea name="find" id="find" class="small-text code" rows="6" style="width: 100%;"><?php if ($this->options['find']) echo esc_textarea( implode( "\n", $this->options['find'] ) ); ?></textarea></p>
	<h3><?php echo esc_html__( 'Step 2: Enter your own footer credit (one per line)', 'remove-footer-credit'); ?></h3>
	<?php wp_editor( $replace, 'replace', $settings = array('quicktags' => true, 'wpautop' => false, 'editor_height' => '100', 'teeny' => false ) ); ?>
	<h3><?php echo esc_html__( 'Step 3: Please support my work and spread the word (optional)', 'remove-footer-credit' ); ?></h3>
	<p><?php echo esc_html__( 'Help keep this plugin free by providing one link back at the bottom of one of your posts/pages.', 'remove-footer-credit' ); ?></p>
	<label><input type="radio" name="willLinkback" value="no" class="js-linkback" <?php if ( 'no' == $willLinkback ) echo 'checked="checked"' ?>> <?php echo esc_html__( 'No, thanks.', 'remove-footer-credit' ); ?></label><br>
	<label><input type="radio" name="willLinkback" value="yes" class="js-linkback" <?php if ('yes' == $willLinkback ) echo 'checked="checked"' ?>> <?php echo esc_html__( 'Yes, I will support you!', 'remove-footer-credit' ); ?></label>

	<div class="js-linkback-panel" style="<?php echo ( ( 'no' == $willLinkback ) ? 'display: none;' : '' ); ?> margin-top: 15px;">
		<?php
		$post_args   = array(
			'posts_per_page'   => -1,
			'orderby'          => 'title',
			'order'            => 'asc',
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'suppress_filters' => true,
		);
		$page_args   = array(
			'posts_per_page'   => -1,
			'orderby'          => 'title',
			'order'            => 'asc',
			'post_type'        => 'page',
			'post_status'      => 'publish',
			'suppress_filters' => true,
		);
		$posts_array = get_posts( $post_args );
		$pages_array = get_posts( $page_args );
		?>
		<strong><?php echo esc_html__( 'Select a post/page:', 'remove-footer-credit' ); ?></strong><br>
		<select name="linkbackPostId" style="margin-bottom: 15px;">
			<?php if ( sizeof( $posts_array ) > 0 ) { ?>
				<option disabled><?php echo esc_html__( '-- Posts --', 'remove-footer-credit' ); ?></option>
				<?php foreach ( $posts_array as $item ) { ?>
				<option value="<?php echo esc_attr( $item->ID ) ?>" <?php echo( $linkbackPostId == $item->ID ) ? 'selected=selected' : ''; ?>><?php echo esc_html( $item->post_title ); ?></option>
				<?php } ?>
			<?php } ?>
			<?php if ( sizeof( $pages_array ) > 0 ) { ?>
				<option disabled><?php echo esc_html__( '-- Pages --', 'remove-footer-credit' ); ?></option>
				<?php foreach ( $pages_array as $item ) { ?>
					<option value="<?php echo esc_attr( $item->ID ); ?>" <?php echo( $linkbackPostId == $item->ID ) ? 'selected=selected' : ''; ?>><?php echo esc_html( $item->post_title ); ?></option>
				<?php } ?>
			<?php } ?>
		</select>

		<div>
			<strong><?php echo esc_html__( 'The text below will appear at the bottom of the selected post/page.', 'remove-footer-credit' ); ?></strong><br>
			<?php echo esc_html__( 'Get WordPress help, plugins, themes and tips at', 'remove-footer-credit' ); ?> <a href="https://www.machothemes.com">MachoThemes.com</a>.
		</div>
	</div>
	<div style="margin-top: 20px;">
		<?php wp_nonce_field( 'remove_footer_credit_options', 'remove_footer_credit_nonce' ); ?>
		<input type="submit" class="button" value="Save" />
	</div>
</form>
<script>
	jQuery('.js-linkback').change(function() {
		jQuery('.js-linkback-panel').toggle();
	});

</script>