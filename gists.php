<?php

/**
 * Extension for MediaWiki to include GitHub Gists in pages.
 * Copyright (C) 2005 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Extensions
 * @author Mauro Veron <opensource@mauroveron.com>
 */

$wgExtensionCredits['gists'][] = array(
	'path' => __FILE__,
	'name' => 'Gists',
	'author' => 'Mauro Veron',
	'url' => 'http://www.mediawiki.org/wiki/Extension:Gists',
	'description' => 'Show embedded gists on your wiki.',
	'version' => 1.0
);

$wgHooks['ParserFirstCallInit'][] = 'mvGists';

/**
 * Add the <gist> tag to the parser.
 *
 * @param Parser $parser Parser object
 * @return bool true
 */
function mvGists( Parser $parser ) {
	$parser->setHook( 'gist', 'mvGistRender' );
	return true;
}

/**
 * Parses $input (gist number) and embeds gist code.
 *
 * @param string $input Contents of tag
 * @param array $args Attributes to the tag
 * @param Parser $parser Parser object
 * @param PPFrame $frame Current parser grame
 */
function mvGistRender( $input, array $args, Parser $parser, PPFrame $frame ) {
	if( !empty( $args['files'] ) ) {
		$files = explode( ' ', $args['files'] );
	} elseif( !empty( $args['file'] ) ) {
		$files = array( $args['file'] );
	} else {
		$files = array( '' );
	}

	if( !ctype_xdigit( $input ) ) {
		return '!!! Invalid gist number';
	} else {
		$gistId = trim( $input );
		$output = '';
		foreach( $files as $file ) {
			$output .= Html::linkedScript( "https://gist.github.com/{$input}.js?file={$file}" );
		}
		return $output;
	}
}
