<?php
include_once dirname(__FILE__) . '/../../config/config.php';

$pricespy = new pricespy();
$pricespy->parse();
$report = $pricespy->get_report()

$view = new view();
$view->assign('report', $report);
$message = $view->fetch('admin/pricespy/message');

$from_email = get_preference('from_email');
$from_name = get_preference('from_name');

$pricespy_email = get_preference('pricespy_email');
$pricespy_subject = get_preference('pricespy_subject');

@sendmail::send( $pricespy_email, $from_email, $from_name, $pricespy_subject, $message );
