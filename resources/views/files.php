<?php ob_start(); ?>
    <h4>Your files</h4>
<?php if(isset($flash_error)):?>
    <p class="red darken-2"><?= $flash_error ?></p>
<?php endif; ?>
    <div>
        <?php if(count($files) === 0): ?>
            <p class="red darken-2">You have no files.</p>
        <?php else: ?>
            <table class="white-text responsive-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($files as $file):?>
                        <tr>
                            <td><a href="/<?= $file->id ?>"><?= $file->id ?></a></td>
                            <td><?= $file->name ?></td>
                            <td id="time"><?= $file->date ?></td>
                        </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
<?php
include(__DIR__ . '/base.php');
