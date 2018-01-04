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
 * Helper functions to manage cache.
 */
abstract class RPBChessboardHelperCache {

	/**
	 * Return the URL of the given cached file.
	 *
	 * @param string $file_name File name, relative to the cache root.
	 * @return string
	 */
	public static function get_url( $file_name ) {
		return RPBCHESSBOARD_URL . 'cache/' . $file_name;
	}


	/**
	 * Return the version of the given cached file.
	 *
	 * @param string $file_name File name, relative to the cache root.
	 * @return string
	 */
	public static function get_version( $file_name ) {
		return get_option( 'rpbchessboard_cache_' . $file_name, '0' );
	}


	/**
	 * Check whether the given file exists in the cache or not.
	 *
	 * @param string $file_name File name, relative to the cache root.
	 * @return boolean
	 */
	public static function exists( $file_name ) {
		return file_exists( self::get_full_file_name( $file_name ) );
	}


	/**
	 * Write a file into the cache. Nothing happens if the file already exists.
	 *
	 * @param string $file_name File name, relative to the cache root.
	 * @param string $template_name Template to use to generate the file, if necessary.
	 * @param string $model_name Model to use to generate the file, if necessary.
	 */
	public static function ensure_exists( $file_name, $template_name, $model_name ) {
		$full_file_name = self::get_full_file_name( $file_name );
		if ( file_exists( $full_file_name ) ) {
			return; }

		$model = RPBChessboardHelperLoader::load_model( $model_name );
		$text  = RPBChessboardHelperLoader::print_template_off_screen( $template_name, $model );

		$dir_name = dirname( $full_file_name );
		if ( ! file_exists( $dir_name ) ) {
			mkdir( $dir_name, 0777, true );
		}
		file_put_contents( $full_file_name, $text );
		update_option( 'rpbchessboard_cache_' . $file_name, uniqid() );
	}


	/**
	 * Remove the given file from the cache.
	 *
	 * @param string $file_name File name, relative to the cache root.
	 */
	public static function remove( $file_name ) {
		$full_file_name = self::get_full_file_name( $file_name );
		if ( file_exists( $full_file_name ) ) {
			unlink( $full_file_name );
		}
	}


	/**
	 * Return the full-path to a file in the cache directory.
	 *
	 * @param string $file_name File name, relative to the cache root.
	 */
	private static function get_full_file_name( $file_name ) {
		return RPBCHESSBOARD_ABSPATH . 'cache/' . $file_name;
	}
}
