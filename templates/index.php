<html>
<head>
    <title>Home :: skil-share</title>
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
            <h3>Welcome, <?php echo $user;?>!</h3>
            <?php if(isset($_SESSION["login"]) && $_SESSION["login"] == true):?>
                <button class="btn waves-effect waves-light grey darken-1" onclick="window.location = '/upload'">
                    Upload a file
                    <i class="material-icons left">unarchive</i>
                </button>
                <button data-target="sharex" class="btn waves-effect waves-light grey darken-1">
                    Show ShareX Config
                    <i class="material-icons left">settings</i>
                </button>
                <div id="sharex" class="modal">
                    <div class="modal-content grey darken-3">
                        <h4>ShareX Config</h4>
                        <div class="divider"></div>
                        <br>
                        <div class="black lighten-1 left-align" style="border-radius: 10px;">
                            <pre>
{
    "Name": "skil-share",
    "DestinationType": "None",
    "RequestType": "POST",
    "RequestURL": "<?php echo $config["app"]["uploadHost"];?>upload",
    "FileFormName": "d",
    "Arguments": {
        "name": "<?php echo $user;?>",
        "pass": "YOURPASSWORD"
    },
    "ResponseType": "Text"
}</pre>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <button class="btn waves-effect waves-light grey darken-1" onclick="window.location = '/login'">
                    Login
                    <i class="material-icons left">vpn_key</i>
                </button>
                <button class="btn waves-effect waves-light grey darken-1" onclick="window.location = '/register'">
                    Register
                    <i class="material-icons left">person_add</i>
                </button>
            <?php endif; ?>
            <br>
            <br>
        </div>
    </div>
</main>
<?php include('include/after_body.html'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('.modal').modal();
    });
</script>
</body>
</html>