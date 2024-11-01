<?php
$message = '';
$massages = array();
$messages[1] = esc_html__( 'Settings saved', 'otw_wssw' );

if( otw_get('message',false) && isset( $messages[ otw_get('message','') ] ) ){
	$message .= $messages[ otw_get('message','') ];
}
?>
<?php if ( $message ) : ?>
<div id="message" class="updated"><p><?php echo esc_html( $message ); ?></p></div>
<?php endif; ?>
<div class="wrap">
	<div id="icon-edit" class="icon32"><br/></div>
	<h2>
		<?php esc_html_e('Plugin Settings', 'otw_wssw') ?>
	</h2>
	<div class="form-wrap otw_wssw_options" id="poststuff">
		<form method="post" action="" class="validate">
			<input type="hidden" name="otw_wssw_action" value="otw_wssw_settings_action" />
			<?php wp_original_referer_field(true, 'previous'); wp_nonce_field('otw-wssw-settings'); ?>
			<div id="post-body">
				<div id="post-body-content">
					<?php include_once( 'otw_wssw_help.php' );?>
				</div>
			</div>
		</form>
	</div>
</div>

