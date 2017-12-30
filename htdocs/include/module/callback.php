<?php
class module_callback extends module
{
	protected function action_index()
	{
		if (!empty($_POST)) {
			$error = array();
			
			$callback_person = init_string( 'callback_person' );
			$callback_phone = init_string( 'callback_phone' );
			$captcha = init_string( 'g-recaptcha-response' );
			
			if (is_empty($callback_person)) {
				$error['callback_person'] = 'Не заполнено обязательное поле';
			}
			if (is_empty($callback_phone)) {
				$error['callback_phone'] = 'Не заполнено обязательное поле';
			}
            if (!$this->check_captcha($captcha)) {
                $error['captcha'] = 'Вы не прошли проверку';
            }
			
			// Отправка сообщения
			if (!$error) {
				$from_email = get_preference('from_email');
				$from_name = get_preference('from_name');
				
				$callback_email = get_preference('callback_email');
				$callback_subject = get_preference('callback_subject');
				
				$callback_view = new view();
				$callback_message = $callback_view->fetch('module/callback/message');
				
				sendmail::send($callback_email, $from_email, $from_name, $callback_subject, $callback_message);
				
				session::flash('success', true);
				
				header('Location: ' . $_SERVER['HTTP_REFERER']);
				exit;
			}
			
			$this->view->assign('error', $error);
		}
		
		$this->content = $this->view->fetch('module/callback/form');
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

	// Отключаем кеширование
	protected function get_cache_key()
	{
		return false;
	}
}