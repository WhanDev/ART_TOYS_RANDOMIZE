<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <title>Sidenav Example</title>
    <style>
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f8f9fa;
            padding-top: 20px;
            transition: 0.3s;
        }

        .sidenav a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #333;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            background-color: #ddd;
        }

        @media screen and (max-width: 768px) {
            .sidenav {
                width: 0;
                padding-top: 60px;
            }
            .sidenav a {
                text-align: center;
                padding: 8px;
                font-size: 16px;
            }
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        @media screen and (max-width: 768px) {
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="#">Home</a>
    <a href="#">Services</a>
    <a href="#">Clients</a>
    <a href="#">Contact</a>
</div>

<div class="content">
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Open Sidebar</span>
    <h2>Responsive Sidenav Example</h2>
    <p>This is a basic example of a Bootstrap-based responsive sidenav.</p>
</div>

<script>
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        document.querySelector(".content").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.querySelector(".content").style.marginLeft = "0";
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
