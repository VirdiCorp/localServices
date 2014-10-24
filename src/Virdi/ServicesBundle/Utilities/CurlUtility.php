<?php

namespace Virdi\ServicesBundle\Utilities;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Description of CurlUtility
 *
 * @author Savdeep.Singh
 */


/**
 * @DI\Service("curl.util")
 */
class CurlUtility {

    public function makeCurlCall($url, $returnTransfer, $conTimeOut, $timeOut,$headers) {
        $output = FALSE;
        //echo __DIR__;die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returnTransfer);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $conTimeOut);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        if ($headers != FALSE) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        //curl_setopt($ch, CURLOPT_SSH, __DIR__.'\curl-ca-bundle.crt');
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            $output = curl_error($ch);
        }
        curl_close($ch);
        return $output;
    }

}
