<?php 
    use app\core\Application;
    use app\models\Delivery;
use app\models\Employee;

    $user=new Employee;
    $user_availability= $user->get_rider_availability(Application::$app->session->get('userObject')->emp_ID)[0];
    $availability=$user_availability['availability'];

?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width" initial-scale=1.0>
    <!-- <title>
        PHP & MySql Blog Application with Admin
    </title> -->
    <link rel="stylesheet" href="./media/css/style.css">
    <link rel="stylesheet" href="./media/css/delivery-style.css">
    <link rel="stylesheet" href="./media/css/pharmacy-style.css">
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

            

            <div class="nav-box">
                <div class="flex">
                    <?php echo Application::$app->session->get('userObject')->name ?>
                    <img src=<?php echo "./media/images/emp-profile-pictures/".Application::$app->session->get('userObject')->img ?>>
                </div>
                <ul>
                    <div class="nav-box-item">
                        <li>
                            <a href="/ctest/delivery-my-deliveries">Dashboard</a>
                        </li>
                    </div>
                    <div class="nav-box-item">
                        <li>
                            <a href="/ctest/employee-logout">Log Out</a>
                        </li>
                    </div>
                </ul>
            </div>
          
            <!-- switch begin -->
            <div class="active-switch-container">
                <label class="active-switch" >
                    <?php if($availability==='AV'): ?>
                        <input type="checkbox" id="activeCheckbox" onclick="is_checked()" checked>
                    <?php else: ?>
                        <input type="checkbox" id="activeCheckbox" onclick="is_checked()">
                    <?php endif; ?>
                <span class="slider"></span>
                </label>
            </div>
            <!-- switch end -->
        </div>
    </div>
    <div class="nav_row--bottom">
        <h2 class="uppercase">Anspaugh Care</h2>
       
    </div>
</nav>
<script>
    function is_checked() {
        if ( document.getElementById("activeCheckbox").checked ) {
            // location.href="/ctest/login";
            const online_path="/ctest/delivery-online";
            fetch(online_path,{
                method:"GET"
            }).then((res)=>res.json())
            .then((data)=>{
                console.log(data);
            })
            // console.log('checked');
            // console.log("ckeck");
        } else {
            const online_path="/ctest/delivery-offline";
            fetch(online_path,{
                method:"GET"
            }).then((res)=>res.json())
            .then((data)=>{
                console.log(data);
            })
            // console.log('Checkbox is not checked!');
            // alert("unckeck");
        }
    }

    // const button=document.getElementById('sign in');
    // if(button){
    //     button.addEventListener('click',()=>{
    //         location.href="/ctest/login";
    //     })
    // }

    // const myCheckbox = document.getElementById('myCheckbox');

    // const myCheckbox = document.getElementById('myCheckbox');

    // myCheckbox.addEventListener('change', function() {
    // if (myCheckbox.checked) {
    //     // Execute code when checkbox is checked
    //     console.log('Checkbox is checked!');
    // } else {
    //     // Execute code when checkbox is not checked
    //     console.log('Checkbox is not checked!');
    // }
    // });

</script>