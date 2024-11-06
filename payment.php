<?php
require_once 'app/init.php';


$total =$_POST["total"];
$token =$_POST["stripeToken"];

Stripe_Charge::create([
    "amount" => $total,
    "currency" => "php",
    "card" => $token,
    "description" => "Payment for  your meal!"
]);
?>