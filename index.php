<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <h2>Mirawatt API data formats</h2>

        <?php
        echo "Current time : " . date("c");
        ?>
        <table>
            <tr>
                <th>JSON</th>
                <th>XML</th>
            </tr>
            <tr valign="top">
                <td>
                    <pre><?php echo file_get_contents("data.json"); ?></pre>
                </td>
                <td>
                    <pre><?php echo htmlspecialchars(file_get_contents("data.xml")) ?></pre>
                </td>
            </tr>
        </table>
    </body>
</html>
