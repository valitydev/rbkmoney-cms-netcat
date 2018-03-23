<form method="post" action="../../require/e404.php" id="MainSettigsForm" style="padding:0; margin:0;">
    <input type="hidden" name="view" value="settings"/>
    <input type="hidden" name="act" value="save"/>
    <div>
        <table class="nc_t">
            <tr><?php echo API_KEY ?></tr>
            <td><textarea name="' . $key . '" size="50">' . $field['value'] . '</textarea></td>
        </table>
    </div>
</form>
<?php $UI_CONFIG->actionButtons[] = array(
    "id" => "submit",
    "caption" => 'Сохранить',
    "action" => "mainView.submitIframeForm('MainSettingsForm')"
);
?>