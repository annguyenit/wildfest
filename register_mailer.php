<?php
/*require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

$mail = new PHPMailer;
// Setting up PHPMailer
$mail->IsSMTP();                                      // Set mailer to use SMTP
// Visit http://phpmailer.worxware.com/index.php?pg=tip_srvrs for more info on server settings
// For GMail    => smtp.gmail.com
//     Hotmail  => smtp.live.com
//     Yahoo    => smtp.mail.yahoo.com
//     Lycos    => smtp.mail.lycos.com
//     AOL      => smtp.aol.com
$mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
$mail->SMTPAuth = true;                               // Enable SMTP authentication
//This is the email that you need to set so PHPMailer will send the email from
$mail->Username = 'furryfriendzvietnam@gmail.com';             // SMTP username
$mail->Password = 'granny8.9';                           // SMTP password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;                                    // TCP port to connect to
// Add the address to send the mail to
$mail->AddAddress('jezx715@gmail.com');
$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$mail->IsHTML(true);                                  // Set email format to HTML
*/

// choose which fields you would like to be validated separated by |
// options required - check input has content valid_email - check for valid email
$field_rules = array(
    'FNAME' => 'required',
    'FANAME' => 'required',
    'EMAIL'   => 'required|valid_email',
    'COUNTRY' => 'required',
    'STATE' => 'required'
);

// change your error messages here
$error_messages = array(
    'required'    => 'This field is required',
    'valid_email' => 'Please enter a valid email address'
);

// select where each inputs error messages will be shown
$error_placements = array(
    'FNAME'        => 'top',
    'FANAME'       => 'top',
    'EMAIL'        => 'top',
    'COUNTRY'      => 'top',
    'STATE'        => 'top',
    'submitButton' => 'right'
);

// success message
$success_message            = new stdClass();
$success_message->message   = 'Thanks! your information has been sent';
$success_message->field     = 'submitButton';
$success_message->placement = $error_placements['submitButton'];

// mail failure message
$mail_error_message            = new stdClass();
$mail_error_message->message   = 'Sorry your information was not sent - please try again later';
$mail_error_message->field     = 'submitButton';
$mail_error_message->placement = $error_placements['submitButton'];

// DONT EDIT BELOW THIS LINE UNLESS YOU KNOW YOUR STUFF!

$fields = $_POST;

$returnVal           = new stdClass();
$returnVal->status   = 'error';
$returnVal->messages = array();

if (!empty($fields)) {
    //Validate each of the fields
    foreach ($field_rules as $field => $rules) {
        $rules = explode('|', $rules);

        foreach ($rules as $rule) {
            $result = null;

            if (isset($fields[$field])) {
                if (!empty($rule)) {
                    $result = $rule($fields[$field]);
                }

                if ($result === false) {
                    $error = new stdClass();
                    $error->field = $field;
                    $error->message = $error_messages[$rule];
                    $error->placement = $error_placements[$field];

                    $returnVal->messages[] = $error;
                    // break from the rule loop so we only get 1 error at a time
                    break;
                }
            } else {
                $returnVal->messages[] =  $field . ' ' . $error_messages['required'];
            }
        }
    }

    if (empty($returnVal->messages)) {                         // Enable encryption, 'ssl' also accepted
        $fName = stripslashes(safe($fields['FNAME']));
        $faName = stripslashes(safe($fields['FANAME']));
        $bday = stripslashes(safe($fields['BDAY']));
        $email = stripslashes(safe($fields['EMAIL']));
        $tel = stripslashes(safe($fields['TEL']));
        $country = stripslashes(safe($fields['COUNTRY']));
        $city = stripslashes(safe($fields['STATE']));
        $receive = $agree = '';
        if (isset($fields['RECEIVE'])) {
            $receive = 'I am interested in receiving exclusive information to filmmakers.';
        }
        if (isset($fields['AGREE'])) {
            $agree = "I agree that WildFest can use my registered personal information for organizational purpose and can contact me.";
        }
        
        // The sender of the form/mail
        //$to      = 'film@wildfest.org';
        $to = 'film@wildfest.org, info@wildfest.org';
        $subject = '[Wild Fest OGC - Register user]';
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: '.$fName . ' ' . $faName.' <'.$email.'>' . "\r\n";
        $headers .= 'Reply-To: '. $email . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        
        $body = "
        First Name: ".$fName." <br /><br />
        Family Name: ".$faName." <br /><br />
        Year of Birth: ".$bday." <br /><br />
        Place of Residence: {$country} <br /> <br />
        City/Province:{$city} <br /><br />
        E-Mail: ".$email." <br /><br />
        Tel: ".$tel." <br />
        <br />
        {$receive} <br /><br />
        {$agree}";

        if(mail($to, $subject, $body, $headers)) {
            $returnVal->messages[] = $success_message;
            $returnVal->status = 'ok';
        } else {
            $returnVal->messages[] = $mail_error_message;
        }
        
        
        
//        $mail->From = $email;
//        $mail->FromName = $fName . ' ' . $faName;
//        $mail->Subject = '[Wild Fest OGC - Register user]';
//        $body = "
//        First Name: ".$fName." <br /><br />
//        Family Name: ".$faName." <br /><br />
//        Year of Birth: ".$bday." <br /><br />
//        Place of Residence: {$country} <br /> <br />
//        City/Province:{$city} <br /><br />
//        E-Mail: ".$email." <br /><br />
//        Tel: ".$tel." <br />
//        <br />
//        {$receive} <br /><br />
//        {$agree}";
//        $mail->Body    = $body;
//        $options["ssl"]=array("verify_peer"=>false,"verify_peer_name"=>false,"allow_self_signed"=>true);
//        $mail->smtpConnect($options);
//        if(@$mail->Send()) {
//            $returnVal->messages[] = $success_message;
//            $returnVal->status = 'ok';
//        } else {
//            $returnVal->messages[] = $mail_error_message;
//        }
    }
    echo json_encode($returnVal);
}

function required($str, $val = false)
{
    if (!is_array($str)) {
        $str = trim($str);
        return ($str == '') ? false : true;
    } else {
        return !empty($str);
    }
}

function valid_email($str)
{
    return (!preg_match("/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD", $str)) ? false : true;
}

function safe($name)
{
    return(str_ireplace(array("\r", "\n", '%0a', '%0d', 'Content-Type:', 'bcc:','to:','cc:'), '', $name));
}
