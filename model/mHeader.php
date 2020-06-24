<?php 

/**
 * LandingPageBooster mHeader
 *
 *  Manage Header 
 *
 * @class 		mHeader
 * @version		2.4.4
 * @package		LandingPageBooster/Classes
 * @category	Class
 * @author 		Netseek
 */ 

	class mHeader {

    private $scripts = array();

    /**
     * @param string $id        unique script identifier
     * @param string $src   script src attribute
     * @param array  $deps      an array of dependencies ( script identifiers ).
     * @param array  $data      an array, data that will be json_encoded and available to the script.
     */
    function enqueue_script( $id, $src, $deps = array(), $data = array() ) {
        $this->scripts[$id] = array( 'src' => $src, 'deps' => $deps, 'data' => $data );
    }
/**
	 * Get Dependencies.
	 *
	 * @access private
	 * @return array map
	 */
	
    private function dependencies( $script ) {
        if ( $script['deps'] ) {
            return array_map( array( $this, 'dependencies' ), array_intersect_key( $this->scripts, array_flip( $script['deps'] ) ) );
        }
    }
/**
	 * Unset Key Script.
	 *
	 * @access private
	 * 
	 */
    private function _unset( $key, &$deps, &$out ) {
        $out[$key] = $this->scripts[$key];
        unset( $deps[$key] );
    }

    private function flattern( &$deps, &$out = array() ) {

        foreach( $deps as $key => $value ) {            
            empty($value) ? $this->_unset( $key, $deps, $out ) : $this->flattern( $deps[$key], $out );
        }
    }   
/**
	 * Print Scripts
	 *
	 * @access private
	 * 
	 */
    function print_scripts() {

        if ( !$this->scripts ) return;

        $deps = array_map( array( $this, 'dependencies' ), $this->scripts );
        while ( $deps ) 
            $this->flattern( $deps, $js );

        foreach( $js as $key => $script ) {
            $script['data'] && printf( "<script> var %s = %s; </script>" . PHP_EOL, key( $script['data'] ), json_encode( current( $script['data'] ) ) );
            echo "<script id=\"$key-js\" src=\"$script[src]\" type=\"text/javascript\"></script>" . PHP_EOL;
        }
    }
}
?>