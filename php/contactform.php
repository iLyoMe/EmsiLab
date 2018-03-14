<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'vendor/autoload.php';                    

    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $name = strip_tags(trim($_POST["name"]));
		$name = str_replace(array("\r","\n"),array(" "," "),$name);
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $message = trim($_POST["message"]);
        $subject = $_POST["subject"];

        // Check that data was sent to the mailer.
        if (!isset($subject) || !isset($name) || !isset($message) || !isset($email) || empty($subject) || empty($name) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "Oops! There was a problem with your submission. Please complete the form and try again.";
            exit;
        }
       
$mail = new PHPMailer();
$mail->IsSMTP(); // telling the class to use SMTP
//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)  
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "tls";                 
$mail->Host       = "smtp.gmail.com";      // SMTP server
$mail->Port       = 587;                   // SMTP port
$mail->Username   = "X";  // username
$mail->Password   = "Y"; // password

//send to
$mail->addAddress("ouf.ilyas@gmail.com");
//Recipients
$mail->SetFrom($email,$name);
$mail->addReplyTo($email,$name);

$mail->Subject= $subject;
$mail->MsgHTML($message);

if(!$mail->Send()) {
    echo "Oops! Something went wrong and we couldn't send your message.";
  } else {
    echo "OK";
  }
   $name = '';
  $email = '';
  $subject = '';
  $message = '';
    } else {
        // Not a POST request, set a 403 (forbidden) response code.
        http_response_code(403);
        echo "There was a problem with your submission, please try again.";
    }

?>