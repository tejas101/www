<?php

if (isset($_POST['lsr-submit']))
    {
        //header('Location: localhost/indiaspend_xml/xml.php');
    }

$str = '<?xml version="1.0" encoding="UTF-8"?><indiaspend></indiaspend>';
$xml = simplexml_load_string($str);

$title = $_POST['title'];
$authorname = $_POST['authorname'];
$date = $_POST['date'];
$filename = $_POST['filename'];
$category = $_POST['category'];

$description1 = $_POST['desc1'];
$description2 = $_POST['desc2'];
$description3 = $_POST['desc3'];
$description4 = $_POST['desc4'];
$description5 = $_POST['desc5'];
$description6 = $_POST['desc6'];
$description7 = $_POST['desc7'];
$description8 = $_POST['desc8'];
$description9 = $_POST['desc9'];
$description10 = $_POST['desc10'];


$title = utf8_encode($title);
$authorname = utf8_encode($authorname);
$date = utf8_encode($date);
$category = utf8_encode($category);

$description01 = utf8_encode($description1);
$description02 = utf8_encode($description2);
$description03 = utf8_encode($description3);
$description04 = utf8_encode($description4);
$description05 = utf8_encode($description5);
$description06 = utf8_encode($description6);
$description07 = utf8_encode($description7);
$description08 = utf8_encode($description8);
$description09 = utf8_encode($description9);
$description010 = utf8_encode($description10);
$xml->article = "";
$xml->article->addChild('title', $title);
$xml->article->addChild('authorname', $authorname);
$xml->article->addChild('date', $date);
$xml->article->addChild('category', $category);

if($_POST['image1']!=''){
$image1 = "<img href='".$_POST['image1']."' />";
$image1 = utf8_encode($image1);
$xml->article->addChild('image', $image1);
$xml->article->addChild('width', $_POST['width1']);
$xml->article->addChild('height', $_POST['height1']);
}
if($_POST['desc1']!=''){
$desc1_new = $xml->article->addChild('content');
$desc1_new->value=$description01; 
}

if($_POST['image2']!=''){
$image2 = "<img href='".$_POST['image2']."' />";
$image2 = utf8_encode($image2);
$xml->article->addChild('image', $image2);
$xml->article->addChild('width', $_POST['width2']);
$xml->article->addChild('height', $_POST['height2']);
}

if($_POST['desc2']!=''){
$desc2_new = $xml->article->addChild('content');
$desc2_new->value=$description02; 
}

if($_POST['image3']!=''){
$image3 = "<img href='".$_POST['image3']."' />";
$image3 = utf8_encode($image3);
$xml->article->addChild('image', $image3);
$xml->article->addChild('width', $_POST['width3']);
$xml->article->addChild('height', $_POST['height3']);
}

if($_POST['desc3']!=''){
$desc3_new = $xml->article->addChild('content');
$desc3_new->value=$description03; 
}

if($_POST['image4']!=''){
$image4 = "<img href='".$_POST['image4']."' />";
$image4 = utf8_encode($image4);
$xml->article->addChild('image', $image4);
$xml->article->addChild('width', $_POST['width4']);
$xml->article->addChild('height', $_POST['height4']);
}

if($_POST['desc4']!=''){
$desc4_new = $xml->article->addChild('content');
$desc4_new->value=$description04; 
}

if($_POST['image5']!=''){
$image5 = "<img href='".$_POST['image5']."' />";
$image5 = utf8_encode($image5);
$xml->article->addChild('image', $image5);
$xml->article->addChild('width', $_POST['width5']);
$xml->article->addChild('height', $_POST['height5']);
}

if($_POST['desc5']!=''){
$desc5_new = $xml->article->addChild('content');
$desc5_new->value=$description05; 
}

if($_POST['image6']!=''){
$image6 = "<img href='".$_POST['image6']."' />";
$image6 = utf8_encode($image6);
$xml->article->addChild('image', $image6);
$xml->article->addChild('width', $_POST['width6']);
$xml->article->addChild('height', $_POST['height6']);
}

if($_POST['desc6']!=''){
$desc6_new = $xml->article->addChild('content');
$desc6_new->value=$description06; 
}

if($_POST['image7']!=''){
$image7 = "<img href='".$_POST['image7']."' />";
$image7 = utf8_encode($image7);
$xml->article->addChild('image', $image7);
$xml->article->addChild('width', $_POST['width7']);
$xml->article->addChild('height', $_POST['height7']);
}

if($_POST['desc7']!=''){
$desc7_new = $xml->article->addChild('content');
$desc7_new->value=$description07; 
}

if($_POST['image8']!=''){
$image8 = "<img href='".$_POST['image8']."' />";
$image8 = utf8_encode($image8);
$xml->article->addChild('image', $image8);
$xml->article->addChild('width', $_POST['width8']);
$xml->article->addChild('height', $_POST['height8']);
}

if($_POST['desc8']!=''){
$desc8_new = $xml->article->addChild('content');
$desc8_new->value=$description08; 
}

if($_POST['image9']!=''){
$image9 = "<img href='".$_POST['image9']."' />";
$image9 = utf8_encode($image9);
$xml->article->addChild('image', $image9);
$xml->article->addChild('width', $_POST['width9']);
$xml->article->addChild('height', $_POST['height9']);
}

if($_POST['desc9']!=''){
$desc9_new = $xml->article->addChild('content');
$desc9_new->value=$description09; 
}

if($_POST['image10']!=''){
$image10 = "<img href='".$_POST['image10']."' />";
$image10 = utf8_encode($image10);
$xml->article->addChild('image', $image10);
$xml->article->addChild('width', $_POST['width10']);
$xml->article->addChild('height', $_POST['height10']);
}

if($_POST['desc10']!=''){
$desc10_new = $xml->article->addChild('content');
$desc10_new->value=$description010; 
}

$doc = new DOMDocument('1.0');
$doc->formatOutput = true;
$doc->preserveWhiteSpace = true;
$doc->loadXML($xml->asXML(), LIBXML_NOBLANKS);
$doc->save('xmlfile/'.$filename.'.xml');
echo 'Saved File : '.$filename.'.xml';
echo '<br>';
echo '<a href="http://indiaspend.com/xml/xmlfile/'.$filename.'.xml">Click here to view XML file : <strong>'.$filename.' .xml</strong></a>';

?>