<?php

class SessionController extends ControllerBase
{

    private function _registerSession($user)
    {
        $this->session->set(
            'auth',
            [
                'id'   => $user->id,
                'name' => $user->username,
            ]
        );

        $this->session->set('userid', $user->id,);
        $this->session->set('username', $user->username,);
    }

    public function startAction()
    {
        if ($this->request->isPost()) {
            $email    = $this->request->getPost('userEmail');
            $password = $this->request->getPost('userPassword');

            $user = Users::findFirst(
                [
                    "email = :email: AND password = :password:",
                    'bind' => [
                        'email'    => $email,
                        'password' => sha1($password),
                    ]
                ]
            );

            if ($user !== false) {
                $this->_registerSession($user);

                $this->flash->success(
                    'Welcome ' . htmlspecialchars($user->username)
                );

                return $this->response->redirect("index");
            }

            $this->flash->error(
                'Incorrect credentials, try again.'
            );
        }

        return $this->dispatcher->forward(
            [
                'controller' => 'users',
                'action'     => 'login',
            ]
        );
    }

    public function endAction()
    {
        //$this->flashSession->success("Exit");
        $this->session->destroy();
        return $this->response->redirect("index");
    }
}