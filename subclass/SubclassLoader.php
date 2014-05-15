<?php
class SubclassLoader {
	const DIRECTORY = 'test';
	const PREFIX = '';
	const SUFFIX = 'Test.php';

	public static function suite() {
		// create a test suite by finding ALL matching non-abstract classes, even base classes
		$suite = new PHPUnit_Framework_TestSuite;

		// gather all the files, just as PHPUnit_Util_Configuration does
		$fileIteratorFacade = new File_Iterator_Facade;
		$files = $fileIteratorFacade->getFilesAsArray(
              		self::toAbsolutePath(self::DIRECTORY),
              		self::SUFFIX,
              		self::PREFIX,
              		array()
            		);
		foreach( $files as $file ) {
			self::addToSuite( $suite, $file );
		}
		return $suite;
	}

	private static function addToSuite( $suite, $filename ) {
		// load the file... but instantiate the class no matter if it is new or not
		$filename   = PHPUnit_Util_Fileloader::checkAndLoad($filename);
	        $baseName   = str_replace('.php', '', basename($filename));
		
		$class = new ReflectionClass($baseName);
            	if (!$class->isAbstract()) {
                	if ($class->hasMethod(PHPUnit_Runner_BaseTestRunner::SUITE_METHODNAME)) {
                    		$method = $class->getMethod(
                      		PHPUnit_Runner_BaseTestRunner::SUITE_METHODNAME
                    		);

                    		if ($method->isStatic()) {
                        		$suite->addTest($method->invoke(NULL, $className));
                    		}
                	}
                	else if ($class->implementsInterface('PHPUnit_Framework_Test')) {
                    		$suite->addTestSuite($class);
            		}
            	}
	}

    	private static function toAbsolutePath($path, $useIncludePath = FALSE) {
        	// Check whether the path is already absolute.
        	if ($path[0] === '/' || $path[0] === '\\' ||
            		(strlen($path) > 3 && ctype_alpha($path[0]) &&
             		$path[1] === ':' && ($path[2] === '\\' || $path[2] === '/'))) {
            		return $path;
        	}

        	// Check whether a stream is used.
        	if (strpos($path, '://') !== FALSE) {
            		return $path;
        	}

        	$file = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . $path;

        	if ($useIncludePath && !file_exists($file)) {
            		$includePathFile = PHPUnit_Util_Filesystem::fileExistsInIncludePath(
             			$path
            			);

            		if ($includePathFile) {
                		$file = $includePathFile;
            		}
        	}
		return $file;
    	}
}
