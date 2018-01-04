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
 * Process the POST actions.
 *
 * This class is not constructible. Call the static method `run()`
 * to execute the actions (must be called only once).
 */
abstract class RPBChessboardPostActions {

	/**
	 * Look at the POST variable `$_POST['rpbchessboard_action']` and execute the corresponding action, if any.
	 */
	public static function run() {
		switch ( self::get_post_action() ) {
			case 'update-options':
				self::execute_action( 'SaveOptions', 'updateOptions' );
				break;
			case 'set-default-colorset':
				self::execute_action( 'SaveOptions', 'updateDefaultColorset' );
				break;
			case 'set-default-pieceset':
				self::execute_action( 'SaveOptions', 'updateDefaultPieceset' );
				break;
			case 'reset-general':
				self::execute_action( 'ResetOptions', 'resetGeneral' );
				break;
			case 'reset-compatibility':
				self::execute_action( 'ResetOptions', 'resetCompatibility' );
				break;
			case 'reset-smallscreens':
				self::execute_action( 'ResetOptions', 'resetSmallScreens' );
				break;
			case 'add-colorset':
				self::execute_action( 'ThemingColorset', 'add' );
				break;
			case 'edit-colorset':
				self::execute_action( 'ThemingColorset', 'edit' );
				break;
			case 'delete-colorset':
				self::execute_action( 'ThemingColorset', 'delete' );
				break;
			case 'add-pieceset':
				self::execute_action( 'ThemingPieceset', 'add' );
				break;
			case 'edit-pieceset':
				self::execute_action( 'ThemingPieceset', 'edit' );
				break;
			case 'delete-pieceset':
				self::execute_action( 'ThemingPieceset', 'delete' );
				break;
			default:
				break;
		}
	}


	/**
	 * Load the model `$post_model_name`, and execute the method `$method_name` supposedly defined by this model.
	 *
	 * @param string $post_model_name
	 * @param string $method_name
	 * @param string $capability Required capability to execute the action. Default is `'manage_options'`.
	 */
	private static function execute_action( $post_model_name, $method_name, $capability = 'manage_options' ) {
		if ( ! current_user_can( $capability ) ) {
			return;
		}

		$post_model = RPBChessboardHelperLoader::load_model( 'Post/' . $post_model_name );
		$message   = $post_model->$method_name();

		require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/adminpage.php';
		RPBChessboardAbstractModelAdminPage::initializePostMessage( $message );
	}


	/**
	 * Return the name of the action that should be performed by the server.
	 * The action is initiated by the user when clicking on a "submit" button in
	 * an HTML form with its method attribute set to POST.
	 *
	 * This function may return an empty string if no action is required.
	 *
	 * @return string
	 */
	private static function get_post_action() {
		return isset( $_POST['rpbchessboard_action'] ) ? $_POST['rpbchessboard_action'] : '';
	}
}
