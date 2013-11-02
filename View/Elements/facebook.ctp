<div class="call facebookLogin">
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '<?php echo $facebook['appId']; ?>', // App ID
                channelUrl : '<?php echo !empty($facebook['channel']) ? $facebook['channel'] : ''; ?>', // Channel File
                status     : true, // check login status
                cookie     : false, // enable cookies to allow the server to access the session
                xfbml      : true  // parse XFBML
            });

            FB.Event.subscribe('auth.authResponseChange', function(response) {
                if (response.status === 'connected') {
                    setFacebookToken();
                } else if (response.status === 'not_authorized') {
                    FB.login();
                } else {
                    FB.login();
                }
            });
        };

        // Load the SDK asynchronously
        (function(d){
            var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement('script'); js.id = id; js.async = true;
            js.src = "//connect.facebook.net/en_US/all.js";
            ref.parentNode.insertBefore(js, ref);
        }(document));

        // Here we run a very simple test of the Graph API after login is successful. 
        // This testAPI() function is only called in those cases. 
        function setFacebookToken() {
            $('.fb_iframe_widget').remove();
            $('.facebookLogin').children('h2').append(' <span class="green">✔</span>');
            tokens['<?php echo !empty($facebook['token']) ? $facebook['token'] : '{fbtoken}'; ?>'] = FB.getAuthResponse()['accessToken'];
            console.log(tokens);
        }
    </script>
    <h2>Login with Facebook</h2>
    <p>Since this app requires facebook login, please do so before proceeding.</p>
    <fb:login-button show-faces="false" width="200" max-rows="1"></fb:login-button>
</div>