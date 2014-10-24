<?php

namespace Virdi\ServicesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Virdi\ServicesBundle\Resources\model\ServiceConstants;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Description of LInController
 *
 * @author Savdeep.Singh
 */

/**
 * @Route("/lin")
 */
class LInController extends Controller {

    /**
     * @DI\Inject("curl.util")
     */
    public $curlUtil;

    /**
     * @Route("/showLoginPage")
     */
    public function showLoginPage() {
        //echo "here";die;
        //session_start();
        $redirectURI = "http://savdeep.me/localServices/web/app_dev.php/lin/fetchProfile";
        $loginUrl = "https://www.linkedin.com/uas/oauth2/authorization?response_type=code"
                . "&client_id=" . ServiceConstants::LIn_API_Key . "&scope=" . ServiceConstants::LIn_scope_full_profile
                . "&state=" . ServiceConstants::Lin_state_for_csrf . "&redirect_uri=" . $redirectURI;
        return $this->render('VirdiServicesBundle:LIn:login.html.twig', array('loginUrl' => $loginUrl));
    }

    /**
     * @Route("/fetchProfile")
     */
    public function fetchProfile() {
        $request = Request::createFromGlobals();
        if ($request->query->get('state') == ServiceConstants::Lin_state_for_csrf) {
            if (isset($request->error)) {
                echo $request->error . $request->error_description;
                die();
            }
            $auth_code = $request->query->get('code');
            //echo "Authenticated";
            //echo $auth_code;die;
            $redirectURI = "http://savdeep.me/localServices/web/app_dev.php/lin/fetchProfile";
            $url = "https://www.linkedin.com/uas/oauth2/accessToken?grant_type=authorization_code"
                    . "&code=" . $auth_code . "&redirect_uri=" . $redirectURI
                    . "&client_id=" . ServiceConstants::LIn_API_Key . "&client_secret=" . ServiceConstants::LIn_Secret_Key;
            $output = $this->curlUtil->makeCurlCall($url, 1, 10, 10, FALSE);
            $arrResponse = json_decode($output);
            //print_r($arrResponse);die;
            if (isset($arrResponse->access_token)) {
                $access_token = $arrResponse->access_token;
//                $url = "/v1/people/~";
//                $headers = array(
//                    "GET " . $url . " HTTP/1.1",
//                    "Host: api.linkedin.com",
//                    "Connection: Keep-Alive",
//                    "Authorization: Bearer " . $access_token
//                );
                //print_r($headers);die; 
//                $headers = array(
//                    'Authorization' => 'Bearer ' . $access_token
//                    //'x-li-format' => 'json', // Comment out to use XML
//                );
                $url = "https://api.linkedin.com/v1/people/~?oauth2_access_token=" . $access_token . "&format=json";
                $output = $this->curlUtil->makeCurlCall($url, 1, 10, 10, FALSE);
                //print_r($output);
                $user_profile = json_decode($output);
            }
        } else {
            echo "Error UnAuthorized request"; //Its an attaacke prevent
            die;
        }
        print_r($user_profile);
        die;
        /*
          stdClass Object
          (
          [firstName] => savdeep
          [headline] => Engineering Naukri.com at Info Edge India Ltd
          [lastName] => singh
          [siteStandardProfileRequest] => stdClass Object
          (
          [url] => https://www.linkedin.com/profile/view?id=43914003&authType=name&authToken=Bkx2&trk=api*a4114321*s4178951*
          )

          )
         */
        //return $this->render('VirdiServicesBundle:LIn:welcome.html.twig', array('user' => $user_profile));
    }

}
