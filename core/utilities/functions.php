<?php
namespace JAAMS\Core\Utilities;
// Require files within a given directory
function require_files( $path, $reg_exp, $recursive = true )
{
	try {
		$dir = new \DirectoryIterator($path);
		
		foreach ( $dir as $file ) {
			if ( $file->isDot() || preg_match('/.DS_Store/', $file->getFilename()))
				continue;
		    if ( $file->isDir() && $recursive )
			   require_files($file->getPathname(), $reg_exp, $recursive);
<<<<<<< HEAD
		    else if ( preg_match( $reg_exp, $file->getFilename() ) )
			    require_once($file->getPathname());
=======
		    else if ( preg_match( $reg_exp, $file->getFilename() ) ) {
			    require_once($file->getPathname());
			}
>>>>>>> 57359df9d6a55169e4262f7e80bae64210b2ba04
		}
	} catch( Exception $e ) {
		return false;
	}
}