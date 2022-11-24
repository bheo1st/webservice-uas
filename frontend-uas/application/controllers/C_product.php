<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_product extends CI_Controller
{

   public function __construct()
   {
      parent::__construct();
      $this->load->model('M_product');
   }
   public function read()
   {
      $prd = new M_product;
      $data['data']['category'] = json_decode($prd->idCategory());
      $data['data']['detail'] = json_decode($prd->idDetail());
      $data['data']['product'] = json_decode($prd->readProduct());
      $this->load->view('/assets/V_header', $data);
      $this->load->view('/assets/V_sidebar', $data);
      $this->load->view('/product/V_product', $data);
      $this->load->view('/assets/V_footer', $data);
   }
}
