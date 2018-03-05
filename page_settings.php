<?php
/**
 * The output page template settings in the admin.
 */

new RbkAdmin();
?>
    <form method="post" action="admin.php" id="MainSettigsForm" style="padding:0; margin:0;">
        <input type="hidden" name="view" value="settings"/>
        <input type="hidden" name="act" value="save"/>
        <div>
            <?php
            foreach ($this->form as $key => $field) {
                echo '<div style="margin:10px 0;padding:0;">
                  <label style="width: 400px;display: block;float: left;margin-right: 10px;" title="' . $field['placeholder'] . '">' . $field['label'] . '</label>';
                switch ($field['type']) {
                    case 'input':
                        echo '<input type="text" value="' . $field['value'] . '" name="' . $key . '" size="50">';
                        break;
                    case 'select':
                        echo '<select name="' . $key . '">';
                        foreach ($field['options'] as $optionValue) {
                            echo '<option value="' . $optionValue . '"';
                            if ($field['value'] == $optionValue) {
                                echo ' selected ';
                            }
                            echo '>' . $optionValue . '</option>';
                        }
                        echo '</select>';
                        break;
                }
                echo '<div style="clear: both"></div>'
                    . '</div>';
            } ?>
        </div>
    </form>
<?php $UI_CONFIG->actionButtons[] = array(
    "id" => "submit",
    "caption" => 'Сохранить',
    "action" => "mainView.submitIframeForm('MainSettingsForm')"
);
?>