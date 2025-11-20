<?php
class Model_kategori extends CI_Model {

    function tampildata() {
        return $this->db->get('kategori');
    }

    function get_one($id) {
        return $this->db->get_where('kategori', ['kategori_id' => $id]);
    }

    function post($data) {
        $this->db->insert('kategori', $data);
    }

    function edit($id, $data) {
        $this->db->where('kategori_id', $id);
        $this->db->update('kategori', $data);
    }

    function delete($id) {
        $this->db->where('kategori_id', $id);
        $this->db->delete('kategori');
    }
}
