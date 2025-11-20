<?php
class dashboard  extends CI_Controller{

	function index(){
		chek_session();
		$this->template->load('template','dashboard');
	}
}