// Include the PHPWord.php, all other classes were loaded by an autoloader
require_once 'PHPWord.php';

// Create a new PHPWord Object
$PHPWord = new PHPWord();

// Every element you want to append to the word document is placed in a section. So you need a section:
$section = $PHPWord->createSection();

// After creating a section, you can append elements:
$section->addText('Hello world!');

<?php
function formatDoc()
{
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
$file = "SRS_".rand(1111, 9999)."docx";
if($objWriter->save($file))
	return $file;
}
?>