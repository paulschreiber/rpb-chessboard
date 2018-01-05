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
 * Model associated to the [fen][/fen] shortcode.
 */
class RPBChessboardModelShortcodeFEN extends RPBChessboardAbstractModelShortcode {

	private $widget_args;
	private $diagramAlignment;


	public function __construct( $atts, $content ) {
		parent::__construct( $atts, $content );
		$this->loadDelegateModel( 'Common/DefaultOptions' );
	}


	/**
	 * Return the arguments to pass to the JS chessboard widget.
	 *
	 * @return array
	 */
	public function getWidgetArgs() {
		if ( ! isset( $this->widget_args ) ) {
			$this->widget_args = array( 'position' => $this->getContent() );
			$atts             = $this->getAttributes();

			// Square markers
			if ( isset( $atts['csl'] ) && is_string( $atts['csl'] ) ) {
				$this->widget_args['squareMarkers'] = $atts['csl'];
			}

			// Arrow markers
			if ( isset( $atts['cal'] ) && is_string( $atts['cal'] ) ) {
				$this->widget_args['arrowMarkers'] = $atts['cal'];
			}

			// Orientation
			$value = isset( $atts['flip'] ) ? RPBChessboardHelperValidation::validate_boolean( $atts['flip'] ) : null;
			if ( isset( $value ) ) {
				$this->widget_args['flip'] = $value;
			}

			// Square size
			$value                          = isset( $atts['square_size'] ) ? RPBChessboardHelperValidation::validate_square_size( $atts['square_size'] ) : null;
			$this->widget_args['squareSize'] = isset( $value ) ? $value : $this->getDefaultSquareSize();

			// Show coordinates
			$value                               = isset( $atts['show_coordinates'] ) ? RPBChessboardHelperValidation::validate_boolean( $atts['show_coordinates'] ) : null;
			$this->widget_args['showCoordinates'] = isset( $value ) ? $value : $this->getDefaultShowCoordinates();

			// Colorset
			$value                        = isset( $atts['colorset'] ) ? RPBChessboardHelperValidation::validate_set_code( $atts['colorset'] ) : null;
			$this->widget_args['colorset'] = isset( $value ) ? $value : $this->getDefaultColorset();

			// Pieceset
			$value                        = isset( $atts['pieceset'] ) ? RPBChessboardHelperValidation::validate_set_code( $atts['pieceset'] ) : null;
			$this->widget_args['pieceset'] = isset( $value ) ? $value : $this->getDefaultPieceset();
		}
		return $this->widget_args;
	}


	public function getDiagramAlignment() {
		if ( ! isset( $this->diagramAlignment ) ) {
			$atts                   = $this->getAttributes();
			$value                  = isset( $atts['align'] ) ? RPBChessboardHelperValidation::validate_diagram_alignment( $atts['align'] ) : null;
			$this->diagramAlignment = isset( $value ) ? $value : $this->getDefaultDiagramAlignment();
		}
		return $this->diagramAlignment;
	}


	/**
	 * Ensure that the FEN string is trimmed.
	 */
	protected function filterShortcodeContent( $content ) {
		$regex = '\s|<br *\/>';
		$regex = "(?:$regex)*";
		return preg_replace( "/^$regex|$regex\$/i", '', $content );
	}
}
