<?php 
    use app\core\Application;
    use app\core\component\Component;
    use app\core\Request;
    use app\models\PatientNotification;

    $component=new Component();
    $noti=new PatientNotification();
    $request=new Request();
    Application::$app->session->set('notiurl',$request->getURL());
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width" initial-scale=1.0>
    <!-- <title>
        PHP & MySql Blog Application with Admin
    </title> -->
    <link rel="stylesheet" href="./media/css/style.css">
    <link rel="stylesheet" href="./media/css/spec-style.css">
    <link rel="stylesheet" href="./media/css/patient-style.css">
    <!-- <link rel="stylesheet" href="./media/header-footer.css"> -->
    <link rel="stylesheet" href="https:unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora&display=swap" rel="stylesheet">

</head>
<body>
<?php if(!Application::$app->session->get('shownoti')):?>
    <div class="bg">

    </div>
<?php else:?>
    <div class="bg noti-black">

    </div>
<?php endif;?>
<nav class="nav" >
    <div class="nav_row--top shadow">
        <div class="nav_row--top_logo">
            <img src="./media/images/logo-1.png" id="logo">
        </div>
        <div class="nav_row--top_user flex ">
        <?php if(Application::$app->session->get('user')) :?>
            <div class="noti-container"> 
                <div class="bell-container">
                    <img  src="./media/images/patient/notification bell.png">
                    <div class="noti-counter">
                        <?=$noti->getNotifcationCount(); ?>
                    </div>
                    
                </div>
            </div>
            
            <div class="nav-box">
                <h3><?php echo Application::$app->session->get('userObject')->name?></h3>
                        <ul>
                            <div class="nav-box-item">
                                <li>
                                    <a href="/ctest/patient-all-appointment">Dashboard</a>
                                </li>
                            </div>
                            <div class="nav-box-item">
                                <li>
                                    <a href="/ctest/logout">Log Out</a>
                                </li>
                            </div>
                        </ul>
                    </div>
                    
                    <?php else:?>
                        <?php echo $component->button('sign in','','Sign In','button--class-1','sign in') ?>
                        <?php endif ?>
                        
                    </div>
                </div>
                <div class="nav_row--bottom box-shadow">
                    <h2 class="uppercase">Anspaugh Care</h2>
                    
                </div>
            </nav>
        <?php $notifications = $noti->fetchAssocAll(['patient_ID' => Application::$app->session->get('user'), 'is_read' => 0]); ?>
             <?php if ($notifications) : ?>
        <div class="noti-wrapper yesnoti">
            <div class="noti-text">
                <div class="flex">
                    <h1>Your Notifications</h1>
                    <img src="./media/anim_icons/noti.gif">
                </div>
                <div class="noti-list">
                    <?php $notifications = $noti->fetchAssocAll(['patient_ID' => Application::$app->session->get('user'), 'is_read' => 0]); ?>
                    <?php if ($notifications) : ?>
                        <?php foreach ($notifications as $notification) : ?>
                            <div class="noti-item">
                                <?= $notification['text'] ?> <?= $component->button('btn', '', 'Mark As Read', 'mark-as-read-btn', $notification['noti_ID']) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <img src="./media/anim_icons/nonoti.gif">
                        <center>
                            <h3>No Notifications to Show</h3>
                        </center>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else : ?>
        <div class="noti-wrapper ">
            <div class="noti-text-l">
                <div class="flex">
                    <h1>Your Notifications</h1>
                    <img src="./media/anim_icons/noti.gif">
                </div>
                <div class="noti-list">
                    <?php $notifications = $noti->fetchAssocAll(['patient_ID' => Application::$app->session->get('user'), 'is_read' => 0]); ?>
                    <?php if ($notifications) : ?>
                        <?php foreach ($notifications as $notification) : ?>
                            <div class="noti-item">
                                <?= $notification['text'] ?> <?= $component->button('btn', '', 'Mark As Read', 'mark-as-read-btn', $notification['noti_ID']) ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <img src="./media/anim_icons/nonoti.gif">
                        <center>
                            <h3>No Notifications to Show</h3>
                        </center>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
            <script>
                const bg=document.querySelector(".bg");
                
                const button=document.getElementById('sign in');
                if(button){
                    button.addEventListener('click',()=>{
                        location.href="/ctest/";
                    })
                }
                const image=document.getElementById("logo");
                image.addEventListener('click',()=>{
                    location.href="/ctest/patient-main"
                })
                isset=0;
                const wrapper=document.querySelector(".noti-wrapper");
                const bell=document.querySelector(".bell-container");
                <?php if(!Application::$app->session->get('shownoti')):?>
                    wrapper.style.display='none';
                <?php endif;?>
                bell.addEventListener('click',()=>{
                    if(isset==0){
                        wrapper.style.display='block';
                        bg.classList.add('noti-black');
                        isset=1;
                    }
                    else{
                        wrapper.style.display='none';
                        bg.classList.remove('noti-black');
                        isset=0; 
                    }
                 })
     bg.addEventListener('click',()=>{
        wrapper.style.display='none';
        bg.classList.remove('noti-black');
            isset=0;
     })
     const mark=document.querySelectorAll(".mark-as-read-btn");
     mark.forEach((el)=>{
        el.addEventListener('click',()=>{
            location.href="patient-my-detail?spec=notification-rem&id="+el.id;
        })
     })
</script>
<?php Application::$app->session->set('shownoti',false)?>