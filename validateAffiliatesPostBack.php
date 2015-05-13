<?php

    define("POSTBACK_SECRET","961c8cbcb174566f5d5297049c666d95"); // Use your postback secret (copied from your user preferences)

    if((isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST')) {
        $date = isset($_POST["date"]) ? $_POST["date"] : null;
        $trackId = isset($_POST["track-id"]) ? $_POST["track-id"] : null;
        $trackSubId = isset($_POST["track-subid"]) ? $_POST["track-subid"] : null;
        $transactionId = isset($_POST["transaction-id"]) ? $_POST["transaction-id"] : null;
        $amount = isset($_POST["amount"]) ? $_POST["amount"] : null;
        $currency = isset($_POST["currency"]) ? $_POST["currency"] : null;
        $country = isset($_POST["country"]) ? $_POST["country"] : null;
        $signature = isset($_POST["signature"]) ? $_POST["signature"] : null;
        $isValidSignature = signParameters(POSTBACK_SECRET, $_POST) == $_POST["signature"];
        if($isValidSignature) {
            // Signature valid
            echo "OK";
            /**
            * @todo Insert transaction in your database. Your code here.
            */
        } else {
            // Signature not valid, FAIL!
            header("HTTP/1.0 403 Forbidden");
            echo "KO - Invalid signature";
            exit;
        }
    } else {
        header("HTTP/1.0 400 Bad Request");
        echo "KO - Invalid method. Only POST allowed";
    }

    /**
     * @param $postbackSecret
     * @param array $params
     * @return string
     */
    function signParameters($postbackSecret, array $params) {
        return md5(
            $postbackSecret.
            @$params["date"].
            @$params["track-id"].
            @$params["track-subid"].
            @$params["transaction-id"].
            @$params["amount"].
            @$params["currency"].
            @$params["country"]
        );
    }
