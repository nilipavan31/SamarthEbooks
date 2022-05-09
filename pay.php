<?php
define('server', 'localhost');
define('username', 'root');
define('password', '');
define('dbname', 'payment');

$con=new mysqli(server,username,password,dbname);
if($con === false)
{
  die("Error could not connet".$con->connect_error);
}
 
if($_SERVER['REQUEST_METHOD'] == "POST")
				{
					
					$EMAIL=$_POST['EMAIL'];
					$MSISDN=$_POST['MSISDN'];
					// $message=$_POST['message'];
				
					$qry="INSERT INTO PAYMENT_DETAILS(EMAIL,MSISDN)VALUES('$EMAIL','$MSISDN')";
					if ($con->query($qry)===TRUE) 
					{
					//echo '<script>alert("Data Inserted Successfully!!!!")</script>';
				
					}
					else
					{
					echo "Data is not Inserted".mysqli_error();
					}   
				}
				else
				{
					echo "Request method is not POST";
				}
require('config.php');
require('razorpay-php/Razorpay.php');
session_start();

// Create the Razorpay Order

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

//
// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders
//
$EMAIL=$_POST['EMAIL'];
$MSISDN=$_POST['MSISDN'];


$orderData = [
    'receipt'         => 3456,
    'amount'          => 99 * 100, // 2000 rupees in paise
    'currency'        => 'INR',
    'payment_capture' => 1 // auto capture
];

$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}



$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => "Samarth Distibuters",
    "description"       => "Ebook Celling",
    "image"             => "Shrinivas Nagmal 20220320_223050.jpg",
    "prefill"           => [
    "name"              => "",
    "email"             => $EMAIL,
    "contact"           => $MSISDN,
    ],
    "notes"             => [
    "address"           => "Online",
    "merchant_order_id" => "12312321",
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);
?>

<!--  The entire list of Checkout fields is available at
 https://docs.razorpay.com/docs/checkout-form#checkout-fields -->

 <form action="verify.php" method="POST">
  <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $data['key']?>"
    data-amount="<?php echo $data['amount']?>"
    data-currency="INR"
    data-name="<?php echo $data['name']?>"
    data-image="<?php echo $data['image']?>"
    data-description="<?php echo $data['description']?>"
    data-prefill.name=""
    data-prefill.email="<?php echo $data['prefill']['email']?>"
    data-prefill.contact="<?php echo $data['prefill']['contact']?>"
    data-notes.shopping_order_id="<?php echo 'OID'.rand(10,100).'END'; ?>"
    data-order_id="<?php echo $data['order_id']?>"
    <?php if ($displayCurrency !== 'INR') { ?> data-display_amount="<?php echo $data['display_amount']?>" <?php } ?>
    <?php if ($displayCurrency !== 'INR') { ?> data-display_currency="<?php echo $data['display_currency']?>" <?php } ?>
  >
  </script>
  <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
  <input type="hidden" name="shopping_order_id" value="3456">
</form>

<script type="text/javascript">
       const element = document.querySelector('.razorpay-payment-button');
        element.click();
    </script>

<style>
    .razorpay-payment-button{display:none;}
</style>

