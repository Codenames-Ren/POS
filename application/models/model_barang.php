<?php
class Model_barang extends CI_Model {

    function tampildata($limit = null, $start = null) {
        $this->db->select('barang.*, kategori.nama_kategori');
        $this->db->from('barang');
        $this->db->join('kategori', 'kategori.kategori_id = barang.kategori_id');
        
        if ($limit !== null) {
            $this->db->limit($limit, $start);
        }
        
            return $this->db->get();
    }

    function get_one($id) {
        $param = array('barang_id' => $id);
        return $this->db->get_where('barang', $param);
    }

    function post($data) {
        $this->db->insert('barang', $data);
    }

    function edit($id, $data) {
        $this->db->where('barang_id', $id);
        $this->db->update('barang', $data);
    }

    function delete($id) {
        $this->db->where('barang_id', $id);
        $this->db->delete('barang');
    }

    function cariData($keyword, $limit = null, $start = null) {
        $this->db->select('barang.*, kategori.nama_kategori');
        $this->db->from('barang');
        $this->db->join('kategori', 'kategori.kategori_id = barang.kategori_id', 'left');
        $this->db->like('barang.nama_barang', $keyword);
        $this->db->or_like('kategori.nama_kategori', $keyword);
        
        // UBAH INI - cukup cek $limit saja karena $start bisa 0
        if ($limit !== null) {
            $this->db->limit($limit, $start);
        }
        
        return $this->db->get();
    }

    function count_all() {
        $this->db->select('barang.*, kategori.nama_kategori');
        $this->db->from('barang');
        $this->db->join('kategori', 'kategori.kategori_id = barang.kategori_id');
        return $this->db->count_all_results();
    }

    function count_search($keyword) {
        $this->db->select('barang.*, kategori.nama_kategori');
        $this->db->from('barang');
        $this->db->join('kategori', 'kategori.kategori_id = barang.kategori_id', 'left');
        $this->db->like('barang.nama_barang', $keyword);
        $this->db->or_like('kategori.nama_kategori', $keyword);
        return $this->db->count_all_results();
    }
}