<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body{
  min-height: 100vh;
  background: url(images/background.jpg) no-repeat;
  background-size: cover;
  display: flex;
  justify-content: center;
  align-items: center;
}

.contact-section{
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.contact-info{
  color: #fff;
  max-width: 500px;
  line-height: 65px;
  padding-left: 50px;
  font-size: 18px;
}

.contact-info i{
  margin-right: 20px;
  font-size: 25px;
}

.contact-form{
  max-width: 700px;
  margin-right: 50px;
}

.contact-info, .contact-form{
  flex: 1;
}

.contact-form h2{
  color: #fff;
  text-align: center;
  font-size: 35px;
  text-transform: uppercase;
  margin-bottom: 30px;
}

.contact-form .text-box{
  background: #000;
  color: #fff;
  border: none;
  width: calc(50% - 10px);
  height: 50px;
  padding: 12px;
  font-size: 15px;
  border-radius: 5px;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
  opacity: 0.9;
}

.contact-form .text-box:first-child{
  margin-right: 15px;
}

.contact-form textarea{
  background: #000;
  color: #fff;
  border: none;
  width: 100%;
  padding: 12px;
  font-size: 15px;
  min-height: 200px;
  max-height: 400px;
  resize: vertical;
  border-radius: 5px;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
  opacity: 0.9;
}

.contact-form .send-btn{
  float: right;
  background: #2E94E3;
  color: #fff;
  border: none;
  width: 120px;
  height: 40px;
  font-size: 15px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 2px;
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
  transition-property: background;
}

.contact-form .send-btn:hover{
  background: #0582E3;
}

@media screen and (max-width: 950px){
  .contact-section{
    flex-direction: column;
  }

  .contact-info, .contact-form{
    margin: 30px 50px;
  }

  .contact-form h2{
    font-size: 30px;
  }

  .contact-form .text-box{
    width: 100%;
  }
}

/*css for alert messages*/

.alert-success{
  z-index: 1;
  background: #D4EDDA;
  font-size: 18px;
  padding: 20px 40px;
  min-width: 420px;
  position: fixed;
  right: 0;
  top: 10px;
  border-left: 8px solid #3AD66E;
  border-radius: 4px;
}

.alert-error{
  z-index: 1;
  background: #FFF3CD;
  font-size: 18px;
  padding: 20px 40px;
  min-width: 420px;
  position: fixed;
  right: 0;
  top: 10px;
  border-left: 8px solid #FFA502;
  border-radius: 4px;
}
  </style>


<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
    require ('PHPMailer-master/PHPMailer-master/src/Exception.php');
    require ('PHPMailer-master/PHPMailer-master/src/SMTP.php');
    require ('PHPMailer-master/PHPMailer-master/src/PHPMailer.php');

$mail = new PHPMailer(true);

$alert = '';

if(isset($_POST['submit'])){
  $name = $_POST['name'];
  $email = $_POST['email'];
  $message = $_POST['message'];

  try{
    $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;            
        $mail->Username   = 'KELALwayauction@gmail.com';
        $mail->Password   = 'feluqdagwerbbzkb';                    
        $mail->SMTPSecure = 'tsl';   
        $mail->Port       = 587;

    $mail->setFrom('KELALwayauction@gmail.com', 'KELAL'); // Gmail address which you used as SMTP server
    $mail->addAddress('KELALwayauction@gmail.com'); // Email address where you want to receive emails (you can use any of your gmail address including the gmail address which you used as SMTP server)

    $mail->isHTML(true);
    $mail->Subject = 'Message Received (Contact Page)';
    $mail->Body = "<h3>Name : $name <br>Email: $email <br>Message : $message</h3>";

    $mail->send();
    $alert = '<div class="alert-success">
                 <span>Message Sent! Thank you for contacting us.</span>
                </div>';
                return true;
  } catch (Exception $e){
    $alert = '<div class="alert-error">
                <span>'.$e->getMessage().'</span>
              </div>';
  }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
  </head>
  <body>

    <!--alert messages start-->
    <?php echo $alert; ?>
    <!--alert messages end-->

    <!--contact section start-->
    <div class="contact-section">
      <div class="contact-info">
        <div><i class="fas fa-map-marker-alt"></i>4kilo, Addis Ababa, Ethiopia</div>
        <div><i class="fas fa-envelope"></i>KELALwayauction@email.com</div>
        <div><i class="fas fa-phone"></i>0112840199</div>
        <div><i class="fas fa-clock"></i>Mon - Fri 8:00 AM to 5:00 PM</div>
      </div>
      <div class="contact-form">
        <h2>Contact Us</h2>
        <form class="contact" action="" method="post">
          <input type="text" name="name" class="text-box" placeholder="Your Name" required>
          <input type="email" name="email" class="text-box" placeholder="Your Email" required>
          <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
          <input type="submit" name="submit" class="send-btn" value="Send">
        </form>
      </div>
    </div>
    <!--contact section end-->

    <script type="text/javascript">
    if(window.history.replaceState){
      window.history.replaceState(null, null, window.location.href);
    }
    </script>

  </body>
</html>