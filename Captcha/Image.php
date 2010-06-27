<?php

/*
Example configuration array

'captcha' => array
(
	'width' => 150,
	'height' => 50,
	'padding' => '5,5,8,10',
	'noise' => 50,
	'dust_vs_scratches' => 90,
	'letters_no' => 4,
	'letter_precession' => 30,
	'use_local_fonts' => 1,
	'font_size' => '18,20,22',
	'fonts' => 'aamunkoi,all_used_up,almanac_of_the_apprentice,appendi3,carbtim,colour,fruidc__,reclaimthestreets',
	'case_sensitive' => 0,
	'bg_color_1' => '10.10.10',
	'bg_color_2' => '128.128.128',
	'font_dir' => PROJECT_ROOT_PATH . '/resources/captcha/fonts',
	'fg_colors' => array
	(
		'fg_color_1' => '255.185.0',
		'fg_color_2' => '231.231.231',
		'fg_color_3' => '255.255.255',
		'fg_color_4' => '255.221.0'
	)
)
*/

class RPC_Captcha_Image /* implements RPC_Captcha */
{
	
	/**
	 * Class configuration
	 * 
	 * @var array
	 */
	protected $config;
	
	/**
	 * Resulting image
	 * 
	 * @var resource
	 */
	protected $image;
	
	/**
	 * Array of letters
	 * 
	 * @var array
	 */
	protected $letters;
	
	/**
	 * Class instance
	 * 
	 * @var RPC_Captcha_Image
	 */
	protected static $instance = null;
	
	/**
	 * Singleton
	 * 
	 * @see self::getInstance()
	 */
	protected function __construct() {}
	
	/**
	 * Singleton
	 * 
	 * @see self::getInstance()
	 */
	protected function __clone() {}
	
	/**
	 * Class constructor
	 * 
	 * @return RPC_Captcha_Image
	 */
	public static function getInstance()
	{
		if( empty( self::$instance ) )
		{
			$c = __CLASS__;
			self::$instance = new $c();
			
			$config = array
			(
					'width' => 150,
					'height' => 50,
					'padding' => '5,5,8,10',
					'noise' => 50,
					'dust_vs_scratches' => 90,
					'letters_no' => 4,
					'letter_precession' => 30,
					'use_local_fonts' => 0,
					'font_size' => '18,20,22',
					'fonts' => '', // ex: "arial,helvetica"; only used when use_local_fonts=1
					'bg_color_1' => '10.10.10',
					'bg_color_2' => '128.128.128',
					'font_dir' => '', // ex: PROJECT_ROOT_PATH . '/resources/captcha/fonts'; only used when use_local_fonts=1
					'fg_colors' => array // as many colors as letters_no - each color will be applied to the corresponding letter
					(
						'fg_color_1' => '255.185.0',
						'fg_color_2' => '231.231.231',
						'fg_color_3' => '255.255.255',
						'fg_color_4' => '255.221.0'
					)
			);
			
			self::$instance->loadConfig( $config );
		}
		
		return self::$instance;
	}
	
	/**
	 * Loads the configuration array that will be used
	 * 
	 * @return RPC_Captcha_Image
	 */
	public function loadConfig( $config )
	{
		foreach( $config as $k => $v )
		{
			$this->config[$k] = $v;
		}
		
		return $this;
	}
	
	/**
	 * Generates the image and puts md5 hash into the session
	 * 
	 * @return void
	 */
	public function render()
	{
		if( headers_sent() )
		{
			throw new Exception( 'Headers already sent, cannot render captcha' );
		}
		
		$value = RPC_Util::generatePassword( 4, $this->config['letters_no'] );
		
		$this->setValue( $value );
		
		$this->createImgBase()
		     ->addDustAndScratches( $this->config['bg_color_2'] )
		     ->printLetters( $value )
		     ->addDustAndScratches( $this->config['bg_color_1'] );
		
		header( 'Content-type: image/png' );
		imagepng( $this->image );
		imagedestroy( $this->image );
	}
	
