<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
class pdf extends TCPDF {
	protected $ci;

	public function __construct(){
		$this->ci =& get_instance();
	}
}