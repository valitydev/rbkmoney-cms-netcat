<div>
    <table class="nc-table nc--striped nc--wide">
        <tr>
            <th><?php echo USER; ?></th>
            <th><?php echo AMOUNT; ?></th>
            <th><?php echo PRODUCT; ?></th>
            <th><?php echo USER_STATUS; ?></th>
            <th></th>
        </tr>
        <?php
        foreach ($this->recurrent as $recurrentId => $payment) {
            echo "<tr class='nc-infoblock-object'>
                      <td><a href='{$payment['user']}'>{$payment['user_name']}</a></td>
                      <td>{$payment['amount']}</td>
                      <td>{$payment['name']}</td>
                      <td>{$payment['status']}</td>
                      <td>
                          <form action='admin.php'>
                              <input type='hidden' name='view' value='recurrent'>
                              <input type='hidden' name='act' value='recurrentDelete'>
                              <input type='hidden' name='recurrentId' value='$recurrentId'>
                              <button type='submit' style='height: 30px'>" . DELETE . "</button>
                          </form>
                      </td>
                  </tr>";
        }
        ?>
    </table>
</div>