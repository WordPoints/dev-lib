<?php

unserialize( $data ); // Bad
maybe_unserialize( $data ); // Bad

wp_redirect( $url ); // Bad
wp_safe_redirect( $url ); // OK

wp_remote_post( $args ); // Bad
wp_safe_remote_post( $args ); // OK
wp_remote_get( $args ); // Bad
wp_safe_remote_get( $args ); // OK

$body = wp_remote_retrieve_body( $response ); // OK

esc_sql( $data ); // Bad

esc_js( $data ); // Bad

gmdate( $data ); // Bad
