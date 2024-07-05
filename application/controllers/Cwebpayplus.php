<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cwebpay extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->library('transbanktransaction'); // Asegúrate de que el nombre esté en minúsculas

        if (ENVIRONMENT === 'production') {
            \Transbank\Webpay\WebpayPlus::configureForProduction(config_item('webpay_plus_cc'), config_item('webpay_plus_api_key'));
        } else {
            \Transbank\Webpay\WebpayPlus::configureForTesting();
        }
    }

    public function createTransaction()
    {
        $req = $this->input->post();
        $resp = $this->transbanktransaction->create($req["buy_order"], $req["session_id"], $req["amount"], $req["return_url"]);

        $data = [
            "params" => $req,
            "response" => $resp
        ];

        return json_encode($data);
    }

    public function commitTransaction()
    {
        if ($this->input->post("token_ws")) {
            $req = $this->input->post();
            $resp = $this->transbanktransaction->commit($req["token_ws"]);

            $data = [
                "resp" => $resp,
                'req' => $req
            ];

            $this->load->view('webpayplus/transaction_committed', $data);
        } elseif ($this->input->post("TBK_TOKEN")) {
            $data = ["resp" => $this->input->post()];
            $this->load->view('webpayplus/transaction_aborted', $data);
        } else {
            $data = ["resp" => $this->input->post()];
            $this->load->view('webpayplus/transaction_timeout', $data);
        }
    }

    public function showRefund()
    {
        $this->load->view('webpayplus/refund');
    }

    public function refundTransaction()
    {
        $error = false;
        try {
            $req = $this->input->post();
            $resp = $this->transbanktransaction->refund($req["token"], $req["amount"]);
        } catch (\Exception $e) {
            $resp = [
                'msg' => $e->getMessage(),
                'code' => $e->getCode()
            ];
            $error = true;
        }

        $data = [
            "resp" => $resp,
            "error" => $error
        ];

        $this->load->view('webpayplus/refund_success', $data);
    }

    public function getTransactionStatus()
    {
        $req = $this->input->post();
        $token = $req["token"];
        $resp = $this->transbanktransaction->status($token);

        $data = [
            "resp" => $resp,
            "req" => $req
        ];

        $this->load->view('webpayplus/transaction_status', $data);
    }
}
