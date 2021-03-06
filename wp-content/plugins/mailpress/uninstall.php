<?php

if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) ) die();

class MP_uninstall
{
	function __construct()
	{
		global $wpdb;

		$wpdb->mp_mails		= $wpdb->prefix . 'mailpress_mails';
		$wpdb->mp_mailmeta	= $wpdb->prefix . 'mailpress_mailmeta';
		$wpdb->mp_users		= $wpdb->prefix . 'mailpress_users';
		$wpdb->mp_usermeta	= $wpdb->prefix . 'mailpress_usermeta';
		$wpdb->mp_stats		= $wpdb->prefix . 'mailpress_stats';
		$wpdb->mp_tracks	= $wpdb->prefix . 'mailpress_tracks';
		$wpdb->mp_forms		= $wpdb->prefix . 'mailpress_forms';
		$wpdb->mp_fields	= $wpdb->prefix . 'mailpress_formfields';

// taxonomies
		$taxonomies = array( 'MailPress_mailing_list', 'MailPress_autoresponder' );
		foreach( $taxonomies as $taxonomy )
		{
			$queries[] = "DELETE FROM $wpdb->terms WHERE term_id IN ( SELECT term_id FROM $wpdb->term_taxonomy WHERE taxonomy = '$taxonomy' );";
			$queries[] = "DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id IN ( SELECT term_id FROM $wpdb->term_taxonomy WHERE taxonomy = '$taxonomy' );";
			$queries[] = "DELETE FROM $wpdb->term_taxonomy WHERE taxonomy = '$taxonomy';";
		}
// postmeta
		$queries[] = "DELETE FROM $wpdb->postmeta WHERE meta_key like '%_MailPress%';";		
		$queries[] = "DELETE FROM $wpdb->postmeta WHERE meta_key like '%_mailpress%';";
// usermeta
		$queries[] = "DELETE FROM $wpdb->usermeta WHERE meta_key like '%_MailPress%';";		
		$queries[] = "DELETE FROM $wpdb->usermeta WHERE meta_key like '%_mailpress%';";
// options
		$queries[] = "DELETE FROM $wpdb->options WHERE option_name like '%MailPress%';";		
		$queries[] = "DELETE FROM $wpdb->options WHERE option_name like '%mailpress%';";
// mailpress tables
		$drop_tables = array ( 
							$wpdb->mp_stats, 
							$wpdb->mp_mails, 
							$wpdb->mp_mailmeta, 
							$wpdb->mp_users, 
							$wpdb->mp_usermeta, 
							$wpdb->mp_tracks, 
							$wpdb->mp_forms, 
							$wpdb->mp_fields
					 );
		foreach ( ( array ) $drop_tables as $table ) $wpdb->query( "DROP TABLE IF EXISTS $table;" );

		foreach( $queries as $query ) $wpdb->query( $query );
	}
}
new MP_uninstall(); 