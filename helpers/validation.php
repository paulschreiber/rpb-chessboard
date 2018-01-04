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
 * Validation functions.
 */
abstract class RPBChessboardHelperValidation {

	/**
	 * Minimum square size of the chessboard widgets.
	 */
	const MINIMUM_SQUARE_SIZE = 12;


	/**
	 * Maximum square size of the chessboard widgets.
	 */
	const MAXIMUM_SQUARE_SIZE = 64;


	/**
	 * Maximum value for the animation speed parameter.
	 */
	const MAXIMUM_ANIMATION_SPEED = 1000;


	/**
	 * The animation speed parameter must be a multiple of this value.
	 */
	const STEP_ANIMATION_SPEED = 100;


	/**
	 * Validate a chessboard widget square size parameter.
	 *
	 * @param mixed $value
	 * @return int May be null is the value is not valid.
	 */
	public static function validate_square_size( $value ) {
		return self::validate_integer( $value, self::MINIMUM_SQUARE_SIZE, self::MAXIMUM_SQUARE_SIZE );
	}


	/**
	 * Validate a color (defined as a hexadecimal string).
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validate_color( $value ) {
		if ( is_string( $value ) ) {
			$value = strtolower( $value );
			if ( preg_match( '/^#[0-9a-f]{6}$/', $value ) ) {
				return $value;
			}
		}
		return null;
	}


	/**
	 * Validate colorset/pieceset parameter.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validate_set_code( $value ) {
		if ( is_string( $value ) ) {
			$value = strtolower( $value );
			if ( preg_match( '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $value ) ) {
				return $value;
			}
		}
		return null;
	}


	/**
	 * Validate colorset/pieceset parameter list.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validate_set_code_list( $value ) {
		if ( is_string( $value ) ) {
			$value = strtolower( $value );
			if ( $value === '' ) {
				return array();
			} elseif ( preg_match( '/^[a-z0-9]+(?:-[a-z0-9]+)*(?:\|[a-z0-9]+(?:-[a-z0-9]+)*)*$/', $value ) ) {
				return explode( '|', $value );
			}
		}
		return null;
	}


	/**
	 * Validate a diagram alignment parameter.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validate_diagram_alignment( $value ) {
		return ( $value === 'center' || $value === 'floatLeft' || $value === 'floatRight' ) ? $value : null;
	}


	/**
	 * Validate a piece symbol mode parameter.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validate_piece_symbols( $value ) {
		if ( $value === 'native' || $value === 'localized' || $value === 'figurines' ) {
			return $value;
		} elseif ( is_string( $value ) && preg_match( '/^\([a-zA-Z]{6}\)$/', $value ) ) {
			return strtoupper( $value );
		} else {
			return null;
		}
	}


	/**
	 * Validate a single piece symbol.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validate_piece_symbol( $value ) {
		return is_string( $value ) && preg_match( '/^[a-zA-Z]$/', $value ) ? strtoupper( $value ) : null;
	}


	/**
	 * Validate a navigation board position parameter.
	 *
	 * @param mixed $value
	 * @return string May be null is the value is not valid.
	 */
	public static function validate_navigation_board( $value ) {
		return ( $value === 'none' || $value === 'frame' || $value === 'above' || $value === 'below'
			|| $value === 'floatLeft' || $value === 'floatRight' || $value === 'scrollLeft' || $value === 'scrollRight' ) ? $value : null;
	}


	/**
	 * Validate a set of small-screen mode specifications.
	 */
	public static function validate_small_screen_modes( $value ) {
		if ( ! is_string( $value ) ) {
			return null;
		}
		$res = array();

		// Split the input into a list of comma-separated tokens
		$modes = explode( ',', $value );
		foreach ( $modes as $mode ) {

			// Split each mode-encoding token into 3 colon-separated sub-tokens
			$tokens = explode( ':', $mode );
			if ( count( $tokens ) !== 3 ) {
				continue;
			}

			// Validate each sub-token
			$screen_width     = self::validate_integer( $tokens[0], 1 );
			$square_size      = self::validate_square_size( $tokens[1] );
			$hide_coordinates = self::validate_boolean( $tokens[2] );
			if ( isset( $screen_width ) && isset( $square_size ) && isset( $hide_coordinates ) ) {
				$res[ $screen_width ] = (object) array(
					'squareSize'      => $square_size,
					'hideCoordinates' => $hide_coordinates,
				);
			}
		}

		// Sort by screen-width and return the result.
		ksort( $res );
		return $res;
	}


	/**
	 * Validate a chessboard widget animation speed parameter.
	 *
	 * @param mixed $value
	 * @return int May be null is the value is not valid.
	 */
	public static function validate_animation_speed( $value ) {
		$value = self::validate_integer( $value, 0, self::MAXIMUM_ANIMATION_SPEED );
		if ( isset( $value ) ) {
			$value = round( $value / self::STEP_ANIMATION_SPEED ) * self::STEP_ANIMATION_SPEED;
		}
		return $value;
	}


	/**
	 * Validate an integer.
	 *
	 * @param mixed $value
	 * @param int $min Minimum value (optional).
	 * @param int $max Maximum value (optional).
	 * @return int May be null is the value is not valid.
	 */
	public static function validate_integer( $value, $min = null, $max = null ) {
		$value = filter_var( $value, FILTER_VALIDATE_INT );
		return $value === false ? null : max( $max === null ? $value : min( $value, $max ), $min );
	}


	/**
	 * Validate a boolean.
	 *
	 * @param mixed $value
	 * @return boolean May be null is the value is not valid.
	 */
	public static function validate_boolean( $value ) {
		return ( $value === null || $value === '' ) ? null : filter_var( $value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
	}


	/**
	 * Validate a boolean specified as an integer value.
	 *
	 * @param mixed $value
	 * @return boolean May be null is the value is not valid.
	 */
	public static function validate_boolean_from_int( $value ) {
		$value = filter_var( $value, FILTER_VALIDATE_INT );
		if ( $value === 0 ) {
			return false;
		} elseif ( $value === 1 ) {
			return true;
		} else {
			return null;
		}
	}
}
