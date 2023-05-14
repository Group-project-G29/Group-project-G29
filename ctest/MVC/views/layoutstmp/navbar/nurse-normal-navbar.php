<?php 
    use app\core\Application;
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width" initial-scale=1.0>
    <!-- <title>
        PHP & MySql Blog Application with Admin
    </title> -->
    <link rel="stylesheet" href="./media/css/style.css">
    <!-- <link rel="stylesheet" href="./media/css/pharmacy-style.css"> -->
    <link rel="stylesheet" href="./media/css/doctor-style.css">
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
            <a href="/ctest/nurse"><img src="./media/images/logo-1.png"></a>
        </div>
        <div class="nav_row--top_user flex">
            <div class="nav-box">
                        <div class="flex">
                            <?php echo Application::$app->session->get('userObject')->name ?>
                            <img src=<?php echo "./media/images/emp-profile-pictures/".Application::$app->session->get('userObject')->img ?>>
                        </div>
                        <ul>
                            <div class="nav-box-item">
                                <li>
                                    <a href="/ctest/nurse">Dashboard</a>
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

        <!-- <input type="checkbox" class="toggle-sidebar" id="toggle-sidebar">
        <label for="toggle-sidebar" class="toggle-icon" onclick="toggleMenu()">
            <div class="bar-top"></div>
            <div class="bar-center"></div>
            <div class="bar-bottom"></div>
        </label> -->
        <h2 class="uppercase">Anspaugh Care</h2>
       
    </div>
</nav>
<script>
    const button=document.getElementById('sign in');
    if(button){
        button.addEventListener('click',()=>{
            location.href="/ctest/login";
        })
    }
</script>
<script>
    function toggleMenu(){
        document.getElementById('sidebar').classList.toggle('active');
    }
</script>