<?php
    echo '<div class="cfw-box">
    <h3>crystal for WooCommerce Settings</h3>
    <div class="cfw-box-content">';
        $pixels = get_option('cfw_pixels');
        updatePixels($pixels);
        $activePixel =  (array) get_option('cfw_active_pixel');
        $plan = get_option('cfw_plan');
        $at = get_option('cfw_at');
        if($at && $plan){
            $cfw_saved_options = isset($_GET['saved_options']) ? $_GET['saved_options'] : null;
            if($cfw_saved_options !== null) {
                echo '<h3>Congrats!</h3>
                <p>crystal for WooCommerce has been successfully configured.</p>';
            } else{
                switch($plan){
                    case '1': // free
                        echo '<p>You must be a crystal premium user to run the plugin.</p>
                        <p>
                            <a class="cfw-link" href="'.CRYSTAL_URL.'/profile/upgrade/1" target="_blank">Upgrade</a> now to access all the features and start promoting your e-shop with effective campaigns!
                        </p>';
                        break;
                    case '3': // enterprise
                        break;
                    default: // premium
                        if(count($pixels) > 0 && $pixels !== ''){
                            echo'<p>Plug-in configuration complete!</p>
                            <p>Now you are ready to monitor and promote your e-shop.</p>
                                <form method="post" name="cfw-options">
                                    <label for="active_pixel">Select your Pixel ID </label>
                                    <select name="active_pixel" id="active_pixel">
                                        <option selected="selected" value="'.$activePixel['name'].'">'.$activePixel['name'].'</option>';
                                        foreach ($pixels as $key => $pixel) {
                                            $pixel =  (array) $pixel ;
                                            if($pixel['name'] !== $activePixel['name']){
                                            echo '<option value="'.$key.'">'.$pixel['name'].'</option>';
                                        }
                                    }
                                echo '</select>
                                <input class="button-primary" type="submit" name="Save" value="Save" />
                            </form>';
                        }else {
                        echo'<p>Almost done…</p>
                        <p>
                            Activate <a class="cfw-link" href="'.CRYSTAL_URL.'/campaigns/facebook_marketing" target="_blank">"Facebook Campaigns"</a> on crystal to complete the configuration and start promoting your business with effective campaigns!
                        </p>';
                        }
                    break;
                }
            }
        }else{
            $cfw_at = isset($_GET['cfw_at']) ? $_GET['cfw_at'] : null;
            $cfw_plan = isset($_GET['cfw_plan']) ? $_GET['cfw_plan'] : null;

            $cfw_saved_options = isset($_GET['saved_options']) ? $_GET['saved_options'] : null;
            if($cfw_at && $cfw_at !== '' && $cfw_plan && $cfw_plan !== '') { // User is from crystal_login
                saveOptionsAndRedirect($_GET, admin_url('options-general.php?page=cfw_settings&saved_options'));
            }else if($cfw_saved_options !== null){
                echo '<h3>Congrats!</h3>
                <p>crystal for WooCommerce has been successfully configured.</p>';
            }else{ // Show login button

                $wcKeys = generateKeys();
                $date = new DateTime();
                $websiteUrl = get_site_url();
                $websiteName = get_bloginfo('name');
                $timestamp =  $date->getTimestamp();
                $redirectUri = admin_url('options-general.php?page=cfw_settings');
                if(isset($wcKeys) && isset($wcKeys->consumer_key) && (is_ssl() || strpos(get_site_url(), 'https://') === 0)) {
                    //$wcKeys->revoke_url
                    echo '<p>crystal for WooCommerce has been successfully installed.</p>
                    <p>To get started login with crystal or sign-up if you don’t have an account.</p>';
                   
                    $apiKey = $wcKeys->consumer_key;
                    $apiSecret = $wcKeys->consumer_secret;
                    $QP = 'domain='.$websiteUrl.'&name='.$websiteName.'&timestamp='.$timestamp.'&redirectUri='.$redirectUri.'&apiKey='.$apiKey.'&apiSecret='.$apiSecret;
                    $login_url = CRYSTAL_URL.'/login/woocommerce/domain?'.$QP;
                    echo '<a id="cfw-login" class="button-primary" href="'.$login_url.'" title="Login with crystal.ai">';
                    echo esc_attr_e( 'Login with crystal.ai' );

                    echo '</a>
                    <a href="'.CRYSTAL_URL.'/sign-up/woocommerce" class="button-primary white" title="Sign up">';
                        echo esc_attr_e( 'Sign up' );
                    echo '</a>';
                } else if(!isset($wcKeys) || ! isset($wcKeys->consumer_key)){
                    echo '<p>There is a problem with the automatic WooCommerce key generation.</p>
                    <p>Please, go to this <a target="_blank" href="'.get_site_url().'/wp-admin/admin.php?page=wc-settings&tab=advanced&section=keys&create-key=1">link</a> to generate the keys manually.</p>
                    <p>If you are not using the last version of WooCommerce go to this other <a target="_blank" href="'.get_site_url().'/wp-admin/admin.php?page=wc-settings&tab=api&section=keys&create-key=1">link</a>.</p>
                    <p>Set the permissions as <b>Read/Write</b> then copy and paste the keys in the form below.</p>
                    <form class="cwf_woo-keys" id="cwf_woo-keys" name="cwf_woo-keys" method="post">
                        <div class="cwf_form-field"><label for="consumer_key"><h4>Consumer key</h4></label><input required type="text" name="apiKey" class="regular_text" id="consumer_key"></input></div>
                        <div class="cwf_form-field"><label for="consumer_secret"><h4>Consumer secret</h4></label><input required type="text" name="apiSecret" class="regular_text" id="consumer_secret"></input></div>
                        <div class="cwf_form-field"><input type="hidden" name="domain" value="'.$websiteUrl.'"></input></div>
                        <div class="cwf_form-field"><input type="hidden" name="name" value="'.$websiteName.'"></input></div>
                        <div class="cwf_form-field"><input type="hidden" name="timestamp" value="'.$timestamp.'"></input></div>
                        <div class="cwf_form-field"><input type="hidden" name="redirectUri" value="'.$redirectUri.'"></input></div>
                        <div class="submit_woo-keys-wrapper">
                        <input value="Login with crystal.ai" type="submit" class="button-primary"  id="submit_woo-keys" name="submit_woo-keys"/> <a href="'.CRYSTAL_URL.'/action/woocommerceSignup/'.$timestamp.'" class="button-primary white" title="Sign up">';
                        echo esc_attr_e( 'Sign up' );
                    echo '</a></div>
                    </form>';
                }else {
                    echo '<p>Oops! The plugin is available only for SSL certified URLs (https format).</p>
                    <p>To start using crystal for WooCommerce provide your e-shop with a Secure Sockets Layer certificate.</p>';
                }
            }
        }
    echo '</div>
