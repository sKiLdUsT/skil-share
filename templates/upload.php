<html>
<head>
    <title>Upload :: skil-share</title>
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
<main class="container center">
    <div class="valign-wrapper">
        <div class="grey darken-2 z-depth-1 white-text container valign">
            <h3>Upload</h3>
            <div class="divider"></div>
            <br>
            <div class="row">
                <form method="POST" id="upload" action="/upload" enctype="multipart/form-data" class="col s6 push-s3">
                    <div class="row">
                        <input type="hidden" name="isBrowser" value="true">
                        <div class="file-field input-field">
                            <div class="btn grey darken-1">
                                <span>Select file</span>
                                <input type="file" name="d">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                        </div>
                        <div class="input-field col s12">
                            <button type="submit" id="submit" class="btn waves-effect waves-light grey darken-1">
                                Upload
                                <i class="material-icons right">send</i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php include('include/after_body.html'); ?>
</body>
</html>