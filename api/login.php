<?php
ini_set('session.cookie_domain', '.vercel.app');
session_name('INSIGHT');
$bCookiesEnabled = isset($_COOKIE['insight_cookie_check']);
                    if(!$bCookiesEnabled){
                        setcookie('insight_cookie_check', 'insight', 0, '/'); // we'll set every time they start a new browser session (for UK compliance)
                  }
?>

<html lang = "en">

<head>
    <title>A Simple Login Form</title>
    <!--<link rel="stylesheet" a href="css\style.css">-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/fontawesome.min.css" integrity="sha512-giQeaPns4lQTBMRpOOHsYnGw1tGVzbAIHUyHRgn7+6FmiEgGGjaG0T2LZJmAPMzRCl+Cug0ItQ2xDZpTmEc+CQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body{
            margin: 0 auto;
            height: 100vh !important;
        }

        #mainDiv{
            display: flex;
            flex-direction: row;
            height: 100vh;
        }

        #rightCard,
        #leftCard
        {
            flex: 1;
        }

        #leftCard > img
        {
            width: 100%;
            height: auto;
        }

        .container{
            text-align: center;
            margin: 0 auto;
        }

        .container img{
            width: 150px;
            height: 150px;
            margin-top: -60px;
        }

        input[type="text"],input[type="password"]{
            margin-top: 30px;
            height: 45px;
            width: 300px;
            font-size: 18px;
            margin-bottom: 20px;
            background-color: #fff;
            padding-left: 40px;
        }

        .form-input::before{
            content: "\f007";
            font-family: "FontAwesome";
            padding-left: 07px;
            padding-top: 40px;
            position: absolute;
            font-size: 1.5rem;
            color: #2980b9;
        }

        .form-input:nth-child(2)::before{
            content: "\f023";
        }

        .btn-login{
            padding: 15px 25px;
            border: none;
            background-color: #27ae60;
            color: #fff;
        }

    </style>
</head>

<body>

<div id="mainDiv">
    <div id="leftCard">
    </div>
    <div id="rightCard">
        <div class = "container">

            <?php

            if (isset($_POST['login']) && !empty($_POST['username'])
                && !empty($_POST['password'])) {

                $mysqli = new mysqli("db4free.net", "jeyasuriya", "jeyasuriya", "demopoc");

                $sql="select * from loginform where user='".$_POST['username']."'AND pass='".$_POST['password']."' limit 1";

                $result=$mysqli->query($sql);


                if(mysqli_num_rows($result) > 0){
                    $userDetails = $result->fetch_array(MYSQLI_ASSOC);
                    $admin_link = 'https://admin-portal-nextjs.vercel.app/';

                    session_start();

                    $_SESSION['id'] = session_id();
                    $_SESSION['userid'] = $userDetails['id'];

                    $key = 'example_key';
                    $payload = [
                        'sessionId' => $_SESSION['id'],
                        'adminAccess' => 'first-level'
                    ];

                    /**
                     * IMPORTANT:
                     * You must specify supported algorithms for your application. See
                     * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
                     * for a list of spec-compliant algorithms.
                     */
                    /*$jwt = JWT::encode($payload, $key, 'HS256');*/
                    /*            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

                                print_r($jwt);

                                $decoded_array = (array) $decoded;*/

                    /**
                     * You can add a leeway to account for when there is a clock skew times between
                     * the signing and verifying servers. It is recommended that this leeway should
                     * not be bigger than a few minutes.
                     *
                     * Source: http://self-issued.info/docs/draft-ietf-oauth-json-web-token.html#nbfDef
                     */
                    /*            JWT::$leeway = 60; // $leeway in seconds
                                $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

                                print_r($decoded);*/

                    // check for our session cookie
//                    $bCookiesEnabled = isset($_COOKIE['insight_cookie_check']);
//                    if(!$bCookiesEnabled){
//                        setcookie('insight_cookie_check', 'insight', 0, '/'); // we'll set every time they start a new browser session (for UK compliance)
//                        /*setcookie('TOKEN', $jwt, 0, '/');*/
//                    }
                    $_SESSION['insight'] = session_id();

                    $insert_session_id="INSERT INTO session	(sid, uid, status, updated) VALUES ('".$_SESSION['id']."','".$userDetails['id']."' , 1, NOW()) 
            ON DUPLICATE KEY UPDATE status = 1, updated = NOW()";

                    $inserted=$mysqli->query($insert_session_id);
                    if($userDetails['isAdmin'] == 1) {
                        echo "<h2> You Have Successfully Logged in.</h2> <br>Please click here <a target = '_blank' href='".$admin_link."'> to visit admin portal</a>.";
                        echo "<br> Click here to <a href = 'logout.php' title = 'Logout'>logout.";
                        exit();
                    }
                    else{
                        echo " <h2> You Have Successfully Logged in. </h2>";
                        echo "<br> Click here to <a href = 'logout.php' title = 'Logout'>logout.";
                        exit();
                    }
                }
                else{
                    echo " You Have Entered Incorrect Password, try again.";
                }
            }
            ?>
        </div> <!-- /container -->

        <div class = "container">
            <h1>Welcome to my site</h1>

            <form  class = "form-signin" role = "form"
                   action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);
                   ?>" method = "post">
                <div class="form-input">
                    <input type = "text" class = "form-control"
                           name = "username" placeholder = "username"
                           required autofocus></br>
                </div>
                <div class="form-input">
                    <input type = "password" class = "form-control"
                           name = "password" placeholder = "password" required>
                </div>
                <button class = "btn-login" type = "submit"
                        name = "login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>