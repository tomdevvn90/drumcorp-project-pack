<?php


    use Twilio\Rest\Client;

    // if(!empty($_GET['pp'])){
    //     add_action('init','text_send_sms');
    //     echo 'th2nh';
    //     function text_send_sms() {
    //         // pp_send_sms('+61401360908','test Tom sms');
    //         var_dump(get_current_user_id());
    //
    //     }
    //
    // }

    function pp_send_sms ( $phone,$body_message ) {
        $phone_n = $phone;
        $body = $body_message;
        $account_sid = get_field('account_sid_sms','options');
        $auth_token  = get_field('token_sms_twillo','options');
        $twilio_number = get_field('phone_sms_twillo','options');

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $phone_n,
            array(
                'from' => $twilio_number,
                'body' => strip_tags($body)
            )
        );

    }



?>
