function goLogIn() {
    'use strict';
    window.location.href = Routing.generate('fb_security_check');
}

function onFbInit() {
    'use strict';
    if (typeof FB !== 'undefined' && FB !== null) {
        FB.Event.subscribe('auth.statusChange', function (response) {
            if (response.session || response.authResponse) {
                setTimeout(goLogIn, 500);
            } else {
                window.location.href = Routing.generate('fb_security_logout');
            }
        });
    }
}

function fb_login() {
    'use strict';
    FB.login(function (response) {

        if (response.authResponse) {
            console.log('Welcome!  Fetching your information.... ');
            //console.log(response); // dump complete info
            //access_token = response.authResponse.accessToken; //get access token
            //user_id = response.authResponse.userID; //get FB UID
        } else {
            //user hit cancel button
            console.log('User cancelled login or did not fully authorize.');

        }
    }, {
        scope: 'publish_stream,email'
    });
}


