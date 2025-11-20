<?php
class Model_operator extends CI_Model
{
    public function login($username, $password)
    {
        $chek = $this->db->get_where('operator', [
            'username' => $username,
            'password' => md5($password)
        ]);

        return ($chek->num_rows() > 0) ? 1 : 0;
    }

    public function count_all()
    {
        return $this->db->count_all('operator');
    }

    public function count_search($keyword)
    {
        $this->db->from('operator');
        $this->db->like('nama_lengkap', $keyword);
        $this->db->or_like('username', $keyword);
        return $this->db->count_all_results();
    }

    public function tampildata($limit = null, $offset = null)
    {
        $this->db->order_by('nama_lengkap', 'ASC');

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get('operator');
    }

    public function cariData($keyword, $limit, $offset)
    {
        $this->db->from('operator');
        $this->db->like('nama_lengkap', $keyword);
        $this->db->or_like('username', $keyword);
        $this->db->order_by('nama_lengkap', 'ASC');
        $this->db->limit($limit, $offset);

        return $this->db->get();
    }

    public function get_one($id)
    {
        return $this->db->get_where('operator', ['id_operator' => $id]);
    }

    public function is_username_exist($username, $exclude_id = null)
    {
        $this->db->from('operator');
        $this->db->where('username', $username);

        if ($exclude_id !== null) {
            $this->db->where('id_operator !=', $exclude_id);
        }

        $query = $this->db->get();
        return ($query->num_rows() > 0);
    }
}
