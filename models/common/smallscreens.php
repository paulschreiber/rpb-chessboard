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


require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/abstractmodel.php';
require_once RPBCHESSBOARD_ABSPATH . 'helpers/validation.php';


/**
 * Specific settings to deal with small-screen devices (such as smartphones).
 */
class RPBChessboardModelCommonSmallScreens extends RPBChessboardAbstractModel {

	private static $smallScreenCompatibility;
	private static $smallScreenModes;


	public function __construct() {
		parent::__construct();
		$this->registerDelegatableMethods( 'getSmallScreenCompatibility', 'getSmallScreenModes' );
	}


	/**
	 * Whether the small-screen compatibility mode is enabled or not.
	 *
	 * @return boolean
	 */
	public function getSmallScreenCompatibility() {
		if ( ! isset( self::$smallScreenCompatibility ) ) {
			$value                          = RPBChessboardHelperValidation::validate_boolean_from_int( get_option( 'rpbchessboard_smallScreenCompatibility' ) );
			self::$smallScreenCompatibility = isset( $value ) ? $value : true;
		}
		return self::$smallScreenCompatibility;
	}


	/**
	 * Return the small-screen modes.
	 *
	 * @return array
	 */
	public function getSmallScreenModes() {
		if ( ! isset( self::$smallScreenModes ) ) {
			self::loadSmallScreenModes();
		}
		return self::$smallScreenModes;
	}


	/**
	 * Load the small-screen mode specifications.
	 */
	private static function loadSmallScreenModes() {

		// Load the raw data
		$data = RPBChessboardHelperValidation::validate_small_screen_modes( get_option( 'rpbchessboard_smallScreenModes' ) );
		$data = isset( $data ) ? $data : array(
			240 => (object) array(
				'squareSize'      => 18,
				'hideCoordinates' => true,
			),
			320 => (object) array(
				'squareSize'      => 24,
				'hideCoordinates' => true,
			),
			480 => (object) array(
				'squareSize'      => 32,
				'hideCoordinates' => false,
			),
			768 => (object) array(
				'squareSize'      => 56,
				'hideCoordinates' => false,
			),
		);

		// Format the mode entries
		self::$smallScreenModes   = array();
		$previousScreenWidthBound = 0;
		foreach ( $data as $screen_width_bound => $mode ) {
			$mode->minScreenWidth = $previousScreenWidthBound;
			$mode->maxScreenWidth = $screen_width_bound;
			array_push( self::$smallScreenModes, $mode );
			$previousScreenWidthBound = $screen_width_bound;
		}
	}


	/**
	 * Whether the square size must be customized in the given small-screen mode or not.
	 *
	 * @return boolean
	 */
	public function hasSmallScreenSizeSquareSizeSection( $mode ) {
		return $mode->squareSize < RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE;
	}


	/**
	 * Selector to use to introduce the specific CSS instructions for the given small-screen mode.
	 *
	 * @return string
	 */
	public function getSmallScreenModeMainSelector( $mode ) {
		$res = '@media all';
		if ( $mode->minScreenWidth > 0 ) {
			$res .= ' and (min-width:' . ( $mode->minScreenWidth + 1 ) . 'px)';
		}
		$res .= ' and (max-width:' . $mode->maxScreenWidth . 'px)';
		return $res;
	}


	/**
	 * Selector to use to introduce the specific CSS instructions to customize the square size in the given small-screen mode.
	 *
	 * @return string
	 */
	public function getSmallScreenModeSquareSizeSelector( $mode ) {
		$selectors = array();
		for ( $size = $mode->squareSize + 1; $size <= RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE; ++$size ) {
			array_push( $selectors, '.rpbui-chessboard-size' . $size . ' .rpbui-chessboard-sized' );
		}
		return implode( ',', $selectors );
	}


	/**
	 * Selector to use to introduce the specific CSS instructions to customize the annotation layer in the given small-screen mode.
	 *
	 * @return string
	 */
	public function getSmallScreenModeAnnotationLayerSelector( $mode ) {
		$selectors = array();
		for ( $size = $mode->squareSize + 1; $size <= RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE; ++$size ) {
			array_push( $selectors, '.rpbui-chessboard-size' . $size . ' .rpbui-chessboard-annotations' );
		}
		return implode( ',', $selectors );
	}


	/**
	 * Return the background-position x-offset to use for sprites having size `$square_size`.
	 *
	 * @param int $square_size
	 * @return int
	 */
	public function getBackgroundPositionXForSquareSize( $square_size ) {
		if ( $square_size <= 32 ) {
			// delta_x = - sum (k = $square_size + 1 to 32) { k }
			return - ( $square_size + 33 ) * ( 32 - $square_size ) / 2;
		} else {
			// delta_x = - sum (k = 33 to $square_size - 1) { k }
			return - ( $square_size + 32 ) * ( $square_size - 33 ) / 2;
		}
	}


	/**
	 * Return the background-position y-offset to use for sprites having size `$square_size`.
	 *
	 * @param int $square_size
	 * @return int
	 */
	public function getBackgroundPositionYForSquareSize( $square_size ) {
		return $square_size <= 32 ? $square_size - 65 : 0;
	}


	/**
	 * Return the height and width to use for the annotation layer when using the given square size.
	 *
	 * @param int $square_size
	 * @return int
	 */
	public function getHeightWidthForAnnotationLayer( $square_size ) {
		return $square_size * 8;
	}


	/**
	 * Return the x-offset (from right) to use for the annotation layer when using the given square size.
	 *
	 * @param int $square_size
	 * @return int
	 */
	public function getRightForAnnotationLayer( $square_size ) {
		return $square_size + 8;
	}
}
