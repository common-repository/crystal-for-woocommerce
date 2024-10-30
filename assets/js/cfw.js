jQuery(document).ready(function($) {
    var isFromSignup = getParameterByName("from_signup");
    var isSavedOptions = getParameterByName("saved_options");
    if (isFromSignup || isFromSignup !== null) {
        $("#cfw-login")[0].click();
    }
    if (isSavedOptions || isSavedOptions !== null) {
        var closePopupTimeout = setTimeout(function() {
            clearTimeout(closePopupTimeout);
            if (window.opener) {
                window.close();
            }
        }, 3000);
    }
    $("form#cwf_woo-keys").submit(function(e) {
        e.preventDefault();
        var $inputs = $("form#cwf_woo-keys :input");

        var values = {};
        $inputs.each(function() {
            if (this.name !== "submit_woo-keys") {
                values[this.name] = $(this).val();
            }
        });
        setTimeout(function() {
            window.location.href = `https://crystal.ai/login/woocommerce/domain?domain=${
                values.domain
            }&name=${values.name}&timestamp=${values.timestamp}&redirectUri=${
                values.redirectUri
            }&apiKey=${values.apiKey}&apiSecret=${values.apiSecret}`;
        }, 500);
        return false;
    });
});

/**
 * Returns the query parameter filtereb by name
 **/
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return "";
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
