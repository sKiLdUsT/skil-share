<html>
<head>
    <title><?php echo $data["oldName"]; ?> :: skil-share</title>
    <?php include('include/head.html'); ?>
</head>
<body class="grey darken-3">
<div class="navbar-fixed">
    <nav role="navigation" class="transparent z-depth-1">
        <div class="nav-wrapper container">
            <a href="/" class="brand-logo"><b>skil-share</b></a>
            <?php if(isset($_SESSION["login"]) && $_SESSION["login"] == true)include('include/nav_menu.php'); ?>
        </div>
    </nav>
</div>
<main class="center">
    <div class="valign-wrapper">
        <div class="grey darken-2 z-depth-2 white-text valign">
            <br>
            <div class="row container">
                <div class="col s12" id="file">
                    <?php
                preg_match('/(.*)\//', $mimeType, $match);
                switch($match[1]) {
                    case 'image':
                        echo '<img class="responsive-img z-depth-1 hoverable materialboxed" src="/'.$data["fileID"].'?raw">';
                        break;
                    case 'video':
                        echo '<video class="responsive-video" controls><source src="/'.$data["fileID"].'?raw" type="'.$mimeType.'"></video>';
                        break;
                    case 'text':
                        echo '<i class="material-icons large">description</i>';
                        break;
                    default:
                        echo '<i class="material-icons large">content_copy</i>';
                        break;
                    }
                    ?>
                </div>
                <div class="col s12" style="margin-top: 2rem">
                    <div class="row">
                        <div class="col s3 truncate">
                            <b>Name: </b><?php echo $data["oldName"]; ?>
                        </div>
                        <div class="col s3">
                            <b>Size: </b><?php echo round($fileSize / 1024 / 1024, 2); ?>MB
                        </div>
                        <div class="col s3">
                            <b>Type: </b><?php echo $mimeType; ?>
                        </div>
                        <div class="col s3">
                            <b>Uploader: </b><?php echo $uUser; ?>
                        </div>
                    </div>
                </div>
                <button class="btn waves-effect waves-light grey darken-1" onclick="window.open('/<?php echo $data["fileID"] ?>?raw')">
                    Download
                    <i class="material-icons left">get_app</i>
                </button>
            </div>
        </div>
    </div>
</main>
<?php include('include/after_body.html'); ?>
</body>
</html>