<div>
    <textarea rows="30" wrap="off" readonly=""><?php echo trim($logs)?></textarea><br>
    <table>
        <tr>
            <th>
                <form action='admin.php'>
                    <input type='hidden' name='view' value='logs'>
                    <input type='hidden' name='act' value='deleteLogs'>
                    <button type='submit'><?php echo DELETE_LOGS?></button>
                </form>
            </th>
            <th>
                <form action='admin.php'>
                    <input type='hidden' name='view' value='logs'>
                    <input type='hidden' name='act' value='downloadLogs'>
                    <button type='submit'><?php echo DOWNLOAD_LOGS?></button>
                </form>
            </th>
        </tr>
    </table>
</div>