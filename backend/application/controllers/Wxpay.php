<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wxpay extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('wxpay_model');
        //$this->load->model('wxpay');

    }

    public function index() {
        //????
        $this->smarty['wxPayUrl'] = $this->wxpay_model->retWxPayUrl();
        $this->displayView('index.tpl');
    }

    /**
     * ????????????????code??????
     * @param  [type] $orderId ????id
     * @return [type]          [description]
     */
    public function confirm($orderId) {
        //?????????
        $this->ensureLogin();
        //????????????
        $order = $this->wxpay_model->get($orderId);
        //???????????
        $this->_verifyUser($order);

        //????????????
        $orderData = $this->returnOrderData[$orderId];
        //??jsApi??????
        $wxJsApiData = $this->wxpay_model->wxPayJsApi($orderData);
        //???????????js???
        $this->smartyData['wxJsApiData'] = json_encode($wxJsApiData, JSON_UNESCAPED_UNICODE);
        $this->smartyData['order'] = $orderData;
        $this->displayView('confirm.tpl');

    }
    /**
     * ??????
     * @return [type] [description]
     */
    public function pay_callback() {
        $postData = '';
        if (file_get_contents("php://input")) {
            $postData = file_get_contents("php://input");
        } else {
            return;
        }
        $payInfo = array();
        $notify = $this->wxpay_model->wxPayNotify($postData);

        if ($notify->checkSign == TRUE) {
            if ($notify->data['return_code'] == 'FAIL') {
                $payInfo['status'] = FALSE;
                $payInfo['msg'] = '????';
            } elseif ($notify->data['result_code'] == 'FAIL') {
                $payInfo['status'] = FALSE;
                $payInfo['msg'] = '????';
            } else {
                $payInfo['status'] = TRUE;
                $payInfo['msg'] = '????';
                $payInfo['sn']=substr($notify->data['out_trade_no'],8);
                $payInfo['order_no'] = $notify->data['out_trade_no'];
                $payInfo['platform_no']=$notify->data['transaction_id'];
                $payInfo['attach']=$notify->data['attach'];
                $payInfo['fee']=$notify->data['cash_fee'];
                $payInfo['currency']=$notify->data['fee_type'];
                $payInfo['user_sign']=$notify->data['openid'];
            }
        }
        $returnXml = $notify->returnXml();

        echo $returnXml;

        $this->load->library('RedisCache');
        if($payInfo['status']){
            //?????????????
            $this->model->order->onPaySuccess($payInfo['sn'], $payInfo['order_no'], $payInfo['platform_no'],'', $payInfo['user_sign'], $payInfo);
            $this->redis->RedisCache->set('order:payNo:'.$payInfo['order_no'],'OK',5000);
        }else{
            //?????????????
            $this->model->order->onPayFailure($payInfo['sn'], $payInfo['order_no'], $payInfo['platform_no'],'', $payInfo['user_sign'], $payInfo, '?????? ['.$payInfo['msg'].']');
        }
    }

    /**
     * ??????????
     * @param  [type] $orderId ???
     * @param  string $data    ??????$data???????$orderData???????????
     * @return [type]          [description]
     */
    public function returnOrderData($orderId, $data = '') {
        //??????
        $order = $this->wxpay_model->get($orderId);
        if (0 === count($order)) return false;
        if (empty($data)) {
            $this->load->library('RedisCache');
            //?????redis?????
            $orderData = $this->rediscache->getJson("order:orderData:".$orderId);
            if (empty($orderData)) {
                //??redis????????????
                $this->load->model('order_model');
                $order = $this->order_model->get($orderId);
                if (0 === count($order)) {
                    return false;
                }
                $data = $order;
            } else {
                //??redis????????????
                return $orderData;
            }
        }

        //???????????
        $orderData['id'] = $data['id'];
        $orderData['fee'] = $data['fee'];

        //?????????
        $orderData['user_id'] = $data['user_id'];
        $orderData['sn'] = $data['cn'];
        //??????
        $orderData['order_no'] = substr(md5($data['sn'].$data['fee']), 8, 8).$data['sn'];
        $orderData['fee'] = $data['fee'];
        $orderData['time'] = $data['time'];
        $orderData['goods_name'] = $data['goods_name'];
        $orderData['attach'] = $data['attach'];

        //??????redis??
        $this->rediscache->set("order:orderData:".$orderId, $orderData, 3600*24);
        //???????redis???????????????
        $this->rediscache->set("order:payNo:".$orderData['order_no'], "NO", 3600*24);

        return $orderData;
    }

    private function _verifyUser($order) {
        if (empty($order)) show_404();
        if (0 === count($order)) show_404();
        //?????????id?????????id
        if ($order['user_id'] == $this->uid) return;
        show_error('?????????');
    }

}