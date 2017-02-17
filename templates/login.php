<html>
<head>
    <title>Login :: skil-share</title>
    <?php include('include/head.html'); ?>
</head>
<body class="grey darken-3">
    <div class="navbar-fixed">
        <nav role="navigation" class="transparent z-depth-1">
            <div class="nav-wrapper container">
                <a href="/" class="brand-logo"><b>skil-share</b></a>
            </div>
        </nav>
    </div>
    <main class="container center">
        <div class="valign-wrapper">
            <div class="grey darken-2 z-depth-1 white-text container valign">
                <h3>Login</h3>
                <div class="divider"></div>
                <br>
                <div class="row">
                    <form method="POST" id="login" action="/login" class="col s6 push-s3">
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="name" name="name" type="text" class="validate">
                                <label for="name">Username</label>
                            </div>
                            <div class="input-field col s12">
                                <input id="password" name="pass" type="password" class="validate">
                                <label for="password">Password</label>
                            </div>
                            <div class="input-field col s12">
                                <button type="submit" id="submit" class="btn waves-effect waves-light grey darken-1">
                                    Login
                                    <i class="material-icons right">send</i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
            </div>
        </div>
    </main>
    <?php include('include/after_body.html'); ?>
</body>
</html>