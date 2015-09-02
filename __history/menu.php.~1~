 <?php
ini_set("display_errors",1);
error_reporting(E_ALL);

global $menu;
$menu = new menu($menu);

    class menu {
        public function show() {
$email=$_GET['email'];
$password=$_GET['password'];
?>
<!DOCTYPE html>
<html>
<head>


    <title>UnoComaSeis</title>
    <meta charset="utf-8" />
    <title>UnoComaSeis</title>
    <link href="css/layout.css" rel="stylesheet" type="text/css" />
    <link href="css/menu.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="container">
    <br><br>
    <ul id="nav">
        <li><a href="index.php">Home</a></li>

        <li><a class="hsubs" href="">Datos</a>
            <ul class="subs">
                <li><a href=<?php echo $_SERVER['PHP_SELF'].'?op=1&email='.$email.'&password='.urlencode($password)?>>Abreviados</a></li>
                <li><a href=<?php echo $_SERVER['PHP_SELF'].'?op=2&email='.$email.'&password='.urlencode($password)?>>Usuarios</a></li>
            </ul>
        </li>
        <li><a href="#openModal">Sobre nosotros</a>
                <div id="openModal" class="modalDialog">
                <div>
                    <a href="#close" title="Close" class="close">X</a>
                    <img src="images/RAPPORT_color.jpg" alt="Nosotros"/>
                </div>
                </div>
        </li>
        <!--<li><a href="">Contacto</a></li>-->
        <div id="lavalamp"></div>

    </ul>

    </div>

</body>
</html>

<?php
    }
    }

?>
