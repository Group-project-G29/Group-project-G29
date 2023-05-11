
<?php
use app\core\Application;

      $hash = strtoupper(
    md5(
        '1222960'. 
        $order_id . 
        number_format(1000, 2, '.', '') . 
        'LKR' .  
        strtoupper(md5('MzA1MDU0OTcyMjM4NDk5OTEyMTMwNzQ1NjE1ODAxMzI3Nzc3MjIx')) 
    ) 
);
                            ?>
  
<script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
<script>
    // Payment completed. It can be a successful failure.
    payhere.onCompleted = function onCompleted(orderId) {
        console.log("Payment completed. OrderID:" + orderId);
        // Note: validate the payment and show success or failure page to the customer
    };

    // Payment window closed
    payhere.onDismissed = function onDismissed() {
        // Note: Prompt user to pay again or show an error page
        console.log("Payment dismissed");
    };

    // Error occurred
    payhere.onError = function onError(error) {
        // Note: show an error page
        console.log("Error:"  + error);
    };

    // Put the payment variables here
    var payment = {
        "sandbox": true,
        "merchant_id": "1222960",    // Replace your Merchant ID
        "return_url": "localhost",     // Important
        "cancel_url": "localhost",     // Important
        "notify_url": "http://sample.com/notify",
        "order_id": "ItemNo12345",
        "items": "Door bell wireles",
        "amount": "1000.00",
        "currency": "LKR",
        "hash": ".<?=$hash?>.", // *Replace with generated hash retrieved from backend
        "first_name": "Saman",
        "last_name": "Perera",
        "email": "samanp@gmail.com",
        "phone": "0771234567",
        "address": "No.1, Galle Road",
        "city": "Colombo",
        "country": "Sri Lanka",
        "delivery_address": "No. 46, Galle road, Kalutara South",
        "delivery_city": "Kalutara",
        "delivery_country": "Sri Lanka",
        "custom_1": "",
        "custom_2": ""
    };

    // Show the payhere.js popup, when "PayHere Pay" is clicked

        payhere.startPayment(payment);
    
</script>
    