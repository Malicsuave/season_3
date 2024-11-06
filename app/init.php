<?php

require_once  'vendor/autoload.php';

$stripe = [
	'pub_key' => 'pk_test_51QG2QFLwfEoMpz35Dnxr0pzi4u8S4FuYmcNPnIBcfXsm1RkZGv5i8jHvLPp7jR0UVxRTkjkSqho3cUz5WtPVhl2B00mX4vJt68',
	'pri_key' => 'sk_test_51QG2QFLwfEoMpz35fXqLusItqUUavw2mahFfT03EB5OURzjKQWcl0sQieR4ab6SCh067hIji0ima4Whp938FqPty00jGgT4EC2'
];

Stripe::setApiKey($stripe['pri_key']);

?>