<?php

/* * ***
 * Version: V1.0.1
 *
 * Description of Auth Controller
 *
 * @author TechArise Team
 *
 * @email  info@techarise.com
 *
 * *** */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth extends MY_Controller {

    public function __construct() {
        parent::__construct();
        //load model
        $this->load->model('Auth_model', 'auth');
        $this->load->model('Mail', 'mail');
        $this->load->library('form_validation');
    }

    // user profile
    public function index() {
        if ($this->session->userdata('ci_session_key_generate') == FALSE) {
            redirect('signin'); // the user is not logged in, redirect them!
        } else {
            $data = array();
            $data['metaDescription'] = 'User Profile';
            $data['metaKeywords'] = 'User Profile';
            $data['title'] = "User Profile - TechArise";
            $data['breadcrumbs'] = array('Profile' => '#');
            $sessionArray = $this->session->userdata('ci_seesion_key');
            $this->auth->setUserID($sessionArray['user_id']);
            $data['userInfo'] = $this->auth->getUserDetails();
            $this->page_construct('auth/index', $data);
        }
    }

    // registration method
    public function register() {
        $data = array();
        $data['metaDescription'] = 'New User Registration';
        $data['metaKeywords'] = 'New User Registration';
        $data['title'] = "Registration - TechArise";
        $data['breadcrumbs'] = array('Registration' => '#');
        $this->page_construct('auth/register', $data);
    }

    // edit method
    public function edit() {
        if ($this->session->userdata('ci_session_key_generate') == FALSE) {
            redirect('signin'); // the user is not logged in, redirect them!
        } else {
            $data = array();
            $data['metaDescription'] = 'Update Profile';
            $data['metaKeywords'] = 'Update Profile';
            $data['title'] = "Update Profile - TechArise";
            $data['breadcrumbs'] = array('Update Profile' => '#');
            $sessionArray = $this->session->userdata('ci_seesion_key');
            $this->auth->setUserID($sessionArray['user_id']);
            $data['userInfo'] = $this->auth->getUserDetails();
            $this->page_construct('auth/edit', $data);
        }
    }

    // login method
    public function login() {
        if (!empty($this->input->get('usid'))) {
            $verificationCode = urldecode(base64_decode($this->input->get('usid')));
            $this->auth->setVerificationCode($verificationCode);
            $this->auth->activate();
        }
        $data = array();
        $data['metaDescription'] = 'Login';
        $data['metaKeywords'] = 'Login';
        $data['title'] = "Login - TechArise";
        $data['breadcrumbs'] = array('Login' => '#');
        $this->page_construct('auth/login', $data);
    }

    // edit method
    public function changepwd() {
        if ($this->session->userdata('ci_session_key_generate') == FALSE) {
            redirect('signin'); // the user is not logged in, redirect them!
        } else {
            $data = array();
            $data['metaDescription'] = 'New User Registration';
            $data['metaKeywords'] = 'Change Password';
            $data['title'] = "Change Password - TechArise";
            $data['breadcrumbs'] = array('Change Password' => '#');
            $this->page_construct('auth/changepwd', $data);
        }
    }

    //forgot password method
    public function forgotpassword() {
        if ($this->session->userdata('ci_session_key_generate') == TRUE) {
            redirect('profile'); // the user is logged in, redirect them!
        } else {
            $data['metaDescription'] = 'Forgot Password';
            $data['metaKeywords'] = 'Member, forgot password';
            $data['title'] = "Forgot Password - SoOLEGAL";
            $data['breadcrumbs'] = array('Forgot Password' => '#');
            $this->page_construct('auth/forgotpwd', $data);
        }
    }

    // action create user method
    public function actionCreate() {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('contact_no', 'Contact No', 'required|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('dob', 'Date of Birth(DD-MM-YYYY)', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->register();
        } else {
            $firstName = $this->input->post('first_name');
            $lastName = $this->input->post('last_name');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $contactNo = $this->input->post('contact_no');
            $dob = $this->input->post('dob');
            $address = $this->input->post('address');
            $timeStamp = time();
            $status = 0;
            $verificationCode = uniqid();
            $verificationLink = site_url() . 'signin?usid=' . urlencode(base64_encode($verificationCode));
            $userName = $this->mail->generateUnique('users', trim($firstName . $lastName), 'user_name', NULL, NULL);
            $this->auth->setUserName($userName);
            $this->auth->setFirstName(trim($firstName));
            $this->auth->setLastName(trim($lastName));
            $this->auth->setEmail($email);
            $this->auth->setPassword($password);
            $this->auth->setContactNo($contactNo);
            $this->auth->setAddress($address);
            $this->auth->setDOB($dob);
            $this->auth->setVerificationCode($verificationCode);
            $this->auth->setTimeStamp($timeStamp);
            $this->auth->setStatus($status);
            $chk = $this->auth->create();
            if ($chk === TRUE) {
                $this->load->library('encrypt');
                $mailData = array('topMsg' => 'Hi', 'bodyMsg' => 'Congratulations, Your registration has been successfully submitted.', 'thanksMsg' => SITE_DELIMETER_MSG, 'delimeter' => SITE_DELIMETER, 'verificationLink' => $verificationLink);
                $this->mail->setMailTo($email);
                $this->mail->setMailFrom(MAIL_FROM);
                $this->mail->setMailSubject('User Registeration!');
                $this->mail->setMailContent($mailData);
                $this->mail->setTemplateName('verification');
                $this->mail->setTemplatePath('mailTemplate/');
                $chkStatus = $this->mail->sendMail(MAILING_SERVICE_PROVIDER);
                if ($chkStatus === TRUE) {
                    redirect('signin');
                } else {
                    echo 'Error';
                }
            } else {
                
            }
        }
    }

    // action update user 
    public function editUser() {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('contact_no', 'Contact No', 'required|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('dob', 'Date of Birth(DD-MM-YYYY)', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->edit();
        } else {
            $firstName = $this->input->post('first_name');
            $lastName = $this->input->post('last_name');
            $contactNo = $this->input->post('contact_no');
            $dob = $this->input->post('dob');
            $address = $this->input->post('address');
            $timeStamp = time();
            $sessionArray = $this->session->userdata('ci_seesion_key');
            $this->auth->setUserID($sessionArray['user_id']);
            $this->auth->setFirstName(trim($firstName));
            $this->auth->setLastName(trim($lastName));
            $this->auth->setContactNo($contactNo);
            $this->auth->setAddress($address);
            $this->auth->setDOB($dob);
            $this->auth->setTimeStamp($timeStamp);
            $status = $this->auth->update();
            if ($status == TRUE) {
                redirect('profile');
            }
        }
    }

    // action login method
    function doLogin() {
        // Check form  validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_name', 'User Name/Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to login page
            $this->login;
        } else {
            $sessArray = array();
            //Field validation succeeded.  Validate against database
            $username = $this->input->post('user_name');
            $password = $this->input->post('password');

            $this->auth->setUserName($username);
            $this->auth->setPassword($password);
            //query the database
            $result = $this->auth->login();

            if (!empty($result) && count($result) > 0) {
                foreach ($result as $row) {
                    $authArray = array(
                        'user_id' => $row->user_id,
                        'user_name' => $row->user_name,
                        'email' => $row->email
                    );
                    $this->session->set_userdata('ci_session_key_generate', TRUE);
                    $this->session->set_userdata('ci_seesion_key', $authArray);
                }
                redirect('profile');
            } else {
                redirect('signin?msg=1');
            }
        }
    }

    public function actionChangePwd() {
        $this->form_validation->set_rules('change_pwd_password', 'Password', 'trim|required|min_length[8]');
        $this->form_validation->set_rules('change_pwd_confirm_password', 'Password Confirmation', 'trim|required|matches[change_pwd_password]');
        if ($this->form_validation->run() == FALSE) {
            $this->changepwd();
        } else {
            $change_pwd_password = $this->input->post('change_pwd_password');
            $sessionArray = $this->session->userdata('ci_seesion_key');
            $this->auth->setUserID($sessionArray['user_id']);
            $this->auth->setPassword($change_pwd_password);
            $status = $this->auth->changePassword();
            if ($status == TRUE) {
                redirect('profile');
            }
        }
    }

    //action forgot password method
    public function actionForgotPassword() {
        $this->form_validation->set_rules('forgot_email', 'Your Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to Forgot Password page
            $this->forgotpassword();
        } else {
            $login = site_url() . 'signin';
            $email = $this->input->post('forgot_email');
            $this->auth->setEmail($email);
            $pass = $this->generateRandomPassword(8);
            $this->auth->setPassword($pass);
            $status = $this->auth->updateForgotPassword();
            if ($status == TRUE) {
                $this->load->library('encrypt');
                $mailData = array('topMsg' => 'Hi', 'bodyMsg' => 'Your password has been reset successfully!.', 'thanksMsg' => SITE_DELIMETER_MSG, 'delimeter' => SITE_DELIMETER, 'loginLink' => $login, 'pwd' => $pass, 'username' => $email);
                $this->mail->setMailTo($email);
                $this->mail->setMailFrom(MAIL_FROM);
                $this->mail->setMailSubject('Forgot Password!');
                $this->mail->setMailContent($mailData);
                $this->mail->setTemplateName('sendpwd');
                $this->mail->setTemplatePath('mailTemplate/');
                $chkStatus = $this->mail->sendMail(MAILING_SERVICE_PROVIDER);
                if ($chkStatus === TRUE) {
                    redirect('forgotpwd?msg=2');
                } else {
                    redirect('forgotpwd?msg=1');
                }
            } else {
                redirect('forgotpwd?msg=1');
            }
        }
    }

    //generate random password
    public function generateRandomPassword($length = 10) {
        $alphabets = range('a', 'z');
        $numbers = range('0', '9');
        $final_array = array_merge($alphabets, $numbers);
        $password = '';
        while ($length--) {
            $key = array_rand($final_array);
            $password .= $final_array[$key];
        }
        return $password;
    }

    //logout method
    public function logout() {
        $this->session->unset_userdata('ci_seesion_key');
        $this->session->unset_userdata('ci_session_key_generate');
        $this->session->sess_destroy();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        redirect('signin');
    }

}

?>
