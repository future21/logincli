<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />


    <!--<script type="text/javascript" charset="utf8" src="media/js/jquery.js"></script>-->
    <script type="text/javascript" src="resources/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="resources/usuarios.js"></script>
    <script type="text/javascript" charset="utf8" src="media/js/jquery.dataTables.js"></script>

    <link rel="stylesheet" type="text/css" href="resources/screen.css" />
    <link rel="stylesheet" type="text/css" href="resources/style.css" />
    <link rel="stylesheet" type="text/css" href="media/css/jquery.dataTables.css" />



</head>
<body>
    <div id ="block"></div>
    <div class="container">
        <!--<h1 class="title"></h1>-->
        <div id="popupbox"></div>
        <div id="content">
            <?php include_once ($view->contentTemplate) ?>
        </div>
    </div>
</body>
</html>
