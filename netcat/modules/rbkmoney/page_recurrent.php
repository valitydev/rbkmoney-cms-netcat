<div>
    <table class="nc-table nc--striped nc--wide">
        <tr>
            <th><?php echo USER_FIELD; ?></th>
            <th><?php echo AMOUNT_FIELD; ?></th>
            <th><?php echo PRODUCT_FIELD; ?></th>
            <th><?php echo USER_STATUS; ?></th>
            <th><?php echo RECURRENT_CREATE_DATE; ?></th>
            <th></th>
        </tr>
        <?php
        foreach ($this->recurrent as $recurrentId => $payment) {
            echo "<tr class='nc-infoblock-object'>
                      <td><a href='{$payment['user']}'>{$payment['user_name']}</a></td>
                      <td>{$payment['amount']}</td>
                      <td>{$payment['name']}</td>
                      <td>{$payment['status']}</td>
                      <td>{$payment['date']}</td>
                      <td>
                          <form action='admin.php'>
                              <input type='hidden' name='view' value='recurrent'>
                              <input type='hidden' name='act' value='recurrentDelete'>
                              <input type='hidden' name='recurrentId' value='$recurrentId'>
                              <button type='submit' style='height: 30px'>" . FORM_BUTTON_DELETE . "</button>
                          </form>
                      </td>
                  </tr>";
        }
        ?>
    </table>
</div>