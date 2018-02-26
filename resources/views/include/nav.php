<ul id="nav-mobile" class="right hide-on-med-and-down">
    <li><a href="/" class="tooltipped" data-position="bottom" data-tooltip="Home"><i class="material-icons">home</i></a></li>
    <li><a href="/upload" class="tooltipped" data-position="bottom" data-tooltip="Upload"><i class="material-icons">cloud_upload</i></a></li>
    <?php if(\Core\Auth::check()): ?>
    <li><a href="/settings" class="tooltipped" data-position="bottom" data-tooltip="Settings"><i class="material-icons">settings</i></a></li>
    <?php endif; ?>
</ul>