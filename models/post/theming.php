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
 * Abstract class for theming request processing.
 */
abstract class RPBChessboardModelPostTheming extends RPBChessboardAbstractModel {

	private $customSetCodes;


	public function __construct() {
		parent::__construct();
		$this->loadDelegateModel( 'Common/DefaultOptionsEx' );
	}


	public function add() {
		$set_code = $this->getNewSetCode();

		// Update attributes and list of custom set-codes.
		if ( ! ( $this->processLabel( $set_code ) && $this->processAttributes( $set_code ) ) ) {
			return null;
		}
		$this->updateCustomSetCodes( array_merge( $this->getCustomSetCodes(), array( $set_code ) ) );

		// Force cache refresh.
		self::invalidateCache();

		return $this->getCreationSuccessMessage();
	}


	public function edit() {
		$set_code = $this->getSetCode();
		if ( ! isset( $set_code ) ) {
			return null;
		}

		$this->processLabel( $set_code );
		$this->processAttributes( $set_code );
		self::invalidateCache();

		return $this->getEditionSuccessMessage();
	}


	public function delete() {
		$set_code = $this->getSetCode();
		if ( ! isset( $set_code ) ) {
			return null;
		}

		// Remove the set-code from the database.
		$this->updateCustomSetCodes( array_diff( $this->getCustomSetCodes(), array( $set_code ) ) );

		// Reset default set-code if it corresponds to the set-code being deleted.
		if ( $set_code === $this->getDefaultSetCode() ) {
			delete_option( 'rpbchessboard_' . $this->getManagedSetCode() );
		}

		// Cleanup the database and the cache.
		delete_option( 'rpbchessboard_custom_' . $this->getManagedSetCode() . '_label_' . $set_code );
		delete_option( 'rpbchessboard_custom_' . $this->getManagedSetCode() . '_attributes_' . $set_code );
		self::invalidateCache();

		return $this->getDeletionSuccessMessage();
	}


	private function processLabel( $set_code ) {
		if ( isset( $_POST['label'] ) ) {
			update_option( 'rpbchessboard_custom_' . $this->getManagedSetCode() . '_label_' . $set_code, $_POST['label'] );
			return true;
		}
		return false;
	}


	/**
	 * Set the attributes of the given set-code according to the current POST data.
	 *
	 * @return `true` if the attributes are successfully set, `false` otherwise.
	 */
	abstract protected function processAttributes( $set_code);


	private function updateCustomSetCodes( $set_codes ) {
		update_option( 'rpbchessboard_custom_' . $this->getManagedSetCode() . 's', implode( '|', $set_codes ) );
	}


	private static function invalidateCache() {
		RPBChessboardHelperCache::remove( 'theming.css' );
	}


	/**
	 * Check whether the given set-code represents an existing custom theming set or not.
	 */
	private function isCustomSetCode( $set_code ) {
		return in_array( $set_code, $this->getCustomSetCodes() );
	}


	/**
	 * Retrieve the list of existing custom set-codes.
	 */
	private function getCustomSetCodes() {
		if ( ! isset( $this->customSetCodes ) ) {
			$result               = RPBChessboardHelperValidation::validate_set_code_list( get_option( 'rpbchessboard_custom_' . $this->getManagedSetCode() . 's' ) );
			$this->customSetCodes = isset( $result ) ? $result : array();
		}
		return $this->customSetCodes;
	}


	/**
	 * Retrieve the set-code used by default, if any.
	 */
	private function getDefaultSetCode() {
		return RPBChessboardHelperValidation::validate_set_code( get_option( 'rpbchessboard_' . $this->getManagedSetCode() ) );
	}


	/**
	 * Retrieve the set-code concerned by this operation and make sure that it corresponds to a custom theming set.
	 */
	private function getSetCode() {
		$managedSetCode = $this->getManagedSetCode();
		$set_code        = isset( $_POST[ $managedSetCode ] ) ? RPBChessboardHelperValidation::validate_set_code( $_POST[ $managedSetCode ] ) : null;
		if ( isset( $set_code ) && ! $this->isCustomSetCode( $set_code ) ) {
			return null;
		}
		return $set_code;
	}


	/**
	 * Retrieve (and sanitize) the set-code to use to create the new theming set.
	 */
	private function getNewSetCode() {
		$managedSetCode = $this->getManagedSetCode();
		$set_code        = isset( $_POST[ $managedSetCode ] ) ? $_POST[ $managedSetCode ] : '';
		if ( trim( $set_code ) === '' && isset( $_POST['label'] ) ) {
			$set_code = $_POST['label'];
		}

		// Convert all upper case to lower case, spaces to '-', and remove the rest.
		$set_code = strtolower( $set_code );
		$set_code = preg_replace( '/\s/', '-', $set_code );
		$set_code = preg_replace( '/[^a-z0-9\-]/', '', $set_code );

		// Concat consecutive '-', and trim the result.
		$set_code = preg_replace( '/-+/', '-', $set_code );
		$set_code = trim( $set_code, '-' );

		// Ensure that the result is valid and not already used for another set-code.
		$counter = 1;
		$base    = $set_code === '' ? $managedSetCode : $set_code;
		$set_code = $set_code === '' ? $managedSetCode . '-1' : $set_code;
		while ( $this->isCustomSetCode( $set_code ) || $this->isBuiltinSetCode( $set_code ) ) {
			$set_code = $base . '-' . ( $counter++ );
		}
		return $set_code;
	}


	/**
	 * Either `'colorset'` or `'pieceset'`.
	 */
	abstract protected function getManagedSetCode();


	/**
	 * Whether the given set-code corresponds to a builtin colorset or pieceset.
	 */
	abstract protected function isBuiltinSetCode( $set_code);


	/**
	 * Human-readable message for set-code creation success.
	 */
	abstract protected function getCreationSuccessMessage();


	/**
	 * Human-readable message for set-code edition success.
	 */
	abstract protected function getEditionSuccessMessage();


	/**
	 * Human-readable message for set-code deletion success.
	 */
	abstract protected function getDeletionSuccessMessage();
}
