<?php


class Approve_trans extends CI_Controller
{
    public function __construct(Type $var = null)
    {
        parent::__construct();
        $this->load->model('M_supervisor', 'model');
        date_default_timezone_set('Asia/Jakarta');
        $role = $this->session->userdata("level");
        if ($role != 'SPV') {
            redirect('Login');
        }
    }

    public function list_approve_trans()
    {
        $sess = $this->session->userdata("nik");
        $data = [
            'uri'       => $this->uri->segment(2),
            'raimbus'    => $this->model->listTransaksi($this->session->userdata("departement_id"), 0)
        ];
        $this->template->load('template_supervisor', 'list_approved_trans', $data);
    }

    public function approve()
    {
        $id = $this->input->get("id");
        $kode = $this->input->get("kode");
        $data = [
            'status_approved'            => $kode,
            'ket'                        => $kode == 1 ? 'accept supervisor' : 'reject supervisor',
            'date_approve_spv'           => date('Y-m-d H:i:s'),
            'approve_spv'                => $kode,
            'approve_spv_user'           => $this->session->userdata("nik")
        ];
        $update = $this->model->updateData($data, "transaksi_jenis_pembayaran", ['id' => $id]);
        if ($update > 0) {
            $this->session->set_flashdata("ok", $kode == 1 ? 'budget telah di setujui' : 'budget telah di tolak ' . ",silahkan konfirmasi ke departement terkait");
            redirect('spv/Approve_trans/list_approve_trans');
        } else {
            $this->session->set_flashdata("nok", "terjadi kesalahan");
            redirect('spv/Approve_trans/list_approve_trans');
        }
    }

    public function viewDetailRaimbes()
    {
        $id    = $this->input->post("id");
        $file1 = $this->input->post("file1");
        $file2 = $this->input->post("file2");
        $file3 = $this->input->post("file3");
        $nama  = $this->input->post("nama");
        $remarks  = $this->input->post("remarks");
        $jenis  = $this->input->post("jenis");
        $data = [
            'raimbus'   => $this->model->ambilData('trans_detail_jenis_pembayaran', ['transaksi_jenis_pembayaran_id' => $id]),
            'file1'      => $file1,
            'file2'      => $file2,
            'file3'      => $file3,
            'nama'       => $nama,
            'remarks'    => $remarks,
            'jenis'       => $jenis,
        ];
        $this->load->view("detail_approved_trans", $data);
    }

    public function histori_approve_trans()
    {
        $sess = $this->session->userdata("nik");
        $data = [
            'uri'       => $this->uri->segment(2),
            'raimbus'    => $this->model->listTransaksi($this->session->userdata("departement_id"), 1, $sess)
        ];
        $this->template->load('template_supervisor', 'histori_raimbusment_approved', $data);
    }


    public function multiApprove()
    {
        $multi = $this->input->post("multi[]");
        $data = array();
        for ($i = 0; $i < count($multi); $i++) {
            $params = array(
                'status_approved'   => 1,
                'ket'               => 'accept supervisor',
                'date_approve_spv'  => date('Y-m-d H:i:s'),
                'approve_spv'       => 1,
                'approve_spv_user'  => $this->session->userdata("nik"),
                'id'                => $multi[$i]
            );
            array_push($data, $params);
        }
        $this->db->update_batch('transaksi_jenis_pembayaran', $data, 'id');
        if ($this->db->affected_rows() > 0) {
            $this->session->set_flashdata("ok", 'budget telah di setujui,silahkan konfirmasi ke departement terkait');
            redirect('spv/Approve_trans/list_approve_trans');
        } else {
            $this->session->set_flashdata("nok", "terjadi kesalahan");
            redirect('spv/Approve_trans/list_approve_trans');
        }
    }
}
