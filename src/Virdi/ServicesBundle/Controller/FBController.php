<?php

namespace Virdi\ServicesBundle\Controller;

/**
 * Description of FBController
 *
 * @author Savdeep.Singh
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Virdi\ServicesBundle\Resources\model\ServiceConstants;

/**
 * @Route("/fb")
 */
class FBController extends Controller {

    /**
     * @Route("/fetchProfile")
     */
    public function fetchProfile() {
        FacebookSession::setDefaultApplication(ServiceConstants::FB_APP_ID, ServiceConstants::FB_Secret_Key);
        session_start();
        $session = null;
        $arrParams = array();
        $helper = new FacebookRedirectLoginHelper('http://savdeep.me/localServices/web/app_dev.php/fb/fetchProfile');
        try {
            $session = $helper->getSessionFromRedirect();
            try {

                $user_profile = (new FacebookRequest(
                        $session, 'GET', '/me'
                        ))->execute()->getGraphObject(GraphUser::className());

                //echo "Name: " . $user_profile->getName();
                //print_r($user_profile);die;
            } catch (FacebookRequestException $e) {

                echo "Exception occured, code: " . $e->getCode();
                echo " with message: " . $e->getMessage();
            }
        } catch (FacebookRequestException $ex) {
            // When Facebook returns an error
            echo $ex->getMessage();
        } catch (\Exception $ex) {
            // When validation fails or other local issues
            echo $ex->getMessage();
        }
        return $this->render('VirdiServicesBundle:FB:welcome.html.twig', array('user' => $user_profile));
    }

    /**
     * @Route("/showLoginPage")
     */
    public function showLoginPage() {
        //echo "here";die;
        FacebookSession::setDefaultApplication(ServiceConstants::FB_APP_ID, ServiceConstants::FB_Secret_Key);
        session_start();
        $helper = new FacebookRedirectLoginHelper('http://savdeep.me/localServices/web/app_dev.php/fb/fetchProfile');

        $loginUrl = $helper->getLoginUrl();
        //$loginUrl="www.google.com";
        //echo "in showLoginPage";
        return $this->render('VirdiServicesBundle:FB:login.html.twig', array('loginUrl' => $loginUrl));
        //return new Response("working");
    }

}
