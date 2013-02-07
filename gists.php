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
 */

/**
 * @file
 * @ingroup Extensions
 * @author Mauro Veron <opensource@mauroveron.com>
 *
 * This extension allows embedding of Github's gists using the <gist> tag
 * like so: <gist>12345678</gist>.
 *
 */

$wgExtensionCredits['gists'][] = array(
	'path' => __FILE__,
	'name' => 'Gists',
	'author' => 'Mauro Veron',
	'url' => '',
	'description' => 'Show embedded gists on your wiki.',
	'version' => 1.0
);

$wgHooks['ParserFirstCallInit'][] = 'mvGists';

function mvGists(Parser $parser) {
	$parser->setHook('gist', 'mvGistRender');
	return true;
}

/**
 * Parses $input (gist number) and embeds gist code.
 */
function mvGistRender($input, $args, $parser, $frame) {
	if( isset( $args['files'] ) ) {
		$files = explode( ' ', $args['files'] );
	} elseif( isset( $args['file'] ) ) {
		$files = array( $args['file'] );
	} else {
		$files = null;
	}

	if( !preg_match('/^\s*[0-9a-f]+\s*$/i', $input ) ) {
		return '!!! Invalid gist number';
	} elseif( $files === null ) {
		return '<script src="https://gist.github.com/' . trim( $input ) . '.js"></script>';
	} else {
		$output = '';
		foreach( $files as $file ) {
			$output .= '<script src="https://gist.github.com/' . trim( $input ) . '.js?file=' . trim( $file ) . '"></script>';
		}
		return $output;
	}
}
