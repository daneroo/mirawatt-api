<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <h2>Mirawatt API</h2>
        <?php
        $json = json_decode(file_get_contents("data-std.json"), true);
        ?>
        <table>
            <tr>
                <th>JSON</th>
                <th>JSON-std</th>
            </tr>
            <tr valign="top">
                <td>
                    <pre><?php
        /*
          { "feeds":[
          { "scopeId":"0", "name":"Live",
          "stamp":"2011-05-06T05:12:29Z", "value":"930",
          "observations":[
          {"2011-05-06T05:12:29Z":"930"},
          {"2011-05-06T05:12:28Z":"930"},
          {"2011-05-06T05:12:27Z":"940"},
         */
        $prettyFeeds = '{ "feeds":[' . PHP_EOL;
        $pa = array();
        $L=" ";
        $L2=$L.$L;
        $L3=$L.$L.$L;
        foreach ($json["feeds"] as $feed) {
            $prettyFeed = $L.'{"scopeId":"' . $feed["scopeId"] . '", "name":"' . $feed["name"] . '",' . PHP_EOL;
            $prettyFeed.=$L2.'"t":"' . $feed["stamp"] . '", "v":' . $feed["value"] . ',' . PHP_EOL;
            $prettyFeed.=$L2.'"observations":[' . PHP_EOL;
            $oa = array();
            foreach ($feed["observations"] as $o) {
                $po = $L3.'{"t":"' . $o["t"] . '","v":' . $o["v"] . '}';
                array_push($oa, $po);
            }
            $prettyFeed.=implode(",".PHP_EOL, $oa);
            $prettyFeed.=PHP_EOL . $L2.']}';
            array_push($pa, $prettyFeed);
        }
        $prettyFeeds.=implode(",", $pa);;
        $prettyFeeds.=PHP_EOL .']}' . PHP_EOL;
        echo $prettyFeeds;
        ?></pre>
                </td>
                <td>
                    <pre><?php echo (file_get_contents("data-std.json")) ?></pre>
                </td>
            </tr>
        </table>
    </body>
</html>
