<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class New_theme extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        
    }
    public function index()
    {
    	echo "Hii'";
    }
}
?>