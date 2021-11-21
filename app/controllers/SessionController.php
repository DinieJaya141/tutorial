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
                'mail' => $user->email,
            ]
        );

        $this->session->set('email', $user->email);
        $this->session->set('user', $user);
        $this->session->set('book_date', '');
        $this->session->set('discount_codes', []);
        $this->session->set('discount_rate', 1);
        $this->session->set('flash', FALSE);
        $this->session->set('flash_type', '');
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

            if ($user !== FALSE) {
                $this->_registerSession($user);
                $this->session->set('flash', TRUE);
                $this->session->set('flash_type', 'success');
                $this->flashSession->notice('Successfully logged in. Welcome, ' . htmlspecialchars($user->username) . '.');
                return $this->response->redirect('');
            } else {
                $this->session->set('flash', TRUE);
                $this->session->set('flash_type', 'danger');
                $this->flashSession->notice('Wrong credentials, try again.');
                return $this->response->redirect('users/login');
            }
        } else {
            return $this->response->redirect('');
        }
    }

    public function endAction()
    {
        $this->session->destroy();
        return $this->response->redirect('');
    }
}