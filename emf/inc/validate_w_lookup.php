<?php
defined( 'WPINC' ) or die;
/**
 * Validates the w_lookup form
 * 
 * @author Robin "FromeXo" Johansson
 * @since 1.0.0
 * @package Emf
 */

/*
 *  Format SSN
 *  
 *  Always make sure SSN data is 12 numbers, no spaces and no dash.
 */
$ssn = str_replace(['-', ' '], ['', ''], $ssn);
if ( strlen($ssn) == 10 ) {
    $ssn = substr(date('Y') - substr($ssn, 0, 2), 0 , 2).$ssn;
}
/*
 *  Validate SSN
 */
if ( ! preg_match('/^(19|20)?\d{2}((0(1|3|5|7|8)|1(0|2))(0[1-9]|1[0-9]|2[0-9]|3[0-1])|02(0[1-9]|1[0-9]|2[0-9])|(0(4|6|9)|11)(0[1-9]|1[0-9]|2[0-9]|30))\-?\d{4}$/', $ssn)) {
    $formError['ssn'] = '';
}

/*
 *  Format Phone
 * 
 *  Format Phone according to the swedish standard format.
 */
$phone = str_replace(['-', ' ', '+46'], ['',  '', '0'], $phone);

/*
 *  Validate Phone
 * 
 *  Validates a phonenumber to make sure it follows the structer of the function format_phone().
 */
if ( ! preg_match('/(\+\d{2}|0)\d{7,9}/', $phone) ) {
    $formError['phone'] = '';
}

/*
 *  Validate Email
 */
if ( filter_var($email, FILTER_VALIDATE_EMAIL) === false ) {
    $formError['email'] = '';
}

if ( ! isset($_POST['statues']) ) {
    $formError['statues'] = '';
}

if ( ! isset($_POST['pul']) ) {
    $formError['pul'] = '';
}

//
if ( ! emf_ebas_is_member($ssn, $options['api_key'], $options['fid']) ) {
    
    emf_ebas_submit_member($ssn, $email, $phone, $options['api_key']);
    
    $mailData = [
        'discord_token' =>  md5(md5($ssn . $email . $phone))
    ];

    $mail = [
        'subject' => $options['mg_subject'],
        'from' => $options['mg_from'],
        'template' => $options['mg_template']
    ];
    emf_mailgun_send_confirmation($email, $options['mg_api_key'], $options['mg_domain'], $mail, $mailData);

} else {
    $content = 'Du är redan medlem!';
}

$showForm = false;