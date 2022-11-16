<?php include 'opendb.php'?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/PHPMailer-master/src/SMTP.php';

// gets buyer with the highest bid
$buyer_email_query = "SELECT
    email,
    item_title
FROM
    bids
LEFT JOIN listings ON bids.listing_id = listings.listing_id
LEFT JOIN users ON bids.user_id = users.id
WHERE
    (bidprice, bids.listing_id) IN(
    SELECT
        MAX(bidprice),
        listing_id
    FROM
        bids
    WHERE
        listing_id IN(
        SELECT
            listing_id
        FROM
            listings
        WHERE
            NOW() > endtime AND DATE_ADD(endtime, INTERVAL 1 MINUTE) >= NOW()) AND bidprice >= reserveprice
        GROUP BY
            listing_id)";

$buyer_email_result = mysqli_query($connection, $buyer_email_query)
    or die('Error making winner email query');

// gets seller
$seller_email_query = "SELECT
    item_title,
    email,
    Address,
    Phone_Num
    
FROM
    users
INNER JOIN listings ON users.id = listings.user_id
WHERE
    NOW() > endtime AND DATE_ADD(endtime, INTERVAL 1 MINUTE) >= NOW()";

$seller_email_result = mysqli_query($connection, $seller_email_query)
		or die('Error making seller email query');

?>

<?php
// mailer function
function smtpmailer($to, $from, $from_name, $subject, $body)
    {

        $mail = new PHPMailer();

        /* Tells PHPMailer to use SMTP. */
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;            
        $mail->Username   = 'KELALwayauction@gmail.com';
        $mail->Password   = 'feluqdagwerbbzkb';                    
        $mail->SMTPSecure = 'tsl';   
        $mail->Port       = 587;   
    
        $mail->IsHTML(true);
        /* Add a recipient. */
        $mail->AddAddress($to, 'User');
        /* Set the mail sender. */
        $mail->SetFrom($from, $from_name);
        /* Set the subject. */
        $mail->Subject = $subject;
        /* Set the mail message body. */
        $mail->Body = $body;
        /* Finally send the mail. */
        if(!$mail->Send())
        {
            $error ="Email failed to send.";
            return $error;
        }
        else
        {
            $error = "Thanks You !! Your email is sent.";
            return $error;
        }
    }
?>

<?php
// email seller
			while($row = mysqli_fetch_array($seller_email_result)){

				$from = 'KELALwayauction@gmail.com';
				$name = 'KELAL Auction';
				$toSeller   = $row[1];
				$subjSeller = 'Your auction" '.$row[0].' "has finished.';
				$msgSeller = 'Your auction called "'.$row[0].'" has now ended. Be sure to check out what bids you got!';
				$error=smtpmailer($toSeller,$from,$name,$subjSeller,$msgSeller);

			}
// email buyer
			while($row = mysqli_fetch_array($buyer_email_result)){

        $from = 'KELALwayauction@gmailgmail.com';
        $name = 'KELAL Auction';
        $toBuyer   = $row[0];
        $msgBuyer = 'Your bid of on the auction called "'.$row[1].'" has now ended... And you are the highest bidder! Congratulations!
        Call"'.$row[3].'"Address:"'.$row[2].'';
        $error=smtpmailer($toBuyer,$from,$name,$subjBuyer,$msgBuyer);

      }



?>


<html>
    <head>
        <title>Send Auction End Email</title>
    </head>
    <body style="background: black;">
        <center><h2 style="padding-top:70px;color: white;"><?php echo $error; ?></h2></center>
    </body>

</html>
