<?php 

require ('opendb.php');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendmail($email,$v_cod){
    
    
    require ('PHPMailer-master/PHPMailer-master/src/Exception.php');
    require ('PHPMailer-master/PHPMailer-master/src/SMTP.php');
    require ('PHPMailer-master/PHPMailer-master/src/PHPMailer.php');
    $mail = new PHPMailer(true);

    try {
       $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;            
        $mail->Username   = 'KELALwayauction@gmail.com';
        $mail->Password   = 'feluqdagwerbbzkb';                    
        $mail->SMTPSecure = 'tsl';   
        $mail->Port       = 587;                           

        $mail->setFrom('KELALwayauction@gmail.com', 'KELAL');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'email verification from KELAL Auction';
        $mail->Body    = "Thanks for registration.<br>click the link below to verify the email address
        <a href='http://localhost/looo/Happyyy/happyyy/verify.php?email=$email&v_cod=$v_cod'>verify</a>";

        $mail->send();
            return true;
    } catch (Exception $e) {
            return false;
    }
}



if (isset($_POST['register'])) {
    
    $full_Name =$_POST['name'];
    $address =$_POST['Address'];
    $phone =$_POST['phone_num'];
    $email =$_POST['email'];
    $password =$_POST['password'];
    $repeat_password = $_POST['passwordrepeat'];
    $accounttype = $_POST['accountType'];
    if ($accounttype == "buyer"){
        $typevar = 0;
    }
    if ($accounttype == "seller"){
        $typevar = 1;
    }





    $user_exist_query="SELECT * FROM users WHERE  email = '$email' ";
    $result = $connection->query($user_exist_query);

    if ($result) {
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            if ($row['email'] === $email) {
                echo "
                    <script>
                        alert('email alredy register');
                        window.location.href='index.php'
                    </script>";
            }
        
        }else{
            
            $v_cod=bin2hex(random_bytes(16));
            
            //$query ="INSERT INTO `users`(`Full_name`, `username`, `email`, `password`,`verification_id`, `verification_status`) VALUES ('$fullName','$username','$email','$password','$v_cod','0')";
            $query = "INSERT INTO users (email, Full_name, Address, Phone_Num, password, type, verification_id,verification_status) VALUES ('$email','$full_Name','$address', '$phone', $password,'$typevar','$v_cod','0')";           
            if (($connection->query($query)===TRUE) && sendmail($email,$v_cod )===TRUE) {
                echo "
                    <script>
                        alert('register successful.chack your mailbox in inbox or spam and verify your account.');
                            window.location.href='index.php'
                    </script>"; 
            }else{
                echo "
                    <script>
                        alert('query can not run');
                        
                    </script>";
            }
        }
    }else
	{
        echo "
        <script>
            alert('query can not run');
            
        </script>";
    }
}

?>