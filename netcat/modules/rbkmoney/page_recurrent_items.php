<form method="post" action="admin.php" id="recurrent" style="padding:0; margin:0;">
    <input type="hidden" name="view" value="recurrent_items"/>
    <input type="hidden" name="act" value="save"/>
    <div>
        <?php
        foreach ($this->recurrentItems as $key => $field) {
            echo '<div style="margin:10px 0;padding:0;">
                  <label style="width: 400px;display: block;float: left;margin-right: 10px;" title="' . $field['placeholder'] . '">' . $field['label'] . '</label>';
                    echo '<textarea " name="' . $key . '" size="50">' . $field['value'] . '</textarea>';
            echo '<div style="clear: both"></div>'
                . '</div>';
        } ?>
    </div>
</form>
<?php $UI_CONFIG->actionButtons[] = array(
    'id' => 'submit',
    'caption' => SAVE,
    'action' => "mainView.submitIframeForm('recurrent')"
);
?>