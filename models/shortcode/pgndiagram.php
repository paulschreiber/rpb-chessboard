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


require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/shortcode.php';


/**
 * Model associated to the [pgndiagram] shortcode.
 */
class RPBChessboardModelShortcodePGNDiagram extends RPBChessboardAbstractModelShortcode {

	private $diagram_options;
	private $diagram_options_as_string;


	/**
	 * Options specific to the current diagram, that may override the settings defined either
	 * at the [pgn][/pgn] shortcode level or at the global level.
	 *
	 * @return array
	 */
	public function getDiagramOptions() {
		if ( ! isset( $this->diagram_options ) ) {
			$this->diagram_options = array();
			$atts                 = $this->getAttributes();

			// Orientation
			$value = isset( $atts['flip'] ) ? RPBChessboardHelperValidation::validate_boolean( $atts['flip'] ) : null;
			if ( isset( $value ) ) {
				$this->diagram_options['flip'] = $value;
			}

			// Square size
			$value = isset( $atts['square_size'] ) ? RPBChessboardHelperValidation::validate_square_size( $atts['square_size'] ) : null;
			if ( isset( $value ) ) {
				$this->diagram_options['squareSize'] = $value;
			}

			// Show coordinates
			$value = isset( $atts['show_coordinates'] ) ? RPBChessboardHelperValidation::validate_boolean( $atts['show_coordinates'] ) : null;
			if ( isset( $value ) ) {
				$this->diagram_options['showCoordinates'] = $value;
			}

			// Colorset
			$value = isset( $atts['colorset'] ) ? RPBChessboardHelperValidation::validate_set_code( $atts['colorset'] ) : null;
			if ( isset( $value ) ) {
				$this->diagram_options['colorset'] = $value;
			}

			// Pieceset
			$value = isset( $atts['pieceset'] ) ? RPBChessboardHelperValidation::validate_set_code( $atts['pieceset'] ) : null;
			if ( isset( $value ) ) {
				$this->diagram_options['pieceset'] = $value;
			}
		}
		return $this->diagram_options;
	}


	/**
	 * Diagram specific settings, as a string ready to be inlined in its PGN text comment.
	 *
	 * @return string
	 */
	public function getDiagramOptionsAsString() {
		if ( ! isset( $this->diagram_options_as_string ) ) {
			$this->diagram_options_as_string = json_encode( $this->getDiagramOptions() );
			$this->diagram_options_as_string = preg_replace( '/{|}|\\\\/', '\\\\$0', $this->diagram_options_as_string );
		}
		return $this->diagram_options_as_string;
	}
}
