<?php
/**
 * Created by PhpStorm.
 * User: adm
 * Date: 21.12.2018
 * Time: 15:57
 */

namespace common\components;


use Yii;
use yii\base\Exception;

class AuthAction extends \yii\authclient\AuthAction
{
    protected function authOAuth2($client)
    {
        $request = Yii::$app->getRequest();

        if (($error = $request->get('error')) !== null) {
            if ($error === 'access_denied' || $error === 'user_cancelled_login') {
                // user denied error
                return $this->authCancel($client);
            }
            // request error
            $errorMessage = $request->get('error_description', $request->get('error_message'));
            if ($errorMessage === null) {
                $errorMessage = http_build_query($request->get());
            }
            throw new Exception('Auth error: ' . $errorMessage);
        }

        // Get the access_token and save them to the session.
        if (($code = $request->get('code')) !== null) {
            $token = $client->fetchAccessToken($code);
            if (!empty($token)) {
                return $this->authSuccess($client);
            }
            return $this->authCancel($client);
        }
        $authClient = $request->get('authclient');
        if(in_array($authClient,['vkontakte'])){
            $url = $client->buildAuthUrl(['scope'=>'email']);
        }
        elseif($authClient == 'odnoklassniki'){
            $url = $client->buildAuthUrl(['scope'=>'GET_EMAIL']);
        }else {
            $url = $client->buildAuthUrl();
        }
        return Yii::$app->getResponse()->redirect($url);
    }
}