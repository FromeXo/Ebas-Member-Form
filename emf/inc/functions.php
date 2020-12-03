<?php defined( 'WPINC' ) or die;
/**
 * Custom PHP Functions
 * 
 * @author Robin "FromeXo" Johansson
 * @since 1.0.0
 * @package Emf
 */

function emf_ebas_submit_member($ssn, $email, $phone, $apiKey) {

    $ch = curl_init();

    $data = json_encode([
        'api_key' => $apiKey,
        'member' => [
            'renewed' => date('Y-m-d'),
            'socialsecuritynumber' => $ssn,
            'email' => $email,
            'phone1' => $phone,
            'phone2' => []
        ]
    ]);

    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://ebas.sverok.se/apis/submit_member_with_lookup.json',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]
    ]);

    $result = json_decode(curl_exec($ch));
    // $ce = curl_error($ch);
    curl_close($ch);
    var_dump($result->stored_member);

}

function emf_ebas_is_member($ssn, $apiKey, $fId) {

    $ch = curl_init();

    $data = json_encode(['request' => [
        'action' => 'confirm_membership',
        'version'  => '2015-06-01',
        'association_number' => $fId,
        'api_key'  => $apiKey,
        'year_id'  => date('Y'),
        'socialsecuritynumber'  => $ssn
    ]]);

    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://ebas.sverok.se/apis/confirm_membership.json',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]
    ]);


    $result = json_decode(curl_exec($ch));
    // $ce = curl_error($ch);
    curl_close($ch);

    return ( $result->response->member_found == 1 ) ? true : false;
}


function emf_mailgun_send_confirmation($to, $apiKey, $domain, $mail, $data=[]) {

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api.mailgun.net/v3/'.$domain.'/messages',
        CURLOPT_NETRC => false,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => false,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERPWD => 'api:'.$apiKey,
        CURLOPT_POSTFIELDS => [
            'to' => $to,
            'from' => $mail['from'],
            'subject' => $mail['subject'],
            'template'=> $mail['template'],
            'h:X-Mailgun-Variables' => json_encode($data)
        ]
    ]);
    curl_exec($ch);
    curl_close($ch);
}