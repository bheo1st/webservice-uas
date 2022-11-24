<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_category extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_category');
    }
    public function read()
    {
        $prd = new M_category;
        $data['category'] = json_decode($prd->readCategory());
        $this->load->view('/assets/V_header', $data);
        $this->load->view('/assets/V_sidebar', $data);
        $this->load->view('/category/V_category', $data);
        $this->load->view('/assets/V_footer', $data);
    }
}
