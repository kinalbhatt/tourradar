<?php

class Tours
{
    public $xml;

    public function xmlToCSV()
    {
        $tours = new SimpleXMLElement($this->xml);

        $csvFields = array(
            "Title",
            "Code",
            "Duration",
            "Inclusions",
            "MinPrice"
        );
        $fp = fopen("php://output", 'w+');
        fputcsv($fp, $csvFields);
        foreach ($tours as $tour) {
            $csvFields = array();
            $csvFields["Title"] = html_entity_decode($tour->Title);
            $csvFields["Code"] = html_entity_decode($tour->Code);
            $csvFields["Duration"] = html_entity_decode($tour->Duration);
            $csvFields["Inclusions"] = trim(
                html_entity_decode(
                    strip_tags(
                        str_replace("&nbsp;", " ", $tour->Inclusions)
                    )
                )
            );

            foreach ($tour->DEP as $depart) {
                $price = floatval($depart->attributes()->EUR);
                if (isset($depart->attributes()->DISCOUNT)) {
                    $price = floatval($price - ($price*(floatval($depart->attributes()->DISCOUNT)/100)));
                }

                if (!isset($csvFields["MinPrice"]) || $csvFields["MinPrice"] > $price) {
                    $csvFields["MinPrice"] = number_format($price, 2, ".", "");
                }
            }
            fputcsv($fp, $csvFields);
        }
        fclose($fp);
    }
}
header('Content-Type: application/excel; charset=utf-8');
header('Content-Disposition: attachment; filename="sample.csv"');

$tours =  new Tours();
if (!file_exists("tours.xml")) {
    die("tours.xml file not exist. Please add that before continuing script.");
}
$tours->xml = file_get_contents("tours.xml");
$tours->xmlToCSV();
