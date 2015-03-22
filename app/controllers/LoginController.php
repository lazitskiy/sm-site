<?php

/**
 * User: vaso
 * Date: 23.03.15
 * Time: 0:23
 */
class LoginController extends BaseController
{

    function __construct()
    {
        parent::__construct();
    }


    public function indexAction()
    {
        $this->set('title', $this->get('_')['Login']);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/login/index.php');

    }

    public function registerAction()
    {

        $this->set('title', $this->get('_')['Register']);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/login/register.php');
    }

    public function forgotPasswordAction()
    {
        $this->set('title', $this->get('_')['ForgotPassword']);

        echo $this->render($this->get('_header'));
        echo $this->render('/app/view/login/forgot-password.php');
    }
}

