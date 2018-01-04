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
 * Register the plugin shortcodes.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBChessboardShortcodes {

	public static function register() {
		// Compatibility information -> describe which shortcode should be used to insert FEN diagrams,
		// which one to insert PGN games, etc...
		$compatibility               = RPBChessboardHelperLoader::load_model( 'Common/Compatibility' );
		$fen_shortcode                = $compatibility->getFENShortcode();
		$pgn_shortcode                = $compatibility->getPGNShortcode();
		self::$no_texturize_shortcodes = array( $fen_shortcode, $pgn_shortcode );
		self::$low_level_shortcodes    = array( $pgn_shortcode );

		// Register the shortcodes
		add_shortcode( $fen_shortcode, array( __CLASS__, 'callback_shortcode_fen' ) );
		add_shortcode( $pgn_shortcode, array( __CLASS__, 'callback_shortcode_pgn' ) );
		add_shortcode( 'pgndiagram', array( __CLASS__, 'callback_shortcode_pgn_diagram' ) );

		// Register the no-texturize shortcodes
		add_filter( 'no_texturize_shortcodes', array( __CLASS__, 'register_no_texturize_shortcodes' ) );

		// A high-priority filter is required to prevent the WP engine to perform some nasty operations
		// (e.g. wptexturize, wpautop, etc...) on the text enclosed by the shortcodes.
		//
		// The priority level 8 what is used by the WP engine to process the special [embed] shortcode.
		// As the same type of low-level operation is performed here, using this priority level seems to be a good choice.
		// However, having "official" guidelines or core methods to achieve this would be desirable.
		//
		add_filter( 'the_content', array( __CLASS__, 'preprocess_low_level_shortcodes' ), 8 );
		add_filter( 'comment_text', array( __CLASS__, 'preprocess_low_level_shortcodes' ), 8 );
	}


	public static function callback_shortcode_fen( $atts, $content ) {
		return self::run_shortcode( 'FEN', false, $atts, $content ); }
	public static function callback_shortcode_pgn( $atts, $content ) {
		return self::run_shortcode( 'PGN', true, $atts, $content ); }
	public static function callback_shortcode_pgn_diagram( $atts, $content ) {
		return self::run_shortcode( 'PGNDiagram', false, $atts, $content ); }


	/**
	 * Process a shortcode.
	 *
	 * @param string $shortcode_name
	 * @param boolean $low_level
	 * @param array $atts
	 * @param string $content
	 * @return string
	 */
	private static function run_shortcode( $shortcode_name, $low_level, $atts, $content ) {
		// The content of low-level shortcodes is supposed to have been saved in `self::$low_level_shortcode_content`.
		if ( $low_level && isset( $content ) && isset( self::$low_level_shortcode_content[ $content ] ) ) {
			$content = self::$low_level_shortcode_content[ $content ];
		}

		// Print the shortcode.
		$model = RPBChessboardHelperLoader::load_model( 'Shortcode/' . $shortcode_name, $atts, $content );
		return RPBChessboardHelperLoader::print_template_off_screen( 'Shortcode/' . $shortcode_name, $model );
	}


	/**
	 * Register the no-texturize shortcodes defined by the plugin with WP engine.
	 *
	 * @param array $shortcodes Global list of no-texturize shortcodes.
	 * @return array
	 */
	public static function register_no_texturize_shortcodes( $shortcodes ) {
		return array_merge( $shortcodes, self::$no_texturize_shortcodes );
	}


	/**
	 * Replace the content of the low-level shortcodes with their respective MD5 digest,
	 * saving the original content in the associative array `self::$low_level_shortcode_content`.
	 *
	 * @param string $text
	 * @return $text
	 */
	public static function preprocess_low_level_shortcodes( $text ) {
		$tag_mask = implode( '|', self::$low_level_shortcodes );
		$pattern = '/\\[(\\[?)(' . $tag_mask . ')\\b([^\\]]*)\\](.*?)\\[\\/\\2\\](\\]?)/s';
		return preg_replace_callback( $pattern, array( __CLASS__, 'preprocess_low_level_shortcode' ), $text );
	}


	/**
	 * Replacement function for the low-level shortcodes.
	 *
	 * @param array $m Regular expression match array.
	 * @return string
	 */
	private static function preprocess_low_level_shortcode( $m ) {
		// Allow the [[foo]...[/foo]] syntax for escaping a tag.
		if ( $m[1] === '[' && $m[5] === ']' ) {
			return $m[0];
		}

		// General case: save the shortcode content, and replace it with its MD5 digest.
		$digest                                    = md5( $m[4] );
		self::$low_level_shortcode_content[ $digest ] = $m[4];
		return '[' . $m[2] . $m[3] . ']' . $digest . '[/' . $m[2] . ']';
	}


	/**
	 * Shortcodes for which the "texturize" filter performed by the WP engine on post content
	 * must be disabled.
	 */
	private static $no_texturize_shortcodes;


	/**
	 * Shortcodes that need their content to be processed in a low-level manner.
	 */
	private static $low_level_shortcodes;


	/**
	 * Saved content of the low-level shortcodes, indexed with their respective MD5 digest.
	 */
	private static $low_level_shortcode_content = array();
}
