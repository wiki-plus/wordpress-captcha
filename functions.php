<?php
 
function wikiplus_my_recaptcha_key(){ 
 $sitekey= "00000000000000000000000000000";  // replace captcha site key 
 $secretkey= "00000000000000000000000000000"; // replace captcha secret key 
 return explode(",", $sitekey.",".$secretkey );   
}

function wikiplus_login_style() { 
    wp_register_script('login-recaptcha', 'https://www.google.com/recaptcha/api.js', false, NULL); 
    wp_enqueue_script('login-recaptcha'); 
 echo "<style>p.submit, p.forgetmenot {margin-top: 10px!important;}.login form{width: 303px;} div#login_error {width: 322px;}</style>"; 
} 
add_action('login_enqueue_scripts', 'wikiplus_login_style'); 

function wikiplus_add_recaptcha_on_login_page() { 
    echo '<div class="g-recaptcha brochure__form__captcha" data-sitekey="'.wikiplus_my_recaptcha_key()[0].'"></div>'; 
} 
add_action('login_form','wikiplus_add_recaptcha_on_login_page'); 

function wikiplus_captcha_login_check($user, $password) { 
    if (!empty($_POST['g-recaptcha-response'])) { 
        $secret = my_recaptcha_key()[1]; 
        $ip = $_SERVER['REMOTE_ADDR']; 
        $captcha = $_POST['g-recaptcha-response']; 
        $rsp = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha .'&remoteip='. $ip); 
        $valid = json_decode($rsp, true); 
        if ($valid["success"] == true) { 
            return $user; 
        } else { 
            return new WP_Error('Captcha Invalid', __('<center>Captcha Invalid! Please check the captcha!</center>')); 
        } 
    } else { 
        return new WP_Error('Captcha Invalid', __('<center>Captcha Invalid! Please check the captcha!</center>')); 
    } 
} 
add_action('wp_authenticate_user', 'wikiplus_captcha_login_check', 10, 2);

?>