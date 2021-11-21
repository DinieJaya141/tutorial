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
        return $this->response->redirect('');
    }

    public function signupAction()
    {
        if ($this->session->has('auth')) {
            return $this->response->redirect('');
        }
    }

    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->response->redirect('');
        }

        $user = new Users();
        $user->email = $this->request->getPost('userEmail', 'email');
        $user->password = $this->request->getPost('userPassword');
        $user->username = $this->request->getPost('userName');
        $user->contact = $this->request->getPost('userContact');
        $user->first_name = $this->request->getPost('userFirstName');
        $user->last_name = $this->request->getPost('userLastName');

        $validity_check = TRUE;

        $does_email_exist = Users::findFirst(
            [
                "email = :email:",
                'bind' => [
                    'email' => $user->email,
                ]
            ]
        );

        $does_username_exist = Users::findFirst(
            [
                "username = :username:",
                'bind' => [
                    'username' => $user->username,
                ]
            ]
        );

        if ($does_email_exist !== false) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Email already in use.');
            $validity_check = FALSE;
        } else if (strlen($user->email) > 50) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Email is too long, it cannot be longer than 50 characters.');
            $validity_check = FALSE;
        }

        if (strlen($user->password) < 8) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Password must be at least 8 characters long.');
            $validity_check = FALSE;
        } else {
            $user->password = sha1($user->password);
        }

        if ($does_username_exist !== false) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Username already in use. Try another one.');
            $validity_check = FALSE;
        } else if (strlen($user->username) > 50) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Username is too long, it cannot be longer than 50 characters.');
            $validity_check = FALSE;
        }

        if (strlen($user->contact) > 15) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Contact number too long.');
            $validity_check = FALSE;
        }

        if (strlen($user->first_name) > 50) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('First name too long, it cannot be longer than 50 characters.');
            $validity_check = FALSE;
        }

        if (strlen($user->last_name) > 50) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Last name too long, it cannot be longer than 50 characters.');
            $validity_check = FALSE;
        }

        if (!$validity_check) {
            return $this->response->redirect('users/signup');
        }

        if (!$user->save()) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            foreach ($user->getMessages() as $message) {
                $this->flashSession->notice($message);
            }

            return $this->response->redirect('users/signup');
        }

        $this->session->set('success', TRUE);
        $this->session->set('email', $user->email);
        return $this->response->redirect("users/success");
    }

    public function successAction() 
    {
        if (!$this->session->get('success') || $this->session->has('auth')) {
            $this->response->redirect("users/login");
        }

        $this->session->set('success', FALSE);

        TestMailer::composeSignUpMail($this->session->get('email'));
        $this->session->remove('email');
    }

    public function loginAction()
    {
        if ($this->session->has('auth')) {
            return $this->response->redirect('');
        }
    }

    public function accountAction()
    {
        if (!$this->session->has('auth')) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'info');
            $this->flashSession->notice('You need to login first.');
            return $this->response->redirect('users/login');
        }
    }

    public function editAction()
    {
        if (!$this->session->has('auth')) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'info');
            $this->flashSession->notice('You need to login first.');
            return $this->response->redirect('users/login');
        }
    }

    public function editusernameAction() 
    {
        if (!$this->request->isPost()) {
           return $this->response->redirect('');
        }

        $user = $this->session->get('user');
        $username = $this->request->getPost('usernameInput');

        if ($username == $user->username) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('That is already your username.');
            return $this->response->redirect('users/edit');
        }
        
        $does_username_exist = Users::findFirst(
            [
                "username = :username:",
                'bind' => [
                    'username'    => $username,
                ]
            ]
        );

        if ($does_username_exist !== FALSE) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Username already in use. Try another one.');
            return $this->response->redirect('users/edit');
        } else if (strlen($username) > 50) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Username is too long, it cannot be longer than 50 characters.');
            return $this->response->redirect('users/edit');
        }

        $user->username = $username;
        $user->save();
        $this->session->set('user', $user);

        $this->session->set('flash', TRUE);
        $this->session->set('flash_type', 'success');
        $this->flashSession->notice('Username changed successfully.');
        return $this->response->redirect('users/edit');
    }

    public function editfirstnameAction() 
    {
        if (!$this->request->isPost()) {
           return $this->response->redirect('');
        }

        $user = $this->session->get('user');
        $firstName = $this->request->getPost('firstnameInput');

        if (strlen($firstName) > 50) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('First name is too long, it cannot be longer than 50 characters.');
            return $this->response->redirect('users/edit');
        } else if ($firstName == $user->first_name) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('That is already your first name.');
            return $this->response->redirect('users/edit');
        }

        $user->first_name = $firstName;
        $user->save();
        $this->session->set('user', $user);

        $this->session->set('flash', TRUE);
        $this->session->set('flash_type', 'success');
        $this->flashSession->notice('First name changed successfully.');
        return $this->response->redirect('users/edit');
    }

    public function editlastnameAction() 
    {
        if (!$this->request->isPost()) {
           return $this->response->redirect('');
        }

        $user = $this->session->get('user');
        $lastName = $this->request->getPost('lastnameInput');

        if (strlen($lastName) > 50) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Last name is too long, it cannot be longer than 50 characters.');
            return $this->response->redirect('users/edit');
        } else if ($lastName == $user->last_name) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('That is already your last name.');
            return $this->response->redirect('users/edit');
        }

        $user->last_name = $lastName;
        $user->save();
        $this->session->set('user', $user);

        $this->session->set('flash', TRUE);
        $this->session->set('flash_type', 'success');
        $this->flashSession->notice('Last name changed successfully.');
        return $this->response->redirect('users/edit');
    }

    public function editcontactAction() 
    {
        if (!$this->request->isPost()) {
           return $this->response->redirect('');
        }

        $user = $this->session->get('user');
        $contact = $this->request->getPost('contactInput');

        if (strlen($contact) > 15) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Contact number is too long, it cannot be longer than 15 characters.');
            return $this->response->redirect('users/edit');
        } else if ($contact == $user->contact) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('That is already your current contact number.');
            return $this->response->redirect('users/edit');
        }

        $user->contact = $contact;
        $user->save();
        $this->session->set('user', $user);

        $this->session->set('flash', TRUE);
        $this->session->set('flash_type', 'success');
        $this->flashSession->notice('Contact number changed successfully.');
        return $this->response->redirect('users/edit');
    }

    public function editpasswordAction()
    {
        if (!$this->session->has('auth')) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'info');
            $this->flashSession->notice('You need to login first.');
            return $this->response->redirect('users/login');
        }
    }

    public function editpasswordsubmitAction() 
    {
        if (!$this->request->isPost()) {
           return $this->response->redirect('');
        }

        $user = $this->session->get('user');

        $password = $this->request->getPost('userPassword');

        if (strlen($password) < 8) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Password must be at least 8 characters long.');
            return $this->response->redirect('users/editpassword');
        }

        $user->password = sha1($this->request->getPost('userPassword'));
        $user->save();

        TestMailer::composePasswordChangeMail($this->session->get('email'));

        $this->session->set('flash', TRUE);
        $this->session->set('flash_type', 'success');
        $this->flashSession->notice('Password changed successfully.');
        return $this->response->redirect('users/account');
    }

    public function deleteAction()
    {
        if (!$this->session->has('auth')) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'info');
            $this->flashSession->notice('You need to login first.');
            return $this->response->redirect('users/login');
        }
    }

    public function deletesubmitAction()
    {
        $previous_url = $_SERVER['HTTP_REFERER'];

        if ($previous_url != 'http://localhost:8000/users/delete') {
            return $this->response->redirect('');
        }

        $id = $this->session->get('userid');
        $user = Users::findFirstByid($id);
        if (!$user) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');
            $this->flashSession->notice('Account deletion failed. Contact administration.');
            return $this->response->redirect('users/account');
        }

        if (!$user->delete()) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'danger');

            foreach ($user->getMessages() as $message) {
                $this->flashSession->notice($message);
            }
           
            return $this->response->redirect('users/account');
        }

        $this->session->destroy();
        return $this->response->redirect("users/deletesuccess");
    }

    public function deletesuccessAction() 
    {
        $previous_url = $_SERVER['HTTP_REFERER'];

        if ($previous_url != 'http://localhost:8000/users/delete') {
            return $this->response->redirect("");
        }
    }

    public function orderhistoryAction()
    {
        if (!$this->session->has('auth')) {
            $this->session->set('flash', TRUE);
            $this->session->set('flash_type', 'info');
            $this->flashSession->notice('You need to login first.');
            return $this->response->redirect('users/login');
        }
    }

}
