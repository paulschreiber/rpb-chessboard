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
 * Helper functions for dynamic class loading.
 */
abstract class RPBChessboardHelperLoader {

	/**
	 * Load the model corresponding to the given model name.
	 *
	 * @param string $model_name Name of the model.
	 * @param mixed ... Arguments to pass to the model (optional).
	 * @return object New instance of the model.
	 */
	public static function load_model( $model_name ) {
		$file_name  = strtolower( $model_name );
		$class_name = 'RPBChessboardModel' . str_replace( '/', '', $model_name );
		require_once RPBCHESSBOARD_ABSPATH . 'models/' . $file_name . '.php';
		if ( func_num_args() === 1 ) {
			return new $class_name();
		} else {
			$args  = func_get_args();
			$clazz = new ReflectionClass( $class_name );
			return $clazz->newInstanceArgs( array_slice( $args, 1 ) );
		}
	}


	/**
	 * Print the given template to the current output.
	 *
	 * @param string $template_name
	 * @param object $model
	 * @param array $args
	 */
	public static function print_template( $template_name, $model, $args = null ) {

		if ( isset( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( $key === 'model' || $key === 'template_name' || $key === 'file_name' ) {
					continue;
				}
				$$key = $value;
			}
		}

		$file_name = RPBCHESSBOARD_ABSPATH . 'templates/' . strtolower( $template_name );
		include $file_name . ( is_dir( $file_name ) ? '/main.php' : '.php' );
	}


	/**
	 * Print the given template to a string.
	 *
	 * @param string $template_name
	 * @param object $model
	 * @param array $args
	 * @return string
	 */
	public static function print_template_off_screen( $template_name, $model, $args = null ) {
		ob_start();
		self::print_template( $template_name, $model, $args );
		return ob_get_clean();
	}
}
