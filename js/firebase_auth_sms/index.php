<?php include 'config.php'; ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Firebase Auth SMS</title>
  </head>
  <body>

    <form onsubmit="event.preventDefault();sendOTP(this)">
        <input type="text" name="phone" id="phone" value="+919824819888">
        <div id="recaptcha-container"></div>
        <button>Submit</button>
    </form>
    
    
    <form onsubmit="event.preventDefault();verifyOTP(this)">
        <input type="text" name="code" id="code" value="123456">
        <button>Submit</button>
    </form>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js"></script>
    <script >
        
        const firebaseConfig = {
            apiKey: "<?= FIREBASE_API_KEY;?>",
            authDomain: "<?= FIREBASE_AUTH_DOMAIN;?>",
            projectId: "<?= FIREBASE_PROJECT_ID?>",
            appId: "<?= FIREBASE_APP_ID?>",
        };
        
        firebase.initializeApp(firebaseConfig);

        $(document).ready(function(){
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
            recaptchaVerifier.render();
        })
        
        function sendOTP() {
            var number = $("#phone").val();
            //console.log(number);
            const recaptchaResponse = grecaptcha.getResponse(window.recaptchaWidgetId);
            //console.log('recaptchaResponse',recaptchaResponse);
            if(recaptchaResponse!='' && recaptchaResponse!=undefined){
                firebase.auth().signInWithPhoneNumber(number, window.recaptchaVerifier).then(function (confirmationResult) {
                    window.confirmationResult = confirmationResult;
                    grecaptcha.reset(window.recaptchaWidgetId);
                    //console.log('confirmationResult',confirmationResult);
                    //otp sent successfully code here to show verify otp page
                
                    
                    
                }).catch(function (error) {
                    //console.error(error);
                });
            }
        }
    
        function verifyOTP() {
            var code = $("#code").val();
            //console.log('code',code);
            window.confirmationResult.confirm(code).then(function (result) {
                //console.log(result);
                //otp verified successfully code here for next step
                $.ajax({
                    url:'check.php',
                    method:'post',
                    data:{token:window.confirmationResult.verificationId,code:code},
                    success:function(response){
                        console.log(response);
                    }
                })
            }).catch(function (error) {
                //console.log(error);
            });
        }
    </script>

</body>
</html>