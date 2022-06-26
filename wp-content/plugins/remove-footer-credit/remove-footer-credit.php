<?php
/**
 * Plugin Name:             Remove Footer Credit
 * Description:             A simple plugin to remove footer credits
 * Version:                 1.0.13
 * Author:                  WPChill
 * Author URI:              https://wpchill.com
 * Requires:                5.2 or higher
 * License:                 GPLv3 or later
 * License URI:             http://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP:            5.6
 * Text Domain:             remove-footer-credit
 * Tested up to:            5.9
 *
 * Copyright 2016-2017      Joe Bill            joe@upwerd.com
 * Copyright 2017-2020      MachoThemes         office@machothemes.com
 * Copyright 2020           WPChill             heyyy@wpchill.com
 *
 * Original Plugin URI:     https://upwerd.com/remove-footer-credit
 * Original Author URI:     https://upwerd.com/
 * Original Author:         https://profiles.wordpress.org/upwerd/
 *
 * NOTE:
 * Joe Bill transferred ownership rights on: 11/13/2017 05:12:22 PM when ownership was handed over to MachoThemes
 * The MachoThemes ownership period started on: 11/13/2017 05:12:23 PM
 * SVN commit proof of ownership transferral: https://plugins.trac.wordpress.org/changeset/1765266/remove-footer-credit
 *
 * WPChill received ownership from MachoThemes on 5th of November, 2020. WPChill is a restructure and rebrand of MachoThemes.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RFC_Plugin {

	private $tabs;
	private $options;
	private $assets_path;

	function __construct() {

		$this->tabs        = array(
			'settings' => array(
				'label' => esc_html__( 'Settings', 'remove-footer-credit' ),
				'path'  => 'settings.php',
			),
			'plugins' => array(
				'label' => esc_html__( 'Other Great Plugins', 'remove-footer-credit' ),
				'path'  => 'plugins.php',
			),
			'help' => array(
				'label' => esc_html__( 'Get Help', 'remove-footer-credit' ),
				'path'  => 'plugin-info.php',
			),
		);
		$options_defaults  = array(
			'replace'        => array(),
			'willLinkback'   => 'no',
			'linkbackPostId' => 'no',
			'find'           => array(),
		);
		$this->options     = get_option( 'jabrfc_text' );
		$this->options     = wp_parse_args( $this->options, $options_defaults );
		$this->assets_path = plugin_dir_url( __FILE__ ) . 'assets/';

		$this->public_hooks();
		$this->admin_hooks();

	}

	private function public_hooks() {

		add_filter( 'the_content', array( $this, 'jabrfc_the_content' ) );

		// Handles find and replace for public pages.
		add_action( 'template_redirect', array( $this, 'jabrfc_template_redirect' ) );

	}

	private function admin_hooks() {

		if ( ! is_admin() ) {
			return;
		}

		// Add style.
		add_action( 'admin_enqueue_scripts', array( $this, 'jabrfc_admin_enqueue_scripts' ) );

		// Add left menu item in admin.
		add_action( 'admin_menu', array( $this, 'jabrfc_admin_menu' ) );

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

	}

	private function generate_url( $tab = 'settings' ){
		return admin_url( 'tools.php?page=remove-footer-credit&tab=' . $tab );
	}

	/**
	 * Load plugin textdomain
	 */
	public function load_textdomain(){
		load_plugin_textdomain( 'remove-footer-credit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	public function jabrfc_the_content( $content ) {
		global $post;
		$data = $this->options;
		if ( 'yes' == $data['willLinkback'] && is_singular() && $data['linkbackPostId'] == $post->ID ) {
			$content = $content . esc_html__( 'Get WordPress help, plugins, themes and tips at ', 'remove-footer-credit' ) . '<a href="https://www.machothemes.com?utm_source=remove-footer-credit&utm_medium=front&utm_campaign=credit-link">'. esc_html__( 'MachoThemes.com','remove-footer-credit' ) . '</a>';
		}
		return $content;
	}

	/*
	* Add a submenu under Tools
	*/
	public function jabrfc_admin_menu() {
		add_submenu_page( 'tools.php', esc_html__( 'Remove Footer Credit', 'remove-footer-credit' ), esc_html__( 'Remove Footer Credit', 'remove-footer-credit' ), 'activate_plugins', 'remove-footer-credit', array( $this, 'jabrfc_options_page' ) );
	}

	public function jabrfc_admin_enqueue_scripts( $hook ) {

		if ( 'tools_page_remove-footer-credit' != $hook ) {
			return;
		}

		wp_enqueue_style( 'jabrfc-styles', $this->assets_path . 'css/admin.css' );
		wp_enqueue_script( 'jabrfc-plugin-install', $this->assets_path . 'js/plugin-install.js', array( 'jquery', 'updates' ), '1.0.0', 'all' );

	}

	public function jabrfc_template_redirect() {
		ob_start();
		ob_start( 'jabrfc_ob_call' );
	}

	public function jabrfc_options_page() {

		if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

			//phpcs:ignore
			if ( ! isset( $_POST['remove_footer_credit_nonce'] ) || ! wp_verify_nonce( $_POST['remove_footer_credit_nonce'], 'remove_footer_credit_options' ) ) {
				return;
			}

			$_POST = stripslashes_deep( $_POST );

			// Sanitization done inside function jabrfc_kses.
			$find    = jabrfc_kses( ( isset( $_POST['find'] ) ) ? $_POST['find']  : '' ); //phpcs:ignore
			// Sanitization done inside function jabrfc_kses.
			$replace = jabrfc_kses( ( isset( $_POST['replace'] ) ) ? $_POST['replace'] : '' ); //phpcs:ignore

			$data = array(
				'find'           => explode( "\n", str_replace( "\r", "", $find ) ),
				'replace'        => explode( "\n", str_replace( "\r", "", $replace ) ),
				'willLinkback'   => ( isset( $_POST['willLinkback'] ) ) ? sanitize_text_field( wp_unslash( $_POST['willLinkback'] ) ) : '',
				'linkbackPostId' => ( isset( $_POST['linkbackPostId'] ) ) ? sanitize_text_field( wp_unslash( $_POST['linkbackPostId'] ) ) : '',
			);

			update_option( 'jabrfc_text', $data );
			$this->options = $data;

			echo '<div id="message" class="updated fade">';
				echo '<p><strong>' . esc_html__( 'Settings Saved', 'remove-footer-credit' ) . '</strong></p>';
			echo '</div>';
		}

		$current_tab = 'settings';
		if ( isset( $_GET['tab'] ) && isset( $this->tabs[ $_GET['tab'] ] ) ) {
			$current_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
		}

		echo '<div class="wrap about-wrap epsilon-wrap">';
		echo '<h1>' . esc_html__( 'Remove Footer Credit', 'remove-footer-credit' ) . '</h1>';
		echo '<div class="about-text">';
				/* Translators: Welcome Screen Description. */
				echo esc_html__( 'Remove or replace footer credits (or any text or HTML in page) before page is rendered. With this plugin there is no need to modify code such as footer.php which if done incorrectly can cause your site to break or new theme updates will stomp over your changes requiring you to remove footer credits on each update.', 'remove-footer-credit' );
			echo '</div>';
		echo '<div class="wp-badge remove-footer-credit"><span class="dashicons dashicons-editor-unlink"></span></div>';
		echo '<h2 class="nav-tab-wrapper wp-clearfix">';
		foreach ( $this->tabs as $tab_id => $tab ) {
			echo '<a class="nav-tab ' . ( $tab_id === $current_tab ? 'nav-tab-active' : '' ) . '" href="' . esc_url( $this->generate_url( $tab_id ) ) . '">' . esc_html( $tab['label'] ) . '</a>';
		}
		echo '</h2>';
		include 'sections/' . $this->tabs[ $current_tab ]['path'];
		echo '</div>';

	}

}

new RFC_Plugin();

/*
* Apply find and replace rules
*/
function jabrfc_ob_call( $buffer ) { // $buffer contains entire page.

	$data = get_option( 'jabrfc_text' );

	if ( is_array( $data['find'] ) ) {
		foreach ( $data['find'] as $i => $value ) {

			$value   = jabrfc_kses( $value );
			$replace = '';
			if ( isset( $data['replace'][ $i ] ) ) {
				$replace = html_entity_decode( jabrfc_kses( $data['replace'][ $i ] ) );
			}

			// try to replace wihtout html_entity_decode.
			$buffer = str_replace( $value, $replace, $buffer );
			$buffer = str_replace( html_entity_decode( $value ), $replace, $buffer );

		}
	}
	return $buffer;
}

function jabrfc_kses( $string ){
	$allowed_protocols = wp_allowed_protocols();
	$allowed_html      = wp_kses_allowed_html( 'post' );

	$string = wp_kses_no_null( $string, array( 'slash_zero' => 'keep' ) );
	$string = wp_kses_normalize_entities( $string );
	$string = wp_kses_hook( $string, $allowed_html, $allowed_protocols );

	return $string;
}
