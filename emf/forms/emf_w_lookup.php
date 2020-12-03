<?php defined( 'WPINC' ) or die;
/**
 * Form Html
 * 
 * @author Robin "FromeXo" Johansson
 * @since 1.0.0
 * @package Emf
 */

return ($showForm ? '
<form action='.get_page_link().' method="POST" id="emf_w_lookup">
'.wp_nonce_field('emf_w_lookup_submit', '_emf_wpnonce', true, false).'
<div class="form-group">
<label for="inputEmail">Personnummer</label>
<input type="text" class="form-control" name="ssn" id="inputSsn" required="required" value="'.esc_attr($ssn).'">
<p style="' . (isset($formError['ssn'])  ? 'display:block;' : 'display:none;' ) . '"><small style="color: #ee0000;">Du måste ange ett korrect personnummer.</small></p>
</div>

<div class="form-group">
<label for="inputEmail">E-post</label>
<input type="email" class="form-control" name="email" id="inputEmail" required="required" value="'.esc_attr($email).'">
<p style="' . (isset($formError['email'])  ? 'display:block;' : 'display:none;' ) . '"><small style="color: #ee0000;">Du måste ange giltig E-post.</small></p>
</div>

<div class="form-group">
<label for="inputPhone">Telefonnummer</label>
<input type="text" class="form-control" name="phone" id="inputPhone" required="required" value="'.esc_attr($phone).'">
<p style="' . (isset($formError['phone'])  ? 'display:block;' : 'display:none;' ) . '"><small style="color: #ee0000;">Du måste ange ett telefonnummer.</small></p>
</div>

<div class="checkbox">
<label>
    <input type="checkbox" name="statues" required="required">
    Jag godkänner <a href="'.esc_attr($statuesUrl).'" target="_blank">föreningens stadgar</a> <small>(öppnas i ny flik)</small>
    <p style="' . (isset($formError['statues'])  ? 'display:block;' : 'display:none;' ) . '"><small style="color: #ee0000;">För att bli medlem så måste du godkänna föreningens stadgar.</small></p>
</label>
</div>

<div class="checkbox">
<label>
    <input type="checkbox" name="pul" required="required">
    Jag accepterar <a href="'.esc_attr($pulUrl).'">hanteringen av personuppgifter</a> <small>(öppnas i ny flik)</small>.
    <p style="' . (isset($formError['pul'])  ? 'display:block;' : 'display:none;' ) . '"><small style="color: #ee0000;">För att bli medlem så måste du acceptera hanteringen av personuppgifter.</small></p>
</label>
</div>
<button type="submit">Bli Medlem</button>
</form>
<script>
document.getElementById("emf_w_lookup").addEventListener("submit", function(event){
let status = true,
    btn = this.querySelector(\'button[type="submit"]\')
    ssn = this.querySelector("#inputSsn"),
    email = this.querySelector("#inputEmail"),
    phone = this.querySelector("#inputPhone"),
    checkboxes = this.querySelectorAll(\'input[type="checkbox"]\');

btn.setAttribute("disabled", "disabled");

let ssnValue = ssn.value.replace(/(\W|\-)+/g, "");
if ( ssnValue.length == 10 ) {
    ssnValue = ((new Date()).getFullYear() - ssnValue.substr(0, 2)).toString().substr(0, 2) + ssnValue;
}

if ( /(19|20)\d{2}((0(1|3|5|7|8)|1(0|2))(0[1-9]|1[0-9]|2[0-9]|3[0-1])|02(0[1-9]|1[0-9]|2[0-9])|(0(4|6|9)|11)(0[1-9]|1[0-9]|2[0-9]|30))\d{4}/.test(ssnValue) ) {
    ssn.parentNode.querySelector("p").style.display = "none";
} else {
    ssn.parentNode.querySelector("p").style.display = "block";
    status = false;
}


if ( /[a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/g.test(email.value) ) {
    email.parentNode.querySelector("p").style.display = "none";
} else {
    email.parentNode.querySelector("p").style.display = "block";
    status = false;
}


let phoneVal = phone.value.replace(/^\+46/g, "0").replace(/[^\d]+/g, "");
if ( /(\+\d{2}|0)\d{7,9}/g.test(phoneVal) ) {
    phone.parentNode.querySelector("p").style.display = "none";
} else {
    phone.parentNode.querySelector("p").style.display = "block";
    status = false;
}

checkboxes.forEach(function(checkbox){
    
    if ( checkbox.checked !== true ) {
        checkbox.parentNode.querySelector("p").style.display = "block";
        status = false;
    } else {
        checkbox.parentNode.querySelector("p").style.display = "none";
    }

    return;
});

if ( ! status ) {
    event.preventDefault();
    btn.removeAttribute("disabled");
    return false;
}

});
</script>
' : $content) . '<script>if ( window.history.replaceState ) {window.history.replaceState( null, null, window.location.href );}</script>';