</div>';

/**
 * Updates cfw_pixels option and active facebook pixel
 * @param  [array] $pixels array of pixels
 */
function updatePixels($pixels) {
	global $wpdb;

    if(isset($_POST['active_pixel'])) {
        $pixelKey = $_POST['active_pixel'];
        update_option('cfw_active_pixel', $pixels[$pixelKey]);
		if(isset($pixels[$pixelKey])){
        	$pixels[$pixelKey]->post_type = 'pixel';
			$WC_Webhooks = new WC_Webhooks($pixels[$pixelKey]);
	        $args = [
	            'field' => 'active_pixel_id',
	            'value' => $pixels[$pixelKey]->id
	        ];
        }else {
            $pixels[0]->post_type = 'pixel';
            $WC_Webhooks = new WC_Webhooks($pixels[0]);
            $args = [
                'field' => 'active_pixel_id',
                'value' => $pixels[0]->id
            ];
        }
        $WC_Webhooks->update($args);
    }else {
	    $access_token_option = $wpdb->get_results( "SELECT * FROM  ".$wpdb->prefix."options WHERE option_name = 'cfw_at'" );
		if (isset($access_token_option) && isset($access_token_option[0]) && isset($access_token_option[0]->option_value)) {
                // refresh user infos
                $wc_api = new CFW_Api($access_token_option[0]->option_value);
                $wc_api->getUserData();
            }

    }
}

/**
 * Save the opptions and redirect to the specified url
 * @param  [array] $options      ['option1' => 'value1']
 * @param  [string] $redirect_url [url to redirect]
 */
function saveOptionsAndRedirect($options, $redirect_url){
    foreach($options as $index => $value) {
        if(strrpos($index, 'cfw') !== false) {
            update_option($index, $value);
        }
    }
    wp_redirect($redirect_url);

}

/**
 * Activate WC Api and generate new keys
 * @return object of keys
 */
function generateKeys() {

    //ajax admin url
    $url = admin_url( 'admin-ajax.php' );
    //_nonce code
    $update_api_nonce = wp_create_nonce( 'update-api-key' );
    $response = wp_remote_post( $url, [
        'method' => 'POST',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => ['Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'],
        'cookies' => $_COOKIE,
        'body' => [
            'action' => 'woocommerce_update_api_key',
            'security' =>    $update_api_nonce,
            'key_id' =>      0,
            'user' =>        get_current_user_id(),
            'description' => 'crystal_api',
            'permissions' => 'read_write'
        ],
    ]);

    if(isset($response->errors) && isset($response->errors['http_request_failed'])) {
        $error = $response->errors['http_request_failed'][0];
        if(strpos($error, 'SSL') > 0) {
            add_action('admin_notices', function() {
                echo '<div class="error below-h3">
                    <p>Service temporary unavailable. Come back soon.</p>
                </div>';
            });
            return $response->get_error_message();
        }
    } else if(isset($response->errors)){
        add_action('admin_notices', function() {
            echo '<div class="error below-h3">
                <p>Service temporary unavailable. Come back soon.</p>
                <p>'.$response->get_error_message().'</p>

            </div>';
        });
        return $response->get_error_message();
    } else {
        if($response['body'] !== '0') {
            $responseData = json_decode($response['body'])->data;
            return $responseData;
        }

    }
} ?>
