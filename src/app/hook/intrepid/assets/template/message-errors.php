<div class="alert error message message-error error-message">
    <ul class="error-list">
        <?php foreach ($message as $item): ?>
            <li class="error-item">
                <?php echo $item; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
