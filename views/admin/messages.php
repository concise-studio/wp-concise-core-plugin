<?php if (!empty($messages['positive'])) { ?>
    <div id="message" class="updated">
        <?php foreach ($messages['positive'] as $positiveMessage) { ?>
            <p><?= $positiveMessage ?></p>
        <?php } ?>
    </div>
<?php } ?>

<?php if (!empty($messages['negative'])) { ?>
    <div id="message" class="error">
        <?php foreach ($messages['negative'] as $negativeMessage) { ?>
            <p><?= $negativeMessage ?></p>
        <?php } ?>
    </div>
<?php } ?>
