<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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
?>

<div id="rpbchessboard-helpPgnPage">

	<h3>
		<?php echo sprintf(
			__('Chess games with the %1$s[%3$s][/%3$s]%2$s tags', 'rpbchessboard'),
			'<span class="rpbchessboard-sourceCode">',
			'</span>',
			htmlspecialchars($model->getPGNShortcode())
		); ?>
	</h3>





	<h4><?php _e('PGN format', 'rpbchessboard'); ?></h4>

	<p>
		<?php echo sprintf(
			__(
				'The string between the %1$s[%3$s][/%3$s]%2$s tags describe the game. '.
				'The used notation follows the standard %4$sPGN format%7$s, and can '.
				'be automatically generated by the common chess database softwares, '.
				'including %5$sChessbase%7$s, %6$sScid%7$s, etc... '.
				'The PGN syntax is summarized here through a few representative examples.',
			'rpbchessboard'),
			'<span class="rpbchessboard-sourceCode">',
			'</span>',
			htmlspecialchars($model->getPGNShortcode()),
			sprintf('<a href="%1$s" target="_blank">', __('http://en.wikipedia.org/wiki/Portable_Game_Notation', 'rpbchessboard')),
			'<a href="http://www.chessbase.com/" target="_blank">',
			'<a href="http://scid.sourceforge.net/" target="_blank">',
			'</a>'
		); ?>
	</p>





	<div id="rpbchessboard-pgnExamples" class="rpbchessboard-tabs">

		<ul>
			<li><a href="#rpbchessboard-pgnExample1"><?php _e('Standard game'           , 'rpbchessboard'); ?></a></li>
			<li><a href="#rpbchessboard-pgnExample2"><?php _e('Game with no header'     , 'rpbchessboard'); ?></a></li>
			<li><a href="#rpbchessboard-pgnExample3"><?php _e('Custom starting position', 'rpbchessboard'); ?></a></li>
		</ul>


		<div id="rpbchessboard-pgnExample1">
			<div class="rpbchessboard-columns">
				<div>
					<div class="rpbchessboard-sourceCode">
						[<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]<br/>
						<br/>
						[Event &quot;Linares 16&lt;sup&gt;th&lt;sup&gt;&quot;]<br/>
						[Site &quot;Linares, ESP&quot;]<br/>
						[Date &quot;1999.02.25&quot;]<br/>
						[Round &quot;4&quot;]<br/>
						[White &quot;Kasparov, Garry&quot;]<br/>
						[Black &quot;Kramnik, Vladimir&quot;]<br/>
						[Result &quot;1/2-1/2&quot;]<br/>
						[WhiteElo &quot;2812&quot;]<br/>
						[BlackElo &quot;2751&quot;]<br/>
						[WhiteTitle &quot;WCH&quot;]<br/>
						[BlackTitle &quot;GM&quot;]<br/>
						[Annotator &quot;?&quot;]<br/>
						<br/>
						1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8.
						a3 Be7 9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15.
						Ne2 g5 16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2<br/>
						<br/>
						[/<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]
					</div>
				</div>
				<div>
					<div class="rpbchessboard-visuBlock">
						<div>
							<div id="rpbchessboard-pgnExample1-anchor"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#rpbchessboard-pgnExample1-anchor').chessgame({
										pgn:
											'[Event "Linares 16<sup>th</sup>"]\n' +
											'[Site "Linares, ESP"]\n' +
											'[Date "1999.02.25"]\n' +
											'[Round "4"]\n' +
											'[White "Kasparov, Garry"]\n' +
											'[Black "Kramnik, Vladimir"]\n' +
											'[Result "1/2-1/2"]\n' +
											'[WhiteElo "2812"]\n' +
											'[BlackElo "2751"]\n' +
											'[WhiteTitle "WCH"]\n' +
											'[BlackTitle "GM"]\n' +
											'[Annotator "?"]\n' +
											'1. d4 Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 8. a3 Be7 ' +
											'9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4 a5 14. Rac1 O-O 15. Ne2 g5 ' +
											'16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 1/2-1/2'
									});
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div id="rpbchessboard-pgnExample2">
			<div class="rpbchessboard-columns">
				<div>
					<div class="rpbchessboard-sourceCode">
						[<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]<br/>
						1. e3 a5 2. Qh5 Ra6 3. Qxa5 h5 4. Qxc7 Rah6 5. h4 f6 6. Qxd7+ Kf7 7. Qxb7 Qd3
						8. Qxb8 Qh7 9. Qxc8 Kg6 10. Qe6 {<?php _e('Stalemate.', 'rpbchessboard'); ?>} *<br/>
						[/<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]
					</div>
				</div>
				<div>
					<div class="rpbchessboard-visuBlock">
						<div>
							<div id="rpbchessboard-pgnExample2-anchor"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#rpbchessboard-pgnExample2-anchor').chessgame({
										pgn:
											'1. e3 a5 2. Qh5 Ra6 3. Qxa5 h5 4. Qxc7 Rah6 5. h4 f6 6. Qxd7+ Kf7 7. Qxb7 Qd3 8. Qxb8 Qh7 ' +
											'9. Qxc8 Kg6 10. Qe6 {' + <?php echo json_encode(__('Stalemate.', 'rpbchessboard')); ?> + '} *'
									});
								});
							</script>
						</div>
					</div>
				</div>
			</div>
			<p>
				<?php echo sprintf(
					__(
						'Normally, the strict PGN syntax requires that each PGN entry starts with 7 compulsory headers: '.
						'%1$s[Event &quot;...&quot;]%2$s, %1$s[Site &quot;...&quot;]%2$s, %1$s[Date &quot;...&quot;]%2$s, '.
						'%1$s[Round &quot;...&quot;]%2$s, %1$s[White &quot;...&quot;]%2$s, %1$s[Black &quot;...&quot;]%2$s, '.
						'and %1$s[Result &quot;...&quot;]%2$s. However, the RPB Chessboard plugin consider them as optional.',
					'rpbchessboard'),
					'<span class="rpbchessboard-sourceCode">',
					'</span>'
				); ?>
			</p>
		</div>


		<div id="rpbchessboard-pgnExample3">
			<div class="rpbchessboard-columns">
				<div>
					<div class="rpbchessboard-sourceCode">
						[<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]<br/>
						<br/>
						[Event &quot;<?php _e('Endgame example', 'rpbchessboard'); ?>&quot;]<br/>
						[SetUp &quot;1&quot;]<br/>
						[FEN &quot;k7/n1PB4/1K6/8/8/8/8/8 w - - 0 50&quot;]<br/>
						<br/>
						{[pgndiagram]}<br/>
						<br/>
						50.Bc6+ Nxc6 51.c8=Q+ Nb8 52.Qb7# 1-0<br/>
						<br/>
						[/<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]
					</div>
					<p>
						<?php echo sprintf(
							__(
								'The %1$s[FEN &quot;...&quot;]%2$s header might be used to specify that the game '.
								'starts with a custom position. Additionally, the strict PGN syntax requires that '.
								'%1$s[SetUp &quot;1&quot;]%2$s is added when using the %1$s[FEN &quot;...&quot;]%2$s header.',
							'rpbchessboard'),
							'<span class="rpbchessboard-sourceCode">',
							'</span>'
						); ?>
					</p>
				</div>
				<div>
					<div class="rpbchessboard-visuBlock">
						<div>
							<div id="rpbchessboard-pgnExample3-anchor"></div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#rpbchessboard-pgnExample3-anchor').chessgame({
										diagramOptions: { squareSize: 28 },
										pgn:
											'[Event "' + <?php echo json_encode(__('Endgame example', 'rpbchessboard')); ?> + '"]\n' +
											'[SetUp "1"]\n' +
											'[FEN "k7/n1PB4/1K6/8/8/8/8/8 w - - 0 50"]\n' +
											'\n' +
											'{<div class="uichess-chessgame-diagramAnchor"></div>}\n' +
											'\n' +
											'50.Bc6+ Nxc6 51.c8=Q+ Nb8 52.Qb7# 1-0'
									});
								});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>


		<script type="text/javascript">
			jQuery(document).ready(function($) { $('#rpbchessboard-pgnExamples').tabs(); });
		</script>

	</div>





	<h4><?php _e('NAGs (aka. <em>Numeric Annotation Glyphs</em>)', 'rpbchessboard'); ?></h4>

	<div class="rpbchessboard-columns">
		<div>
			<div class="rpbchessboard-sourceCode">
				[<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]
				1.e4 !! ! !? ?! ? ?? +- +/- +/= = inf =/+ -/+ -+ *
				[/<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]
			</div>
		</div>
		<div>
			<div class="rpbchessboard-visuBlock">
				<div>
					<div id="rpbchessboard-pgnNAG-anchor"></div>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$('#rpbchessboard-pgnNAG-anchor').chessgame({
								pgn: '1.e4 !! ! !? ?! ? ?? +- +/- +/= = inf =/+ -/+ -+ *'
							});
						});
					</script>
				</div>
			</div>
		</div>
	</div>

	<p>
		<?php echo sprintf(
			__(
				'Notice that the chess database softwares may introduce annotations such as %1$s&quot;$x&quot;%2$s '.
				'where %1$s&quot;x&quot;%2$s is replaced with one or more digits (for instance, %1$s&quot;1.e4 $1&quot;%2$s). '.
				'This is what is advocated by the PGN norm, which defines equivalences between this syntax and the human-readable one '.
				'(for instance, %1$s&quot;$1&quot;%2$s is equivalent to %1$s&quot;!&quot;%2$s). Both syntaxes are understood '.
				'by the RPB Chessboard plugin. See the %3$slist of NAGs%4$s.',
			'rpbchessboard'),
			'<span class="rpbchessboard-sourceCode">',
			'</span>',
			sprintf('<a href="%1$s" target="_blank">', __('http://en.wikipedia.org/wiki/Numeric_Annotation_Glyphs', 'rpbchessboard')),
			'</a>'
		); ?>
	</p>





	<h4><?php _e('Comments', 'rpbchessboard'); ?></h4>

	<div class="rpbchessboard-columns">
		<div>
			<div class="rpbchessboard-sourceCode">
				[<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]<br/>
				1. d4
				{<?php echo sprintf(__('I\'m a %1$sshort%2$s comment.', 'rpbchessboard'),
					'&lt;strong style=&quot;color:red;&quot;&gt;', '&lt;/strong&gt;'); ?>}
				1... Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5
				8. a3 Be7 9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4<br/>
				<br/>
				{<?php echo sprintf(__('I\'m a %1$slong%2$s comment.', 'rpbchessboard'),
					'&lt;strong style=&quot;color:red;&quot;&gt;', '&lt;/strong&gt;'); ?>}<br/>
				<br/>
				13... a5 14. Rac1 O-O 15. Ne2 g5 16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6
				19. Rcc1 Rfd8 20. Rfd1 Rac8 *<br/>
				[/<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]
			</div>
		</div>
		<div>
			<div class="rpbchessboard-visuBlock">
				<div>
					<div id="rpbchessboard-pgnComment-anchor"></div>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$('#rpbchessboard-pgnComment-anchor').chessgame({
								pgn:
									'1. d4 {' +
									<?php echo json_encode(sprintf(__('I\'m a %1$sshort%2$s comment.', 'rpbchessboard'),
										'<strong style="color:red;">', '</strong>')); ?> +
									'} 1... Nf6 2. c4 e6 3. Nc3 Bb4 4. Qc2 d5 5. cxd5 Qxd5 6. Nf3 Qf5 7. Qxf5 exf5 ' +
									'8. a3 Be7 9. Bg5 Be6 10. e3 c6 11. Bd3 Nbd7 12. O-O h6 13. Bh4\n' +
									'\n' +
									'{' + <?php echo json_encode(sprintf(__('I\'m a %1$slong%2$s comment.', 'rpbchessboard'),
										'<strong style="color:red;">', '</strong>')); ?> + '}\n' +
									'\n' +
									'13... a5 14. Rac1 O-O 15. Ne2 g5 16. Bg3 Ne4 17. Nc3 Nxc3 18. Rxc3 Nf6 19. Rcc1 Rfd8 20. Rfd1 Rac8 *'
							});
						});
					</script>
				</div>
			</div>
		</div>
	</div>

	<p>
		<?php
			_e(
				'Text comments can be inserted, surrounded with braces. They can be rendered either inlined within the move sequence '.
				'(&quot;short comment&quot; style), or as separated blocks (&quot;long comment&quot; style). To insert a comment '.
				'as a separated block, let two blank lines before and after it in the PGN string. '.
				'Also, notice that HTML tags are allowed within comments.',
			'rpbchessboard');
		?>
	</p>





	<h4><?php _e('Variations', 'rpbchessboard'); ?></h4>

	<div class="rpbchessboard-columns">
		<div>
			<div class="rpbchessboard-sourceCode">
				[<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]<br/>
				1. e4 e5 (1... c5) (1... e6) 2. Nf3 Nc6 3. Bb5<br/>
				<br/>
				(3. Bc4 Bc5 (3... Be7) 4.d4)<br/>
				<br/>
				(3. d4 exd4 4. Nxd4 Bc5 5. Be3 Qf6)<br/>
				<br/>
				3... a6 4. Ba4 *<br/>
				[/<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]
			</div>
		</div>
		<div>
			<div class="rpbchessboard-visuBlock">
				<div>
					<div id="rpbchessboard-pgnVariation-anchor"></div>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$('#rpbchessboard-pgnVariation-anchor').chessgame({
								pgn:
									'1. e4 e5 (1... c5) (1... e6) 2. Nf3 Nc6 3. Bb5\n' +
									'\n' +
									'(3. Bc4 Bc5 (3... Be7) 4.d4)\n' +
									'\n' +
									'(3. d4 exd4 4. Nxd4 Bc5 5. Be3 Qf6)\n' +
									'\n' +
									'3... a6 4. Ba4 *'
							});
						});
					</script>
				</div>
			</div>
		</div>
	</div>

	<p>
		<?php
			_e(
				'As for comments, variations can be rendered either inlined within the move sequence, '.
				'or as separated blocks if they are surrounded with two blank lines in the PGN string.',
			'rpbchessboard');
		?>
	</p>





	<h4><?php
		echo sprintf(__('Diagrams with the %1$s[pgndiagram]%2$s tag', 'rpbchessboard'),
			'<span class="rpbchessboard-sourceCode">', '</span>');
	?></h4>

	<div class="rpbchessboard-columns">
		<div>
			<div class="rpbchessboard-sourceCode">
				[<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]<br/>
				1. e4 c5<br/>
				<br/>
				{[pgndiagram] <?php _e('This opening is called the Sicilian defence. A possible continuation is:', 'rpbchessboard'); ?>}<br/>
				<br/>
				2. Nf3 d6 *<br/>
				[/<?php echo htmlspecialchars($model->getPGNShortcode()); ?>]
			</div>
			<p>
				<?php echo sprintf(
					__(
						'Notice that %1$s[pgndiagram]%2$s tags must be inserted only in PGN comments.',
					'rpbchessboard'),
					'<span class="rpbchessboard-sourceCode">',
					'</span>'
				); ?>
			</p>
		</div>
		<div>
			<div class="rpbchessboard-visuBlock">
				<div>
					<div id="rpbchessboard-pgnDiagram-anchor"></div>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$('#rpbchessboard-pgnDiagram-anchor').chessgame({
								diagramOptions: { squareSize: 28 },
								pgn:
									'1. e4 c5\n' +
									'\n' +
									'{<div class="uichess-chessgame-diagramAnchor"></div> ' +
									<?php
										echo json_encode(__('This opening is called the Sicilian defence. A possible continuation is:', 'rpbchessboard'));
									?> + '}\n' +
									'\n' +
									'2. Nf3 d6 *'
							});
						});
					</script>
				</div>
			</div>
		</div>
	</div>





	<h4 id="rpbchessboard-helpOnPgnAttributes"><?php _e('Attributes', 'rpbchessboard'); ?></h4>

	<p>
		<?php echo sprintf(
			__(
				'The %1$s[%4$s][/%4$s]%2$s and %1$s[pgndiagram]%2$s tags accept the same attributes as the %1$s[%3$s][/%3$s]%2$s ones. '.
				'For instance, using %1$s[%4$s square_size=...] ... [/%4$s]%2$s will affect the size of all the chess diagrams '.
				'inserted in the comments of the corresponding chess game. Setting %1$s[pgndiagram square_size=...]%2$s '.
				'will affect only the corresponding diagram.',
			'rpbchessboard'),
			'<span class="rpbchessboard-sourceCode">',
			'</span>',
			htmlspecialchars($model->getFENShortcode()),
			htmlspecialchars($model->getPGNShortcode())
		); ?>
	</p>

</div>
