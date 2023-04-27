<?php 
    use app\core\Application;
    use app\core\component\Component;
    $component=new Component();
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
<nav class="nav" >
    <div class="nav_row--top shadow">
        <div class="nav_row--top_logo">
            <img src="./media/images/logo-1.png">
        </div>
        <div>
            <div class="search-medicine-container">
            <?php echo $component->searchbar('',"name","search-bar--class1","Search by medicine name","search");?>
            <?php if(Application::$app->session->get('user')):?>
                <div class="main-cart-container">
                    <?= $component->cartview() ?>
                </div>
            <?php endif; ?>
            <script src="./media/js/main.js">

            </script>
            <script>
                const searchbar=e('search');
                const btn=e('bsearch');
                btn.addEventListener("click",()=>{
                    location.href="patient-pharmacy?cmd=search&value="+searchbar.value+"&page=1";
                });
            </script>
        </div>
        </div>
        <div class="nav_row--top_user flex ">
        <?php if(Application::$app->session->get('user')) :?>
                <div class="bell-container">
                    <img  src="./media/images/patient/notification bell.png">
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
    <div class="top-stripe">
            <a href="#">Medication</a>
            <a href="#">Wellness</a>
            <a href="#">Personal Care</a>
            <a href="#">Medical Devices</a>
    </div>
</nav>
<script>
    
    const button=document.getElementById('sign in');
    if(button){
        button.addEventListener('click',()=>{
            location.href="/ctest/";
        })
    }
</script>
