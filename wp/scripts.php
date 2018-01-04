<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2018  Yoann Le Montagner <yo35 -at- melix.net>       *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or modify    *
 *    it under the terms of the GNU General Public License as published by    *
 *    the Free Software Foundation, either version 3 of the License, or       *
 *    (at your option) any later version.                                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *    GNU General Public License for more details.                            *
 *                                                                            *
 *    You should have received a copy of the GNU General Public License       *
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *                                                                            *
 ******************************************************************************/


/**
 * Register the plugin JavaScript scripts.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBChessboardScripts {

	public static function register() {
		$ext = self::get_js_file_extension();

		// Moment.js (http://momentjs.com/)
		wp_register_script( 'rpbchessboard-momentjs', RPBCHESSBOARD_URL . 'third-party-libs/moment-js/moment' . $ext, false, '2.13.0' );
		$momentjs = self::localize_javascript_lib( 'rpbchessboard-momentjs', 'third-party-libs/moment-js/locales/%1$s.js', '2.13.0' );

		// RPBChess
		wp_register_script( 'rpbchessboard-core', RPBCHESSBOARD_URL . 'js/rpbchess-core' . $ext, false, RPBCHESSBOARD_VERSION );
		wp_register_script(
			'rpbchessboard-pgn', RPBCHESSBOARD_URL . 'js/rpbchess-pgn' . $ext, array(
				'rpbchessboard-core',
			), RPBCHESSBOARD_VERSION
		);

		// Chessboard widget
		wp_register_script(
			'rpbchessboard-chessboard', RPBCHESSBOARD_URL . 'js/rpbchess-ui-chessboard' . $ext, array(
				'rpbchessboard-core',
				'jquery-ui-widget',
				'jquery-ui-selectable',
			), RPBCHESSBOARD_VERSION
		);

		// Chessgame widget
		wp_register_script(
			'rpbchessboard-chessgame', RPBCHESSBOARD_URL . 'js/rpbchess-ui-chessgame' . $ext, array(
				'rpbchessboard-core',
				'rpbchessboard-pgn',
				'rpbchessboard-chessboard',
				$momentjs,
				'jquery-ui-widget',
				'jquery-ui-button',
				'jquery-ui-selectable',
				'jquery-color',
				'jquery-ui-dialog',
				'jquery-ui-resizable',
			), RPBCHESSBOARD_VERSION
		);

		// Plugin specific
		wp_register_script(
			'rpbchessboard-backend', RPBCHESSBOARD_URL . 'js/backend' . $ext, array(
				'rpbchessboard-chessboard',
				'jquery-ui-dialog',
				'jquery-ui-accordion',
				'jquery-ui-draggable',
				'jquery-ui-droppable',
			), RPBCHESSBOARD_VERSION
		);

		// Enqueue the scripts.
		wp_enqueue_script( 'rpbchessboard-chessboard' );
		wp_enqueue_script( 'rpbchessboard-chessgame' );

		// Additional scripts for the backend.
		if ( is_admin() ) {
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'iris' );
			wp_enqueue_script( 'rpbchessboard-backend' );
			wp_enqueue_media();
		}

		// Inlined scripts
		add_action( is_admin() ? 'admin_print_footer_scripts' : 'wp_print_footer_scripts', array( __CLASS__, 'callback_inlined_scripts' ) );

		// TinyMCE editor
		add_filter( 'mce_external_plugins', array( __CLASS__, 'callback_register_tinymce_plugin' ) );
		add_filter( 'mce_buttons', array( __CLASS__, 'callback_register_tinymce_buttons' ) );

		// QuickTags editor
		add_action( 'admin_print_footer_scripts', array( __CLASS__, 'callback_register_quick_tags_buttons' ) );
	}


	public static function callback_inlined_scripts() {
		$model = RPBChessboardHelperLoader::load_model( 'Common/Compatibility' );
		RPBChessboardHelperLoader::print_template( 'Localization', $model );
	}


	public static function callback_register_tinymce_plugin( $plugins ) {
		$plugins['RPBChessboard'] = RPBCHESSBOARD_URL . 'js/tinymce' . self::get_js_file_extension();
		return $plugins;
	}

	public static function callback_register_tinymce_buttons( $buttons ) {
		array_push( $buttons, 'rpb-chessboard' );
		return $buttons;
	}


	public static function callback_register_quick_tags_buttons() {
		$url = RPBCHESSBOARD_URL . 'js/quicktags' . self::get_js_file_extension();
		echo '<script type="text/javascript" src="' . $url . '"></script>';
	}


	/**
	 * Return the extension to use for the included JS files.
	 *
	 * @return string
	 */
	private static function get_js_file_extension() {
		return WP_DEBUG ? '.js' : '.min.js';
	}


	/**
	 * Determine the language code to use to configure a given JavaScript library, and enqueue the required file.
	 *
	 * @param string $handle Handle of the file to localize.
	 * @param string $relative_file_path_template Where the localized files should be searched.
	 * @param string $version Version the library.
	 * @return string Handle of the localized file a suitable translation has been found, original handle otherwise.
	 */
	private static function localize_javascript_lib( $handle, $relative_file_path_template, $version ) {
		foreach ( self::get_blog_lang_codes() as $lang_code ) {
			// Does the translation script file exist for the current language code?
			$relative_file_path = sprintf( $relative_file_path_template, $lang_code );
			if ( ! file_exists( RPBCHESSBOARD_ABSPATH . $relative_file_path ) ) {
				continue;
			}

			// If it exists, register it, and return a handle pointing to the localization file.
			$localized_handle = $handle . '-localized';
			wp_register_script( $localized_handle, RPBCHESSBOARD_URL . $relative_file_path, array( $handle ), $version );
			return $localized_handle;
		}

		// Otherwise, if no translation file exists, return the handle of the original library.
		return $handle;
	}


	/**
	 * Return an array of language codes that may be relevant for the blog.
	 *
	 * @return array
	 */
	private static function get_blog_lang_codes() {
		if ( ! isset( self::$blog_lang_codes ) ) {
			$main_language        = str_replace( '_', '-', strtolower( get_locale() ) );
			self::$blog_lang_codes = array( $main_language );

			if ( preg_match( '/([a-z]+)\\-([a-z]+)/', $main_language, $m ) ) {
				self::$blog_lang_codes[] = $m[1];
			}
		}
		return self::$blog_lang_codes;
	}


	/**
	 * Blog language codes.
	 */
	private static $blog_lang_codes;
}
