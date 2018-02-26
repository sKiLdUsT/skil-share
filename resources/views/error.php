<?php ob_start(); ?>
<h4><code><?= $code ?></code></h4>
<p>Sorry :c</p>
<?php if (isset($error) && (isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'] === 'true')): ?>
<code>
    <pre><?= $error['message'] ?: $error ?></pre>
</code>
<?php endif;
include('base.php');