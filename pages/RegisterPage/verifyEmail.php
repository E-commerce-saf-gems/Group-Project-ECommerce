<?php
    session_start() ;
    include('../../database/db.php') ;

    if(isset($_GET['token'])) {
        $token = $_GET['token'] ;
        $verify_query = "SELECT token,verificationStatus from customer WHERE token='$token' LIMIT 1" ; 
        $verify_query_run = mysqli_query($conn , $verify_query) ;

        if (mysqli_num_rows($verify_query_run)>0) {
            $row = mysqli_fetch_array($verify_query_run);
            if($row['verificationStatus']=="0") {
                $clickedToken = $row['token'] ;
                $updateQuery = "UPDATE customer SET verificationStatus='1' WHERE token='$clickedToken' LIMIT 1" ;
                $updateQuery_run = mysqli_query($conn, $updateQuery) ;

                if($updateQuery_run) {
                    $_SESSION['status'] = "Your Account Has Been Verfied Successfully" ;
                    header("Location: ../Login/login.html") ;
                    exit(0) ;
                }
                else {
                    $_SESSION['status'] = "Verification Failed" ;
                    header("Location: ../Login/login.html") ;
                    exit(0) ;
                }
            }
            else {
                $_SESSION['status'] = "This Email Has Been Verified Already!" ;
                header("Location: ../Login/login.html") ;
                exit(0) ;
            }
        }
        else {
            $_SESSION['status'] = "This token does not exist" ;
            header("Location: ../Login/login.html") ;
        }
    }
    else {
        $_SESSION['status'] = "Not Allowed" ;
        header("Location: ../Login/login.html") ;
    }

?>