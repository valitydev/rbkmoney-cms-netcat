<form method="post" action="admin.php" id="MainSettingsForm" style="padding:0; margin:0;">
    <input type="hidden" name="view" value="settings"/>
    <input type="hidden" name="act" value="save"/>
    <div>
        <table>
        <?php
        foreach ($this->form as $key => $field) {
            echo "<tr><td style='width: 40%'>{$field['label']}</td><td>";
            switch ($field['type']) {
                case 'textarea':
                    echo "<textarea name='$key' size='50'>{$field['value']}</textarea>";
                    break;
                case 'input':
                    echo "<input type='text' value='{$field['value']}' name='$key' size='50'>";
                    break;
                case 'checkbox':
                    $checked = $field['value'] == 1 ? ' checked' : '';
                    echo "<input type='checkbox' value='1' name='$key' $checked>";
                    break;
                case 'select':
                    echo "<select name='$key'>'";
                    foreach ($field['options'] as $optionValue) {
                        echo "<option value='$optionValue'";
                        if ($field['value'] == $optionValue) {
                            echo ' selected ';
                        }
                        echo ">$optionValue</option>";
                    }
                    echo '</select>';
                    break;
            }
            echo '</td></tr>';
        } ?>
        </table>
    </div>
</form>
<?php $UI_CONFIG->actionButtons[] = [
    'id' => 'submit',
    'caption' => SAVE,
    'action' => "mainView.submitIframeForm('MainSettingsForm')",
];
?>