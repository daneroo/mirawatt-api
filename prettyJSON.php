<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <h2>Mirawatt API</h2>
        <?php
        $json = json_decode(file_get_contents("data.json"), true);
        ?>
        <table>
            <tr>
                <th>JSON</th>
                <th>XML</th>
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
        foreach ($json["feeds"] as $feed) {
            $prettyFeed = '  { "scopeId":"' . $feed["scopeId"] . '", "name":"' . $feed["name"] . '",' . PHP_EOL;
            $prettyFeed.='    "stamp":"' . $feed["stamp"] . '", "value":"' . $feed["value"] . '",' . PHP_EOL;
            $prettyFeed.='    "observations":[' . PHP_EOL;
            $oa = array();
            foreach ($feed["observations"] as $o) {
                $po = '      {"t":"' . $o["t"] . '","v":' . $o["v"] . '}';
                array_push($oa, $po);
            }
            $prettyFeed.=implode(",".PHP_EOL, $oa);
            $prettyFeed.=PHP_EOL . '    ]' . PHP_EOL;
            $prettyFeed.='  }';
            array_push($pa, $prettyFeed);
        }
        $prettyFeeds.=implode(",".PHP_EOL, $pa);;
        $prettyFeeds.=PHP_EOL .']}' . PHP_EOL;
        echo $prettyFeeds;
        ?></pre>
                </td>
                <td>
                    <pre><?php echo file_get_contents("data.json") ?></pre>
                </td>
            </tr>
        </table>
    </body>
</html>
