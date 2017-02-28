<?php
class module_subscribe extends module
{
    public $type_list = array(
        '1' => 'Оптовая', '2' => 'Розничная',
    );
    
    protected function action_index()
    {
        if (!empty($_POST)) {
            $error = array();
            
            if (!isset($_POST['email']) || is_empty($_POST['email'])) {
                $error['email'] = 'Не заполнено обязательное поле';
            }
            if (!isset($error['email']) && !valid::factory('email')->check($_POST['email'])) {
                $error['email'] = 'Поле заполнено некорректно';
            }
            if (!isset($_POST['person']) || is_empty($_POST['person'])) {
                $error['person'] = 'Не заполнено обязательное поле';
            }
            
            if (isset($_POST['name']) && !is_empty($_POST['name'])) {
                if (!isset($_POST['type']) || is_empty($_POST['type'])) {
                    $error['type'] = 'Не заполнено обязательное поле';
                }
                if (!isset($error['type']) && !in_array($_POST['type'], array_keys($this->type_list))) {
                    $error['type'] = 'Поле заполнено некорректно';
                }
                if (!isset($_POST['phone']) || is_empty($_POST['phone'])) {
                    $error['phone'] = 'Не заполнено обязательное поле';
                }
                if (!isset($_POST['fax']) || is_empty($_POST['fax'])) {
                    $error['fax'] = 'Не заполнено обязательное поле';
                }
            }
            if (!isset($error['captcha']) && !$this->check_captcha($_POST['g-recaptcha-response'])) {
                $error['captcha'] = 'Вы не прошли проверку';
            }
            
            // Отправка сообщения
            if (!$error) {
                $from_email = get_preference('from_email');
                $from_name = get_preference('from_name');
                
                $subscribe_email = get_preference('subscribe_email');
                $subscribe_subject = get_preference('subscribe_subject');
                
                $subscribe_view = new view();
                $subscribe_view->assign('type_list', $this->type_list);
                $subscribe_message = $subscribe_view->fetch('module/subscribe/message');
                
                sendmail::send($subscribe_email, $from_email, $from_name, $subscribe_subject, $subscribe_message);
                
                session::flash('success', true);
                
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
            
            $this->view->assign('error', $error);
        }
        
        $this->view->assign('type_list', $this->type_list);
        $this->content = $this->view->fetch('module/subscribe/form');
    }
    
    protected function check_captcha($response)
    {
        $url = get_preference('recaptcha_url');
        $secret = get_preference('recaptcha_secret');
        $remoteip = $_SERVER['REMOTE_ADDR'];
        
        $data = compact('secret', 'response', 'remoteip');
        
        $curl = new curl();
        $result = $curl->post($url, $data);
        $result = json_decode($result, true);
        
        return isset($result['success']) && $result['success'];
    }
    
    protected function action_registration()
    {
        $this->content = $this->view->fetch('module/subscribe/registration');
    }
    
    // Отключаем кеширование
    protected function get_cache_key()
    {
        return false;
    }
}