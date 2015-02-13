<?php
esc_html( $var ); // OK
?>
<?php $var // Bad ?>
<?php echo $var; // OK ?>
<?php echo esc_html( $var ); // OK ?>
<?php esc_html( $var ); // Bad ?>
<?php esc_html_e( $var ); // OK ?>

<?php if ( $something ) : // OK ?>
	<?php $wp_list_table->display(); // OK ?>
<?php endif; // OK ?>

<?php $var = something(); // OK ?>
