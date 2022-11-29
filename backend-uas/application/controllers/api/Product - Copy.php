<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . "libraries/Format.php";
require APPPATH . "libraries/RestController.php";

use chriskacerguis\RestServer\RestController;

class Product extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ModelProduct');
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
    }

    ######PRODUCT SECTION#######
    public function index_get()
    {
        $product = new ModelProduct;
        $data = $product->getProduct();
        $this->response($data, 200);
    }

    public function createProduct_post()
    {
        $product = new ModelProduct;

        $config['upload_path'] = './assets/img/upload/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '3000';
        $config['max_width'] = '1280';
        $config['max_height'] = '1280';
        $config['file_name'] = 'img' . time();
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('image')) {
            $image = $this->upload->data();
            $gambar = $image['file_name'];
        } else {
            $gambar = 'default.jpg';
        }
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $data = array(
            'name_product' => $this->post('name_product', true),
            'code_product' => $this->post('code_product', true),
            'image' => $gambar,
            'id_category' => $this->post('id_category', true),
            'id_detail' => $this->post('id_detail', true),
        );

        $insert = $product->createProduct($data);
        if ($insert > 0) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail'), 502);
        }
    }

    public function updateProduct_put($where)
    {
        $product = new ModelProduct;

        $config['upload_path'] = './assets/img/upload/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = '3000';
        $config['max_width'] = '1024';
        $config['max_height'] = '1000';
        $config['file_name'] = 'img' . time();
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('image')) {
            $image = $this->upload->data();
            $gambar = $image['file_name'];
        } else {
            $gambar = 'default.jpg';
        }
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $data = [
            'name_product' => $this->input->post('name_product', true),
            'code_product' => $this->input->post('code_product', true),
            'image' => $gambar,
            'id_category' => $this->input->post('id_category', true),
            'id_detail' => $this->input->post('id_detail', true),
        ];

        $validasi = $product->getProductSpesific($where);
        if ($validasi != False) {
            $id = ['id' => $where];
            $insert = $product->updateProduct($data, $id);
            if ($insert != False) {
                $this->response(['status' => $insert], 200);
            } else {
                $this->response($where, 502);
            }
        } else {
            $this->response(array('status'=>'Update Failed, Data not Found'), 404);
        }
    }

    public function productSpesific_get($where)
    {
        $detail = new ModelProduct;
        $data = $detail->getProductSpesific($where);
        if ($data != False) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'Data Not Found'), 404);
        }
    }

    public function deleteProduct_delete($data)
    {
        $product = new ModelProduct;
        if (!empty($data)) {
            $del = $product->deleteProduct($data);
            if ($del != False) {
                $this->response($data, 200);
            } else {
                $this->response(array('status' => 'Delete Failed'), 404);
            }
        } else {
            $this->response(array('status' => 'Delete Failed'), 400);
        }
    }

    public function category_get()
    {
        $category = new ModelProduct;
        $data = $category->getCategory();
        $this->response($data, 200);
    }

    public function categorySpesific_get($where)
    {
        $category = new ModelProduct;
        $data = $category->getCategorySpesific($where);
        if ($data != False) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'Data Not Found'), 404);
        }
    }

    public function category_post()
    {
        $category = new ModelProduct;
        $checkData = $category->getCategoryByName($this->post('name_category', true));
        if ($checkData == True) {
            $data = [
                'name_category' => $this->post('name_category', true),
            ];
            $insert = $category->createCategory($data);
            if ($insert > 0) {
                $this->response($data, 200);
            } else {
                $this->response(array('status' => 'fail'), 502);
            }
        } else {
            $this->response(["status" => "Already Registered"], 400);
        }
    }

    public function updateCategory_put($where)
    {
        $category = new ModelProduct;

        $data = [
            'name_category' => $this->put('name_category'),
        ];
        $validasi = $category->getCategorySpesific($where);
        if ($validasi != False) {
            $id = ['id' => $where];
            $insert = $category->updateCategory($data, $id);
            if ($insert != False) {
                $this->response(['status' => $insert], 200);
            } else {
                $this->response($where, 502);
            }
        } else {
            $this->response(array('status' => 'Update Failed, Data not Found'), 404);
        }
    }

    public function deleteCategory_delete($data)
    {
        $category = new ModelProduct;
        if (!empty($data)) {
            $del = $category->deleteCategory($data);
            if ($del != False) {
                $this->response($data, 200);
            } else {
                $this->response(array('status' => 'Delete Failed'), 404);
            }
        } else {
            $this->response(array('status' => 'Delete Failed'), 400);
        }
    }

    public function detail_get()
    {
        $detail = new ModelProduct;
        $data = $detail->getDetail();
        $this->response($data, 200);
    }

    public function detailSpesific_get($where)
    {
        $detail = new ModelProduct;
        $data = $detail->getDetailSpesific($where);
        if ($data != False) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'Data Not Found'), 404);
        }
    }

    public function detail_post()
    {
        $detail = new ModelProduct;

        $data = [
            'short_description' => $this->input->post('short_description'),
            'long_description' => $this->input->post('long_description'),
        ];

        $insert = $detail->createDetail($data);
        if ($insert > 0) {
            $this->response($data, 200);
        } else {
            $this->response(array('status' => 'fail'), 502);
        }
    }

    public function updateDetail_put($where)
    {
        $detail = new ModelProduct;

        $data = [
            'short_description' => $this->put('short_description'),
            'long_description' => $this->put('long_description'),
        ];

        $validasi = $detail->getDetailSpesific($where);
        if ($validasi != False) {
            $id = ['id' => $where];
            $insert = $detail->updateDetail($data, $id);
            if ($insert != False) {
                $this->response(['status' => $insert], 200);
            } else {
                $this->response($where, 502);
            }
        } else {
            $this->response(array('status' => 'Update Failed, Data not Found'), 404);
        }
    }

    public function deleteDetail_delete($data)
    {
        $detail = new ModelProduct;
        if (!empty($data)) {
            $del = $detail->deleteDetail($data);
            if ($del != False) {
                $this->response($data, 200);
            } else {
                $this->response(array('status' => 'Delete Failed'), 404);
            }
        } else {
            $this->response(array('status' => 'Delete Failed'), 400);
        }
    }
};
