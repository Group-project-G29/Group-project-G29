<?php 
    use app\core\Application;
    use app\models\AdminNotification;
    $notificationModel=new AdminNotification();

    
    $count = count($notificationModel->customFetchAll("SELECT * FROM `admin_notification` WHERE is_read = 1;"));

?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width" initial-scale=1.0>
    <!-- <title>
        PHP & MySql Blog Application with Admin
    </title> -->
    <link rel="stylesheet" href="./media/css/style.css">
    <link rel="stylesheet" href="./media/css/admin-style.css">
    <link rel="stylesheet" href="./media/css/nurse-style.css">

    <!-- <link rel="stylesheet" href="./media/header-footer.css"> -->
    <link rel="stylesheet" href="https:unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora&display=swap" rel="stylesheet">

</head>
<body>
<nav class="nav" >
    <div class="nav_row--top">
        <div class="nav_row--top_logo">
            <img src="./media/images/logo-1.png">
        </div>
        <div class="nav_row--top_user flex">
            <a href="/ctest/admin-notification"><div class="noti-icon"><img src="./media/images/patient/notification bell.png" alt="Notification Icon"> </div></a>
            <?php if($count) { ?>
                <div class="msg-count"><?=$count?></div>
            <?php } else { ?>
                <div class="msg-count-0"></div>
            <?php } ?>

            <div class="nav-box">
                        <div class="flex"> 
                            <?php echo Application::$app->session->get('userObject')->name ?>
                            <img src=<?php echo "./media/images/emp-profile-pictures/".Application::$app->session->get('userObject')->img ?>>
                        </div>
                        <ul>
                            <div class="nav-box-item">
                                <li>
                                    <a href="/ctest/admin">Dashboard</a>
                                </li>
                            </div>
                            <div class="nav-box-item">
                                <li>
                                    <a href="/ctest/employee-logout">Log Out</a>
                                </li>
                            </div>
                        </ul>
            </div>
           
          
        </div>
    </div>
    <div class="nav_row--bottom">
        <h2 class="uppercase">Anspaugh Care</h2>
       
    </div>
</nav>