<?php


class ApproveRequestTambah extends CI_Controller
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

    public function list_approve_req()
    {
        $data = [
            'uri'            => $this->uri->segment(2),
            'menunggu'    => $this->model->list_request($this->session->userdata("departement_id"), 'spv', 0),
            'selesai'    => $this->model->list_request($this->session->userdata("departement_id"), 'spv', 1)
        ];
        $this->template->load('template_supervisor', 'list_approved_request_tambah', $data);
    }

    public function approve()
    {
        $id = $this->input->get("id");
        $kode = $this->input->get("kode");
        $data = [
            'status'                     => $kode,
            'ket'                        => $kode == 1 ? 'accept supervisor' : 'reject supervisor',
            'date_approve_spv'           => date('Y-m-d H:i:s'),
            'approve_spv'                => $kode,
            'approve_spv_user'           => $this->session->userdata("nik")
        ];
        $update = $this->model->updateData($data, "transaksi_request_tambah_budget", ['id' => $id]);
        if ($update > 0) {
            $this->session->set_flashdata("ok", $kode == 1 ? 'penambahan budget telah di setujui' : 'penambahan budget telah di tolak ' . ",silahkan konfirmasi ke departement terkait");
            redirect('spv/ApproveRequestTambah/list_approve_req');
        } else {
            $this->session->set_flashdata("nok", "terjadi kesalahan");
            redirect('spv/ApproveRequestTambah/list_approve_req');
        }
    }
}
