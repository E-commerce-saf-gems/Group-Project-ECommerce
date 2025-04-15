<?php
session_start() ;

include ('../../database/db.php') ;

$errors = [];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

function sendPasswordResetEmail($get_name,$get_email,$token) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
        $mail->isSMTP();                                            
       
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                 
        $mail->Username   = 'sadheeyasalim10@gmail.com';          
        $mail->Password   = 'ijkwzrnamjyfeimb';   

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;          
        $mail->Port       = 465;                                  
    
        $mail->setFrom('sadheeysalim10@gmail.com', 'Saf Gems');
        $mail->addAddress($get_email, $get_name);    

        $email_template = "
            <h2 style='color='#449f9f''>Reset Your Password</h2>
            <h3>TIf you requested to reset your password, please click the link below to proceed. If you did not make this request, please ignore this email.</h5>
            <br/><br/>
            <button>
                <a href='http://localhost/Group-Project-ECommerce/pages/Login/resetPassword.php?token=$token&email=$get_email'>Click Here!</a>
            </button>
        " ;

        //Content
        $mail->isHTML(true);                      //Set email format to HTML
        $mail->Subject = 'Password Reset Link';
        $mail->Body    = $email_template ;
    
        $mail->send();
       // echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if(isset($_POST['resetPassword'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']) ;
    $token = md5(rand()) ;

    $check_email =  "SELECT email FROM customer WHERE email='$email' LIMIT 1" ;
    $check_email_run = mysqli_query($conn, $check_email) ;

    if(mysqli_num_rows($check_email_run)>0) {
        $row = mysqli_fetch_array($check_email_run) ;
        $get_name = $row['firstName'] ;
        $get_email = $row['email'] ;

        $update_token = "UPDATE customer SET token='$token' WHERE email='$email' LIMIT 1" ;
        $update_token_run = mysqli_query($conn, $update_token) ;

        if($update_token_run) {
            sendPasswordResetEmail($get_name,$get_email,$token);
            $_SESSION['status'] = "An email has been sent!" ;
            header("Location: ./forgotPassword.html") ;
            exit() ;
        }
        else {
            $_SESSION['status'] = "Something went wrong!" ;
            header("Location: ./forgotPassword.html") ;
            exit() ;           
        }
    }
    else {
        $_SESSION['status'] = "No Email Found" ;
        header("Location: ./forgotPassword.html") ;
        exit() ;
    }
}





?>