<?php


    use Twilio\Rest\Client;

    // add_action('init','test_send_sms_plugin');



    // function test_send_sms_plugin() {
    //     if($_GET['pp']){
    //         echo 'thanh123';
    //         // Your Account SID and Auth Token from twilio.com/console
    //         $account_sid = 'AC1dbc3673189c0780a59e07eb0c55a31c';
    //         $auth_token = '2406e8b9b78a97cadb21dbedc3bddd37';
    //         // In production, these should be environment variables. E.g.:
    //         // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
    //
    //         // A Twilio number you own with SMS capabilities
    //         $twilio_number = "+12679154356";
    //
    //         $client = new Client($account_sid, $auth_token);
    //         $client->messages->create(
    //             // Where to send a text message (your cell phone?)
    //             '+84935545613',
    //             array(
    //                 'from' => $twilio_number,
    //                 'body' => 'I sent this message in under 10 minutes!'
    //             )
    //         );
    //     }
    //
    // }

    function pp_send_sms ( $phone,$body_message ) {
        $account_sid = get_field('account_sid_sms','options');
        $auth_token  = get_field('token_sms_twillo','options');
        $twilio_number = get_field('phone_sms_twillo','options');

        $client = new Client($account_sid, $auth_token);
        $client->messages->create(
            // Where to send a text message (your cell phone?)
            $phone,
            array(
                'from' => $twilio_number,
                'body' => $body_message
            )
        );

    } 



?>
