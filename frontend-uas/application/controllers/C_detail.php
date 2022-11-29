<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_detail extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_detail');
    }
    public function read()
    {
        $prd = new M_detail;
        $data['detail'] = json_decode($prd->readDetail());
        $this->load->view('/assets/V_header', $data);
        $this->load->view('/assets/V_sidebar', $data);
        $this->load->view('/detail/V_detail', $data);
        $this->load->view('/assets/V_footer', $data);
    }
}
