<?php
include_once("lib/connection.php"); 


$sql_stud = $mysqli->query("SELECT * FROM student WHERE p_id='1'");
$sql_proj = $mysqli->query("SELECT * FROM project WHERE id='1'");
$sql_srs = $mysqli->query("SELECT * FROM srsdoc WHERE p_id='1'");
$sql_func = $mysqli->query("SELECT * FROM funcreq WHERE docID='1'");
$sql_nonfunc = $mysqli->query("SELECT * FROM nonfuncreq WHERE docID='1'");

// Include the PHPWord.php, all other classes were loaded by an autoloader
require_once 'PHPWord.php';

// Create a new PHPWord Object
$PHPWord = new PHPWord();

// Every element you want to append to the word document is placed in a section. So you need a section:
$section = $PHPWord->createSection();

$section->addTextBreak(6);

//Cover Page
// You can directly style your text by giving the addText function an array:
$styleFont = array('bold'=>true, 'size'=>40, 'name'=>'Times New Roman');
$styleParagraph = array('align'=>'left', 'spaceAfter'=>100);
$data = $sql_proj->fetch_array();
$section->addText($data['name'], $styleFont, $styleParagraph);
$section->addTextBreak(2);
$styleFont = array('bold'=>false, 'size'=>32, 'name'=>'Times New Roman');
$section->addText('Software Requirements Specification', $styleFont, $styleParagraph);
$section->addTextBreak(9);
$data = $sql_stud->fetch_array();
$styleFont = array('bold'=>false, 'size'=>20, 'name'=>'Times New Roman');
$section->addText($data['name']."  ".$data['reg_no'], $styleFont, $styleParagraph);

$section->addPageBreak();

//New page
//Styling header and content
$styleHeadingFont = array('bold'=>true, 'size'=>24, 'name'=>'Times New Roman');
$styleHeadingParagraph = array('align'=>'left', 'spaceAfter'=>100);
$styleContentFont = array('bold'=>false, 'size'=>16, 'name'=>'Times New Roman');
$styleContentParagraph = array('align'=>'both', 'spaceAfter'=>100);

$data = $sql_srs->fetch_array();
$section->addText('Purpose', $styleHeadingFont, $styleHeadingParagraph);
$section->addTextBreak();
$section->addText($data['purpose'], $styleContentFont, $styleContentParagraph);
$section->addTextBreak(6);
$section->addText('Scope', $styleHeadingFont, $styleHeadingParagraph);
$section->addTextBreak();
$section->addText($data['scope'], $styleContentFont, $styleContentParagraph);
$section->addTextBreak(6);
$section->addText('Assumptions and Dependencies', $styleHeadingFont, $styleHeadingParagraph);
$section->addTextBreak();
$section->addText($data['assumptions'], $styleContentFont, $styleContentParagraph);
$section->addTextBreak(6);
$section->addText('References', $styleHeadingFont, $styleHeadingParagraph);
$section->addTextBreak();
$section->addText($data['references'], $styleContentFont, $styleContentParagraph);
$section->addPageBreak();

//New page
$styleSubFont = array('bold'=>false, 'size'=>20, 'name'=>'Times New Roman', 'underline'=>PHPWord_Style_Font::UNDERLINE_SINGLE);
$styleSubParagraph = array('align'=>'both', 'spaceAfter'=>100);

$section->addText('Functional Requirements', $styleHeadingFont, $styleHeadingParagraph);
$section->addTextBreak(3);

while($data = $sql_func->fetch_array())
{
    $section->addText($data['type'], $styleSubFont, $styleSubParagraph);
    $section->addTextBreak();
    $section->addText($data['desc'], $styleContentFont, $styleContentParagraph);
    $section->addTextBreak(2);
}
$section->addPageBreak();

//New page
$section->addText('Non-Functional Requirements', $styleHeadingFont, $styleHeadingParagraph);
$section->addTextBreak(3);

while($data = $sql_nonfunc->fetch_array())
{
    $section->addText($data['type'], $styleSubFont, $styleSubParagraph);
    $section->addTextBreak();
    $section->addText($data['desc'], $styleContentFont, $styleContentParagraph);
    $section->addTextBreak(2);
}

// At least write the document to webspace:
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$file = "SRS_".rand(1111, 9999).".docx";
$objWriter->save($file);





if(isset($_REQUEST['users']))
{
  foreach($_REQUEST['users'] as $u_id)
  {
    $q = $mysqli->query("SELECT * FROM users WHERE id='$u_id'");
    $data= $q->fetch_array();
    $my_file = $file;
    $my_mail = "reetikaroy21@gmail.com";
    $my_subject = "IWP Assignment";
    $my_message = "SRS Documentation created by extracting data from a database into a Word doc is attached here.";
    mail_attachment($my_file, $data['email'], $my_mail, $my_subject, $my_message);
  }
    
}


function mail_attachment($file, $mailto, $from_mail, $subject, $message) {
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_mail.">\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$file."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$file."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
    if (mail($mailto, $subject, $msg, $header)) {
        header("Location:index.php?msg=Mails sent"); // or use booleans here
    } else {
        header("Location:index.php?msg=Mails not sent");
    }
}
?>