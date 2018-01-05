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
 * Model associated to the [pgn][/pgn] shortcode.
 */
class RPBChessboardModelShortcodePGN extends RPBChessboardAbstractModelShortcode {

	private $loadedFromExternalPGNFile;
	private $externalPGNFile;
	private $widget_args;


	public function __construct( $atts, $content ) {
		parent::__construct( $atts, $content );
		$this->loadDelegateModel( 'Common/DefaultOptions' );
		$this->loadDelegateModel( 'Common/Compatibility' );
	}


	/**
	 * Return whether the PGN data is loaded from an external file or not.
	 *
	 * @return boolean
	 */
	public function isLoadedFromExternalPGNFile() {
		$this->initializeExternalPGNFile();
		return $this->loadedFromExternalPGNFile;
	}


	/**
	 * Return the URL of the external PGN file to load.
	 *
	 * @return string
	 */
	public function getExternalPGNFile() {
		$this->initializeExternalPGNFile();
		return $this->externalPGNFile;
	}


	private function initializeExternalPGNFile() {
		if ( isset( $this->loadedFromExternalPGNFile ) ) {
			return;
		}

		$atts = $this->getAttributes();

		if ( isset( $atts['url'] ) && $atts['url'] !== '' ) {
			$this->externalPGNFile           = $atts['url'];
			$this->loadedFromExternalPGNFile = true;
		} else {
			$this->externalPGNFile           = '';
			$this->loadedFromExternalPGNFile = false;
		}
	}


	/**
	 * Return the arguments to pass to the JS chessboard widget.
	 *
	 * @return array
	 */
	public function getWidgetArgs() {
		if ( ! isset( $this->widget_args ) ) {

			$this->widget_args = array();

			if ( $this->isLoadedFromExternalPGNFile() ) {
				$this->widget_args['url'] = $this->getExternalPGNFile();
			} else {
				$this->widget_args['pgn'] = $this->getContent();
			}

			$atts              = $this->getAttributes();
			$chessboardOptions = array();

			// Game index
			$value = isset( $atts['game'] ) ? RPBChessboardHelperValidation::validate_integer( $atts['game'], 0 ) : null;
			if ( isset( $value ) ) {
				$this->widget_args['gameIndex'] = $value;
			}

			// Orientation
			$value = isset( $atts['flip'] ) ? RPBChessboardHelperValidation::validate_boolean( $atts['flip'] ) : null;
			if ( isset( $value ) ) {
				$chessboardOptions['flip'] = $value;
			}

			// Square size
			$value                           = isset( $atts['square_size'] ) ? RPBChessboardHelperValidation::validate_square_size( $atts['square_size'] ) : null;
			$chessboardOptions['squareSize'] = isset( $value ) ? $value : $this->getDefaultSquareSize();

			// Show coordinates
			$value                                = isset( $atts['show_coordinates'] ) ? RPBChessboardHelperValidation::validate_boolean( $atts['show_coordinates'] ) : null;
			$chessboardOptions['showCoordinates'] = isset( $value ) ? $value : $this->getDefaultShowCoordinates();

			// Colorset
			$value                         = isset( $atts['colorset'] ) ? RPBChessboardHelperValidation::validate_set_code( $atts['colorset'] ) : null;
			$chessboardOptions['colorset'] = isset( $value ) ? $value : $this->getDefaultColorset();

			// Pieceset
			$value                         = isset( $atts['pieceset'] ) ? RPBChessboardHelperValidation::validate_set_code( $atts['pieceset'] ) : null;
			$chessboardOptions['pieceset'] = isset( $value ) ? $value : $this->getDefaultPieceset();

			// Animation speed
			$value                               = isset( $atts['animation_speed'] ) ? RPBChessboardHelperValidation::validate_animation_speed( $atts['animation_speed'] ) : null;
			$chessboardOptions['animationSpeed'] = isset( $value ) ? $value : $this->getDefaultAnimationSpeed();

			// Move arrow
			$value                              = isset( $atts['show_move_arrow'] ) ? RPBChessboardHelperValidation::validate_boolean( $atts['show_move_arrow'] ) : null;
			$chessboardOptions['showMoveArrow'] = isset( $value ) ? $value : $this->getDefaultShowMoveArrow();

			// Piece symbols
			$value                            = isset( $atts['piece_symbols'] ) ? RPBChessboardHelperValidation::validate_piece_symbols( $atts['piece_symbols'] ) : null;
			$this->widget_args['pieceSymbols'] = isset( $value ) ? $value : $this->getDefaultPieceSymbols();

			// Navigation board
			$value                               = isset( $atts['navigation_board'] ) ? RPBChessboardHelperValidation::validate_navigation_board( $atts['navigation_board'] ) : null;
			$this->widget_args['navigationBoard'] = isset( $value ) ? $value : $this->getDefaultNavigationBoard();

			// Navigation toolbar
			$value                                  = isset( $atts['show_flip_button'] ) ? RPBChessboardHelperValidation::validate_boolean( $atts['show_flip_button'] ) : null;
			$this->widget_args['showFlipButton']     = isset( $value ) ? $value : $this->getDefaultShowFlipButton();
			$value                                  = isset( $atts['show_download_button'] ) ? RPBChessboardHelperValidation::validate_boolean( $atts['show_download_button'] ) : null;
			$this->widget_args['showDownloadButton'] = isset( $value ) ? $value : $this->getDefaultShowDownloadButton();

			// Use the same aspect parameters for the navigation board and the text comment diagrams.
			$this->widget_args['navigationBoardOptions'] = $chessboardOptions;
			$this->widget_args['diagramOptions']         = $chessboardOptions;
		}
		return $this->widget_args;
	}


	/**
	 * Apply some auto-format traitments to the text comments.
	 *
	 * These traitments used to be performed by the WP engine on the whole post/page content
	 * (see wp-includes/default-filters.php, filter `'the_content'`).
	 * However, the [pgn][/pgn] shortcode is processed in a low-level manner,
	 * therefore its content is preserved from these traitments, that must be applied
	 * to each text comment individually.
	 */
	protected function filterShortcodeContent( $content ) {
		return preg_replace_callback( '/{((?:\\\\\\\\|\\\\{|\\\\}|[^{}])*)}/', array( __CLASS__, 'processTextComment' ), trim( $content ) );
	}


	/**
	 * Callback called to process the matched comments between the [pgn][/pgn] tags.
	 */
	private static function processTextComment( $m ) {
		$comment = convert_chars( convert_smilies( wptexturize( $m[1] ) ) );
		return '{' . do_shortcode( $comment ) . '}';
	}
}
