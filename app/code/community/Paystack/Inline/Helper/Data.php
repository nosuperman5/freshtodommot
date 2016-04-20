<?php
class Paystack_Inline_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_TEST_MODE        = 'payment/paystack_inline/test_mode';

    const XML_PATH_PUBLIC_KEY_LIVE  = 'payment/paystack_inline/public_key_live';
    const XML_PATH_SECRET_KEY_LIVE  = 'payment/paystack_inline/secret_key_live';
    const XML_PATH_PUBLIC_KEY_TEST  = 'payment/paystack_inline/public_key_test';
    const XML_PATH_SECRET_KEY_TEST  = 'payment/paystack_inline/secret_key_test';

    function getPublicKey(){
        if(Mage::getStoreConfig(Paystack_Inline_Helper_Data::XML_PATH_TEST_MODE)){
            return Mage::getStoreConfig(Paystack_Inline_Helper_Data::XML_PATH_PUBLIC_KEY_TEST);
        } else{
            return Mage::getStoreConfig(Paystack_Inline_Helper_Data::XML_PATH_PUBLIC_KEY_LIVE);
        }
    }
    
    function getSecretKey(){
        if(Mage::getStoreConfig(Paystack_Inline_Helper_Data::XML_PATH_TEST_MODE)){
            return Mage::getStoreConfig(Paystack_Inline_Helper_Data::XML_PATH_SECRET_KEY_TEST);
        } else{
            return Mage::getStoreConfig(Paystack_Inline_Helper_Data::XML_PATH_SECRET_KEY_LIVE);
        }
    }
    
    function verifyTransaction($trxref)
    {
        $ch = curl_init();
        $transactionStatus = new stdClass();

        // set url
        curl_setopt($ch, CURLOPT_URL, "https://api.paystack.co/transaction/verify/" . rawurlencode($trxref));

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer '. Paystack_Inline_Helper_Data::getSecretKey()
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        
        // Make sure CURL_SSLVERSION_TLSv1_2 is defined as 6
        // cURL must be able to use TLSv1.2 to connect
        // to Paystack servers
        if (!defined('CURL_SSLVERSION_TLSv1_2')) {
            define('CURL_SSLVERSION_TLSv1_2', 6);
        }
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        
        // exec the cURL
        $response = curl_exec($ch);
        
        // should be 0
        if (curl_errno($ch)) {   
            // curl ended with an error
            $transactionStatus->error = "cURL said:" . curl_error($ch);
            curl_close($ch);
        } else {

            //close connection
            curl_close($ch);

            // Then, after your curl_exec call:
            $body = json_decode($response);
            if(!$body->status){
                // paystack has an error message for us
                $transactionStatus->error = "Paystack API said: " . $body->message;
            } else {
                // get body returned by Paystack API
                $transactionStatus = $body->data;
            }
        }

        return $transactionStatus;
    }
    
    function getFormParams() 
    {
        $order = new Mage_Sales_Model_Order();
        $orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        
        // return blank params if no order is found
        if(!$orderId){
            return array();
        }
        $order->loadByIncrementId($orderId);

        // get an email for this transaction
        $billing  = $order->getBillingAddress();
        if ($order->getBillingAddress()->getEmail()) {
            $email = $order->getBillingAddress()->getEmail();
        } else {
            $email = $order->getCustomerEmail();
        }

        $params = array(
            'key'         => Paystack_Inline_Helper_Data::getPublicKey(),
            'orderId'     => $orderId,
            'nextUrl'     => Mage::getUrl('paystack/payment/response', array('_query'=> array('orderId' => $orderId))),
            'cancelUrl'   => Mage::getUrl('paystack/payment/cancel'),
            'amount'      => round($order->getGrandTotal(), 2) * 100,
            'currency'    => $order->getOrderCurrencyCode(),
            'firstname'   => $billing->getFirstname(),
            'lastname'    => $billing->getLastname(),
            'address'     => $billing->getStreet(-1),
            'email'       => $email,
            'phone'       => $billing->getTelephone(),
            'remarks'     => $this->__('Order ID: ') . $orderId
        );
        
        return $params;
    }
}
