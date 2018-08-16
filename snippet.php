function my_em_event_form_captcha(){
  if ( !is_user_logged_in() ){
	?><p><script src="https://www.google.com/recaptcha/api.js" async defer></script><div class="g-recaptcha" data-sitekey="6LdAxRYTAAAAAHOShU2E4CqXlc_auqGMcabqVFXd"></div></p><?php
  }
}
add_action('em_booking_form_footer','my_em_event_form_captcha');


function my_em_event_form_captcha_check() {
  global $EM_Booking;

  if ( !is_user_logged_in() ){
    $captcha;

    if(isset($_POST['g-recaptcha-response'])){
      $captcha=$_POST['g-recaptcha-response'];
    }

    if(!$captcha){
      $EM_Booking->errors[]=__('Veuillez valider le captcha');
    }
    $secretKey = "please_put_your_secretkey_here";
    $ip = $_SERVER['REMOTE_ADDR'];
    $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
    $responseKeys = json_decode($response,true);
    if(intval($responseKeys["success"]) !== 1) {
      $EM_Booking->errors[]=__('Mauvais captcha');
      unset($_REQUEST['em_tickets']);
    }
  }
}
add_filter('em_booking_get_post_pre','my_em_event_form_captcha_check',0);