	/**
	 * Stores the value of the captcha into the session
	 * 
	 * @return RPC_Captcha_Image
	 */
	protected function setValue( $value )
	{
		$_SESSION['_RPC_']['captcha'] = $value;
		
		return $this;
	}
	
	/**
	 * Returns the value of the current captcha and, optionally, removes
	 * the value from the session. Returns null in case no value has been
	 * set previously
	 * 
	 * @param bool $clean
	 * 
	 * @return string
	 */
	public function getValue( $clean = true )
	{
		$value = @$_SESSION['_RPC_']['captcha'];
		
		if( $clean )
		{
			unset( $_SESSION['_RPC_']['captcha'] );
		}
		
		return empty( $value ) ? null : $value;
	}
	
	/**
	 * Sets the new image and its background
	 *
	 * @return RPC_Captcha_Image
	 */
	protected function createImgBase()
	{
		list( $red, $green, $blue ) = explode( '.', $this->config['bg_color_1'] );
		
		$img = imagecreate( $this->config['width'], $this->config['height'] );
		imagecolorallocate( $img, $red, $green, $blue );
		
		$this->image = $img;
		
		return $this;
	}
	
	/**
	 * Generate some noise
	 * 
	 * @param string $color
	 * 
	 * @return RPC_Captcha_Image
	 */
	protected function addDustAndScratches( $color )
	{
		$max_x = $this->config['width'] - 1;
		$max_y = $this->config['height'] - 1;
		
		list( $red, $green, $blue ) = explode( '.', $color );
		$color = imagecolorallocate( $this->image, $red, $green, $blue );
		
		for( $i = 0; $i < $this->config['noise']; $i++ )
		{
			if( rand( 1, 100 ) > $this->config['dust_vs_scratches'] )
			{
				imageline( $this->image, rand( 0, $max_x ), rand( 0, $max_y ), rand( 0, $max_x ), rand( 0, $max_y ), $color );
			}
			else
			{
				imagesetpixel( $this->image, rand( 0, $max_x ), rand( 0, $max_y ), $color );
			}
		}
		
		return $this;
	}
	
	/**
	 * Generates the text in the image
	 * 
	 * This function takes many parameters from the captcha_x.ini file, which
	 * define the fonts, their sizes, letter angle and letterr colors
	 * 
	 * @return RPC_Captcha_Image
	 */
	protected function printLetters( $value )
	{
		list( $padding_top, $padding_right, $padding_bottom, $padding_left ) = explode( ',', $this->config['padding'] );
		
		$box_width	   = ( $this->config['width'] - ( $padding_left + $padding_right ) ) / $this->config['letters_no'];
		$box_height	  = $this->config['height'] - ( $padding_top + $padding_bottom );
		
		$font_size	   = explode( ',', $this->config['font_size'] );
		$font_size_count = count( $font_size ) - 1;
		
		$fonts = explode( ',', $this->config['fonts'] );
		$fonts_count = count( $fonts ) - 1;
		
		$fg_colors_count = count( $this->config['fg_colors'] ) - 1;
		$fg_colors = array();
		foreach( $this->config['fg_colors'] as $fg_color )
		{
			$fg_colors[] = explode( '.', $fg_color );
		}
		
		for( $i = 0; $i < $this->config['letters_no']; ++$i )
		{
			$size_index = rand( 0, $font_size_count );
			$size	   = $font_size[$size_index];
			
			$angle = ( rand( 0, $this->config['letter_precession'] * 2 ) - $this->config['letter_precession'] + 360 ) % 360;
			
			$x = $padding_left + ( $box_width * $i );
			$y = $padding_top + $size + ( ( $box_height - $size) / 2 );
			
			$color_index = rand( 0, $fg_colors_count );
			$color	   = $fg_colors[$color_index];
			$color	   = imagecolorallocate( $this->image, $color[0], $color[1], $color[2] );
			
			$font_index = rand( 0, $fonts_count );
			$font       = $this->config['font_dir'] . $fonts[$font_index];
			
			imagettftext( $this->image, $size, $angle, $x, $y, $color, $font, $value[$i] );
		}
		
		return $this;
	}
	
}

?>
