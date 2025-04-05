<?php
require 'C:\xampp\htdocs\FreelanceMarketplace\vendor\autoload.php'; // Load Stripe library
\Stripe\Stripe::setApiKey('sk_test_tR3PYbcVNZZ796tH88S4VQ2u'); // Replace with your Secret Key

session_start();
$db = new SQLite3('C:\xampp\htdocs\FreelanceMarketplace\Connection\Freelance_db.db');

$job_id = $_POST['job_id'];
$proposal_id = $_POST['proposal_id'];
$freelancer_id = $_POST['freelancer_id'];
$bid_amount = $_POST['bid_amount'];

if (!$job_id || !$proposal_id || !$freelancer_id || !$bid_amount) {
    die("Invalid parameters.");
}

// Store details in session to use after payment success
$_SESSION['payment_data'] = [
    'job_id' => $job_id,
    'proposal_id' => $proposal_id,
    'freelancer_id' => $freelancer_id,
    'bid_amount' => $bid_amount
];

// Create Stripe Checkout session
$checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => ['name' => 'Freelance Job Payment'],
            'unit_amount' => $bid_amount * 100, // Convert to cents
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://localhost/FreelanceMarketplace/PHPUIFiles/stripe/payment-success.php',
    'cancel_url' => 'http://localhost/FreelanceMarketplace/PHPUIFiles/stripe/payment-cancel.php',
]);

header("Location: " . $checkout_session->url);
exit;
?>
