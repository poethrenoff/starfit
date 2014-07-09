<?php
class admin_delivery extends admin
{
	protected function action_index()
	{
		$mail_count = db::select_cell( 'select count(*) from delivery_queue' );
		
		if ( !$mail_count )
			db::delete( 'delivery_body' );
		
		$prev_mail = db::select_row( 'select * from delivery_storage' );
		
		$this -> view -> assign( 'title', $this -> object_desc['title'] );
		$this -> view -> assign( 'mail_count', $mail_count );
		$this -> view -> assign( 'prev_mail', $prev_mail );
		
		$form_url = url_for( array( 'object' => 'delivery', 'action' => 'send' ) );
		$this -> view -> assign( 'form_url', $form_url );
		$cancel_url = url_for( array( 'object' => 'delivery', 'action' => 'clear' ) );
		$this -> view -> assign( 'cancel_url', $cancel_url );
		
		$this -> content = $this -> view -> fetch( 'admin/delivery/delivery' );
		
		$this -> store_state();
	}
	
	protected function action_send()
	{
		$email = init_string( 'email' );
		$name = init_string( 'name' );
		$subject = init_string( 'subject' );
		$message = init_string( 'message' );
		$type = init_string( 'type' );
		
		if ( $subject === '' )
			throw new AlarmException( 'Ошибка. Не заполнено поле "Тема рассылки".' );
		if ( $email === '' )
			throw new AlarmException( 'Ошибка. Не заполнено поле "От кого".' );
		if ( $message === '' )
			throw new AlarmException( 'Ошибка. Не заполнено поле "Текст рассылки".' );
		if ( $type === '' )
			throw new AlarmException( 'Ошибка. Не заполнено поле "Тип рассылки".' );
		
		db::delete( 'delivery_storage' );
		db::insert( 'delivery_storage', array( 'body_subject' => $subject, 'body_email' => $email,
			'body_name' => $name, 'body_text' => $message ) );
		
		switch ( $type )
		{
			case 'send_to_all':
				$person_list = db::select_all( '
					select person_id from delivery_person where person_active = 1' ); break;
			default:
				$person_list = db::select_all( '
					select person_id from delivery_person where person_admin = 1 and person_active = 1' );
		}
		
		if ( count( $person_list ) )
		{
			list( $headers, $body ) = sendmail::prepare( $email, $name, $subject, $message );
			
			db::insert( 'delivery_body', array( 'body_headers' => serialize( $headers ), 'body_text' => $body ) );
			$body_id = db::last_insert_id();
			
			foreach ( $person_list as $person )
				db::insert( 'delivery_queue', array( 'queue_body' => $body_id, 'queue_person' => $person['person_id'] ) );
		}
		
		$this -> redirect();
	}
	
	protected function action_clear()
	{
		db::delete( 'delivery_queue' );
		
		$this -> redirect();
	}
}
