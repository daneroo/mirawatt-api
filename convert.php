<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <h2>Mirawatt API</h2>
        <?php
        $xml = simplexml_load_file("data.xml");
        $feeds = array();
        foreach ($xml->feed as $feed) {
            $nufeed = array();
            foreach ($feed->attributes() as $k => $v) {
                $nufeed[$k] = "" . $v;
            }
            $observations = array();
            foreach ($feed->observation as $obs) {
                $nuobs = array();
                foreach ($obs->attributes() as $k => $v) {
                    if ("stamp"==$k) $k="t";
                    if ("value"==$k) $k="v";
                    $nuobs[$k] = "" . $v;
                    //if ("stamp"==$k) $s="".$v;
                    //if ("value"==$k) $v="".$v;
                }
                //$nuobs = array($s => $v);
                array_push($observations, $nuobs);
            }
            $nufeed["observations"] = $observations;
            array_push($feeds, $nufeed); //["@attributes"]
        }
        $feeds = array("feeds"=>$feeds);
        ?>
        <pre>
            <?php
            echo json_encode($feeds);
            ?>
        </pre>
        <pre>
            <?php
            echo json_encode($xml);
            ?>
        </pre>
        <pre>
            <?php
            print_r($xml)
            ?>
        </pre>
    </body>
</html>
