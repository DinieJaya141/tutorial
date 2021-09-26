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

    public function successAction() 
    {
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

    public function accountAction()
    {
        if (!$this->session->has('auth')) {
            return $this->response->redirect("index");
        }
    }

    public function editusernameAction()
    {
        if (!$this->session->has('auth')) {
            return $this->response->redirect("index");
        }
    }

    public function editusernamesubmitAction() 
    {
        if (!$this->request->isPost()) {
           return $this->response->redirect("index");
        }

        $id = $this->session->get('userid');
        $user = Users::findFirstByid($id);
        $username = $this->request->getPost("userName");
        $does_username_exist = Users::findFirst(
            [
                "username = :username:",
                'bind' => [
                    'username'    => $username,
                ]
            ]
        );

        $validity_check = TRUE;

        if ($username === $this->session->get("username")) {
            $this->flash->error("That is already your username.");
            $validity_check = FALSE;
        } else if ($does_username_exist !== false) {
            $this->flash->error("Username already in use. Try another one.");
            $validity_check = FALSE;
        } else if (strlen($username) > 50) {
            $this->flash->error("Username is too long, it cannot be longer than 50 characters.");
            $validity_check = FALSE;
        }

        if (!$validity_check) {
            $this->dispatcher->forward([
                'controller' => "users",
                'action' => 'editusername'
            ]);

            return;
        }

        $user->setUsername($this->request->getPost('userName'));
        $user->save();

        $this->session->set(
            'auth',
            [
                'name' => $user->username,
            ]
        );

        $this->session->set('username', $user->username);

        return $this->response->redirect("users/account");
    }

    public function editpasswordAction()
    {
        if (!$this->session->has('auth')) {
            return $this->response->redirect("index");
        }
    }

    public function editpasswordsubmitAction() 
    {
        if (!$this->request->isPost()) {
           return $this->response->redirect("index");
        }

        $id = $this->session->get('userid');
        $user = Users::findFirstByid($id);

        $password = $this->request->getPost("userPassword");

        if (strlen($password) < 8) {
            $this->flash->error("Password must be at least 8 characters long.");
            $this->dispatcher->forward([
                'controller' => "users",
                'action' => 'editpassword'
            ]);

            return;
        }

        $user->setPassword(sha1($this->request->getPost('userPassword')));
        $user->save();

        return $this->response->redirect("users/account");
    }

    public function deleteAction()
    {
        if (!$this->session->has('auth')) {
            return $this->response->redirect("index");
        }
    }

    public function deletesubmitAction()
    {
        $previous_url = $_SERVER['HTTP_REFERER'];

        if ($previous_url != 'http://localhost:8000/users/delete') {
            return $this->response->redirect("index");
        }

        $id = $this->session->get('userid');
        $user = Users::findFirstByid($id);
        if (!$user) {
            $this->flash->error("user was not found");

            $this->dispatcher->forward([
                'controller' => "users",
                'action' => 'index'
            ]);

            return;
        }

        if (!$user->delete()) {

            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward([
                'controller' => "users",
                'action' => 'delete'
            ]);

            return;
        }

        $this->session->destroy();
        return $this->response->redirect("users/deletesuccess");
    }

    public function deletesuccessAction() 
    {
        $previous_url = $_SERVER['HTTP_REFERER'];

        if ($previous_url != 'http://localhost:8000/users/delete') {
            return $this->response->redirect("index");
        }
    }

}
