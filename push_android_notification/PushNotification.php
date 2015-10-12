<?php
//generic php function to send GCM push notification
function sendPushNotificationToGCM($registatoin_ids, $message) {
    //Google cloud messaging GCM-API url
    $url = 'https://android.googleapis.com/gcm/send';
    $fields = array(
        'registration_ids' => $registatoin_ids,
        'data' => $message,
    );
    // Google API Key to send push notification
    define("GOOGLE_API_KEY", "MY_API_KEY");       
    $headers = array(
        'Authorization: key=' . GOOGLE_API_KEY,
        'Content-Type: application/json'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);             
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}
?>
<?php

// It is used to push data.
$pushStatus = "";   
if ( ! empty($_GET["push"])) { 
    $gcmRegID  = $_POST["gcmRegId"]; 
    $pushMessage = $_POST["message"];   
    if (isset($gcmRegID) && isset($pushMessage)) {      
        $gcmRegId = array($gcmRegID);
        $message = array("m" => $pushMessage);  
        $pushStatus = sendPushNotificationToGCM($gcmRegId, $message);
    }       
}
  
?>
<html>
    <head>
        <title>Google Cloud Messaging (GCM) Server in PHP</title>
    </head>
    <body>
        <h1>Google Cloud Messaging (GCM) Server in PHP</h1> 
        <form method="post"   action="gcm.php/?push=1">                                              
            <div>                                
                <textarea rows="2" name="message" cols="23" placeholder="Please enter valid msg"></textarea>
                <textarea rows="2" name="gcmRegId" cols="23" placeholder="Please enter valid device regId"></textarea>
            </div>
            <div><input type="submit"  value="Send Push Notification via GCM" /></div>
        </form>
        <p><h3><?php echo $pushStatus; ?></h3></p>        
    </body>
</html>