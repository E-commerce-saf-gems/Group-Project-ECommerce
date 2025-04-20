<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

function sendOrderEmail($first_name, $email, $order) {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      
        $mail->isSMTP();     

        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                 
        $mail->Username   = 'sadheeyasalim10@gmail.com';          
        $mail->Password   = 'ijkwzrnamjyfeimb';   

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          
        $mail->Port       = 465;                                  
    
        $mail->setFrom('sadheeysalim10@gmail.com', 'Saf Gems');
        $mail->addAddress($email, $first_name);

        // Build Product List
        $product_list = "";
        foreach ($order['products'] as $product) {
            $product_list .= "
                <tr>
                    <td>{$product['type']} (Shape: {$product['shape']}, Size: {$product['size']} carats, Color: {$product['colour']})</td>
                    <td>1</td>
                    <td>{$product['price']} LKR</td>
                    <td>{$product['price']} LKR</td>
                </tr>
            ";
        }

        // Email Template
        $email_template = "
            <h2 style='color: #449f9f;'>Order Confirmation - Order #{$order['order_id']}</h2>
            <p>Dear $first_name,</p>
            <p>Thank you for your order! Below are your order details:</p>

            <h3>Order Summary</h3>
            <table border='1' cellspacing='0' cellpadding='5' style='border-collapse: collapse; width: 100%;'>
                <tr>
                    <th>Order Date</th>
                    <td>{$order['order_date']}</td>
                </tr>
                <tr>
                    <th>Order Number</th>
                    <td>{$order['order_id']}</td>
                </tr>
                <tr>
                    <th>Payment Method</th>
                    <td>{$order['payment_method']}</td>
                </tr>
                <tr>
                    <th>Shipping Method</th>
                    <td>{$order['shipping_method']}</td>
                </tr>
                <tr>
                    <th>Total (Gross)</th>
                    <td>{$order['total_amount']} LKR</td>
                </tr>
            </table>

            <h3>Products Ordered</h3>
            <table border='1' cellspacing='0' cellpadding='5' style='border-collapse: collapse; width: 100%;'>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
                $product_list
            </table>

            <p>Your order is now being processed. You can check your order status by clicking the link below:</p>
            <br/>
            <a href='http://localhost/Group-Project-ECommerce/pages/Profile/Purchases/MyPurchases.php' 
               style='background-color: #449f9f; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>
               View Your Orders
            </a>
            <br/><br/>
            <p>Thank you for choosing SAF GEMS!</p>
        ";

        $mail->isHTML(true);
        $mail->Subject = "Order Confirmation - Order #{$order['order_id']}";
        $mail->Body    = $email_template;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>