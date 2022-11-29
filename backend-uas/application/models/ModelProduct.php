<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ModelProduct extends CI_Model
{

  #Get section
  public function getProduct()
  {
    $this->db->select('product.id as product_id, product.name_product, product.code_product, product.image, category.*,  detail.id as detail_id, detail.short_description, detail.long_description');
    $this->db->from('product');
    $this->db->join('category', 'category.id = product.id_category', 'inner');
    $this->db->join('detail', 'detail.id = product.id_detail', 'inner');
    return $this->db->get('')->result_array();
  }

  public function getCategory()
  {
    return $this->db->get('category')->result();
  }

  public function getDetail()
  {
    return $this->db->get('detail')->result();
  }

  #Get Spesific by id or name
  public function getCategorySpesific($where = null)
  {
    $this->db->where('id', $where);
    $query = $this->db->get('category')->row();
    if (!empty($query)) {
      return True;
    } else {
      return False;
    }
  }

  public function getCategoryByName($where = null)
  {
    $this->db->where('name_category', $where);
    $query = $this->db->get('category')->row();
    if (empty($query)) {
      return True;
    } else {
      return False;
    }
  }

  public function getProductSpesific($where = null)
  {
    $this->db->select('product.id as product_id, product.name_product, product.code_product, product.image, category.*, detail.*');
    $this->db->from('product');
    $this->db->join('category', 'category.id = product.id_category', 'inner');
    $this->db->join('detail', 'detail.id = product.id_detail', 'inner');
    $this->db->where('product.id', $where);
    $query = $this->db->get('')->row();
    if (!empty($query)) {
      return $query;
    } else {
      return False;
    }
  }

  public function getDetailSpesific($where = null)
  {
    $this->db->where('id', $where);
    $query = $this->db->get('detail')->row();
    if (!empty($query)) {
      return $query;
    } else {
      return False;
    }
  }


  #Create section
  public function createProduct($data = null)
  {
    return $this->db->insert('product', $data);
  }

  public function createCategory($data = null)
  {
    return $this->db->insert('category', $data);
  }

  public function createDetail($data = null)
  {
    return $this->db->insert('detail', $data);
  }

  #Update Section
  public function updateProduct($data = null, $where = null)
  {
    $this->db->update('product', $data, $where);
    return True;
  }

  public function updateCategory($data = null, $where = null)
  {
    $this->db->update('category', $data, $where);
    return True;
  }

  public function updateDetail($data = null, $where = null)
  {
    $this->db->update('detail', $data, $where);
    return True;
  }

  #Delete section
  public function deleteCategory($data)
  {
    $this->db->where('id', $data);
    $query = $this->db->get('category')->result();
    if (!empty($query)) {
      $this->db->where('id', $data);
      return $this->db->delete('category');
    } else {
      return False;
    }
  }

  public function deleteProduct($data)
  {
    $this->db->where('id', $data);
    $query = $this->db->get('product')->result();
    if (!empty($query)) {
      $this->db->where('id', $data);
      return $this->db->delete('product');
    } else {
      return False;
    }
  }

  public function deleteDetail($data)
  {
    $this->db->where('id', $data);
    $query = $this->db->get('detail')->result();
    if (!empty($query)) {
      $this->db->where('id', $data);
      return $this->db->delete('detail');
    } else {
      return False;
    }
  }
}
