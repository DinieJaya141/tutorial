<?php
//namespace ;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
//use Users;

class UsersController extends ControllerBase
{
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    public function signupAction()
    {
        if ($this->session->has('auth')) {
            return $this->response->redirect("index");
        }
    }

    public function createAction()
    {
        
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                'controller' => "users",
                'action' => 'signup'
            ]);
            return;
        }

        $user = new Users();
        $user->email = $this->request->getPost("userEmail", "email");
        $user->password = $this->request->getPost("userPassword");
        $user->username = $this->request->getPost("userName");

        $validity_check = TRUE;

        $does_email_exist = Users::findFirst(
            [
                "email = :email:",
                'bind' => [
                    'email'    => $user->email,
                ]
            ]
        );

        $does_username_exist = Users::findFirst(
            [
                "username = :username:",
                'bind' => [
                    'username'    => $user->username,
                ]
            ]
        );

        if ($does_email_exist !== false) {
            $this->flash->error("Email already in use. Try another one.");
            $validity_check = FALSE;
        } else if (strlen($user->email) <= 0) {
            $this->flash->error("Email is required.");
            $validity_check = FALSE;
        } else if (strlen($user->email) > 50) {
            $this->flash->error("Email is too long, it cannot be longer than 50 characters.");
            $validity_check = FALSE;
        }

        if (strlen($user->password) < 8) {
            $this->flash->error("Password must be at least 8 characters long.");
            $validity_check = FALSE;
        } else {
            $user->password = sha1($user->password);
        }

        if ($does_username_exist !== false) {
            $this->flash->error("Username already in use. Try another one.");
            $validity_check = FALSE;
        } else if (strlen($user->username) <= 0) {
            $this->flash->error("Username is required.");
            $validity_check = FALSE;
        } else if (strlen($user->username) > 50) {
            $this->flash->error("Username is too long, it cannot be longer than 50 characters.");
            $validity_check = FALSE;
        }

        if (!$validity_check) {
            $this->dispatcher->forward([
                'controller' => "users",
                'action' => 'signup'
            ]);

            return;
        }

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "users",
                'action' => 'signup'
            ]);

            return;
        }

        $this->session->set('success', TRUE);
        return $this->response->redirect("users/success");
    }

    public function successAction() {
        if (!$this->session->get('success') || $this->session->has('auth')) {
            $this->response->redirect("users/login");
        } else {
            $this->session->set('success', FALSE);
        }
    }

    public function loginAction()
    {
        if ($this->session->has('auth')) {
            return $this->response->redirect("index");
        }
    }
}
