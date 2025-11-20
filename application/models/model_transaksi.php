<?php
class Model_transaksi extends CI_Model
{
    function simpan_barang()
    {
        $nama_barang   = $this->input->post('barang');
        $qty           = $this->input->post('qty');

        $session_cart = $this->session->userdata('session_cart');
        if (!$session_cart) {
            $session_cart = 'trx_'.uniqid();
            $this->session->set_userdata('session_cart', $session_cart);
        }

        $barang = $this->db
            ->get_where('barang', ['nama_barang' => $nama_barang])
            ->row_array();

        $data = [
            'barang_id'       => $barang['barang_id'],
            'qty'             => $qty,
            'harga'           => $barang['harga'],
            'status'          => '0',
            'transaksi_id'    => null,
            'session_cart'    => $session_cart
        ];

        $this->db->insert('transaksi_detail', $data);
    }

    function tampilkan_detail_transaksi()
    {
        $session_cart = $this->session->userdata('session_cart');

        $this->db->select('td.t_detail_id, td.qty, td.harga, b.nama_barang');
        $this->db->from('transaksi_detail td');
        $this->db->join('barang b', 'b.barang_id = td.barang_id');
        $this->db->where('td.status', '0');
        $this->db->where('td.session_cart', $session_cart);

        return $this->db->get();
    }

    function hapusitem($id)
    {
            $this->db->delete('transaksi_detail', ['t_detail_id' => $id]);
        }

        function selesai_belanja($nama_customer)
    {
        $session_cart = $this->session->userdata('session_cart');

        $user = $this->session->userdata('username');
        $op = $this->db->get_where('operator', ['username' => $user])->row_array();

        $data = [
            'operator_id'        => $op['id_operator'],
            'tanggal_transaksi'  => date('Y-m-d'),
            'nama_customer'      => $nama_customer
        ];

        $this->db->insert('transaksi', $data);
        $transaksi_id = $this->db->insert_id();

        $this->db->where('session_cart', $session_cart);
        $this->db->update('transaksi_detail', [
            'transaksi_id' => $transaksi_id,
            'status'       => '1'
        ]);

        $this->session->unset_userdata('session_cart');
    }

    function laporan_default()
    {
        $this->db->select('
            t.transaksi_id,
            t.tanggal_transaksi,
            t.nama_customer,
            o.nama_lengkap,
            SUM(td.harga * td.qty) AS total
        ');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_detail td', 'td.transaksi_id = t.transaksi_id');
        $this->db->join('operator o', 'o.id_operator = t.operator_id');
        $this->db->group_by('t.transaksi_id');
        $this->db->order_by('t.transaksi_id','DESC');

        return $this->db->get();
    }

    function laporan_periode($tanggal1, $tanggal2)
    {
        $this->db->select('
            t.transaksi_id,
            t.tanggal_transaksi,
            t.nama_customer,
            o.nama_lengkap,
            SUM(td.harga * td.qty) AS total
        ');
        $this->db->from('transaksi t');
        $this->db->join('transaksi_detail td', 'td.transaksi_id = t.transaksi_id');
        $this->db->join('operator o', 'o.id_operator = t.operator_id');
        $this->db->where('t.tanggal_transaksi >=', $tanggal1);
        $this->db->where('t.tanggal_transaksi <=', $tanggal2);
        $this->db->group_by('t.transaksi_id');
        $this->db->order_by('t.transaksi_id','DESC');

        return $this->db->get();
    }
}
