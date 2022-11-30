<?php
session_start();
$nav_selected = "LIST";
$left_buttons = "NO";
$left_selected = ""; 
include("./nav.php");

$puzzles = $_SESSION['puzzles'];
$size = $_SESSION['size'];
$words = $_SESSION["words"];
$images = $_SESSION["images"];

if($_SESSION["hasImages"]){
	$total_images = count($images);
}
else{
	$total_images = 0;
}
$total_puzzles = count($puzzles);

if ($size == "2x2") {

      $cols = 4;
      $boxSize = 2;
      $margin = 10;
      $width = 500;
      $fontSize = 18;

} elseif ($size == "3x3") {
      $cols = 9;
      $boxSize = 3;
      $margin = 10;
      $width = 500;
      $fontSize = 18;
} elseif ($size == "4x4") {

      $cols = 16;
      $boxSize = 4;
      $margin = 10;
      $width = 500;
      $fontSize = 14;
} else {
      die("We cannot handle this size: " . $size);
}



//die($_SESSION['size']);

$margin = 10;
require_once 'PhpPresentation/src/PhpPresentation/Autoloader.php';
\PhpOffice\PhpPresentation\Autoloader::register();
require_once 'PhpOffice/src/Common/Autoloader.php';
\PhpOffice\Common\Autoloader::register();


use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use \PhpOffice\PhpPresentation\Style\Fill;
use \PhpOffice\PhpPresentation\Style\Border;
use PhpOffice\PhpPresentation\Style\Alignment;

$objPHPPowerPoint = new PhpPresentation();

use PhpOffice\PhpPresentation\AbstractShape;
use PhpOffice\PhpPresentation\Autoloader;
use PhpOffice\PhpPresentation\DocumentLayout;
//use PhpOffice\PhpPresentation\IOFactory;
//use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Shape\Drawing;
use PhpOffice\PhpPresentation\Shape\Group;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Shape\RichText\BreakElement;
use PhpOffice\PhpPresentation\Shape\RichText\TextElement;
use PhpOffice\PhpPresentation\Slide;
//use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Bullet;
//use PhpOffice\PhpPresentation\Style\Color;


// Set Documenet meta adata
$objPHPPowerPoint->getDocumentProperties()->setCreator('PHPOffice')
      ->setLastModifiedBy('PHPPresentation Team')
      ->setTitle($size . ' - Elephant Wordoku')
      ->setSubject($size . ' - Elephant Wordoku')
      ->setDescription($size . ' - Elephant Wordoku')
      ->setKeywords('office 2007 wordoku sudoku  php ' . $size)
      ->setCategory('game');




// Remove first Blank Slide
$objPHPPowerPoint->removeSlideByIndex(0);

for ($iz = 0; $iz < count($puzzles); $iz++) {
	
	  $puzzle = $puzzles[$iz];
      // // Create slide
      //$currentSlide = $objPHPPowerPoint->getActiveSlide();
      $currentSlide = $objPHPPowerPoint->createSlide();




      // Create a shape (drawing)
      $shape = $currentSlide->createDrawingShape();
      $shape->setName('PHPPresentation logo')
            ->setDescription('PHPPresentation logo')
            ->setPath('app_header.png')
            ->setHeight(72)
            ->setOffsetX(10)
            ->setOffsetY(5);
			
			
			
	  $shape = $currentSlide->createRichTextShape()
            ->setWidth(800)
            ->setOffsetX(50)
            ->setOffsetY(5);
      $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      //$textRun = $shape->createTextRun('Puzzle '.$iz+1);
      //$textRun->getFont()->setSize(18);

      // Create a shape (text)
      $shape = $currentSlide->createRichTextShape()
            ->setWidth(800)
            ->setOffsetX(50)
            ->setOffsetY(25);
      $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      $textRun = $shape->createTextRun($words[$iz]);
      $textRun->getFont()->setSize(18);
      //->setColor( new Color( 'FFE06B20' ) );


      // Create a shape (text)
      $shape = $currentSlide->createRichTextShape()
            ->setWidth(75)
            ->setHeight(50)
            ->setOffsetX(850)
            ->setOffsetY(5);
      $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      $textRun = $shape->createTextRun('P-'.($iz + 1));
      $textRun->getFont()->setSize(16);
      //->setColor( new Color( 'FFFFFF' ) );
      $shape->getFill()->setFillType(Fill::FILL_GRADIENT_LINEAR)
            ->setRotation(90)
            ->setStartColor(new Color('FFE06B20'))
            ->setEndColor(new Color('0000FF99'));


      if ($total_images > 0) {

            $image_index = $iz;
            if ($total_puzzles > $total_images) {
                  $image_index  = $iz % $total_images;
            }
            if (file_exists($images[$image_index])) {

                  // Create a shape (drawing)
                  $shape2 = $currentSlide->createDrawingShape();
                  $shape2->setName('Puzzle image ' . $iz)
                        ->setDescription('Image image' . $iz)
                        ->setResizeProportional(true)
                        ->setPath($images[$image_index])
                        ->setWidth(($width * 0.75))
                        //->setHeight($width)
                        ->setOffsetX(30)
                        ->setOffsetY(100);
            }
      
      /// Set the image in middle
	  
      if ($shape2->getHeight() <  $width) {

            $offset = ($width - $shape2->getHeight()) / 2 +  $shape2->getOffsetY();
            $shape2->setOffsetY($offset);
      } elseif ($shape2->getHeight() >  $width) {

            $shape2->setHeight($width);
      }
	  }

      $shape = $currentSlide->createTableShape($cols);
      $shape->setHeight($width);
      $shape->setWidth($width);
      $shape->setOffsetX(($width * 0.75) + 45);
      $shape->setOffsetY(100);

      $row_i = 0;
      foreach ($puzzle as $key => $value) {


            $row = $shape->createRow();
            //       $row->getFill()->setFillType(Fill::FILL_SOLID)
            //     ->setStartColor(new Color('FFE06B20'))
            //     ->setEndColor(new Color('FFE06B20'));

            $row->setHeight($width / $cols);

            $col_i = 0;
            foreach ($value as $k => $val) {

                  $cell = $row->nextCell();
                  $cell->createTextRun($val)->getFont()->setSize($fontSize);;
                  $cell->setWidth($width / $cols);
                  if ($val != " ") {
                        $cell->getFill()->setFillType(Fill::FILL_GRADIENT_LINEAR)
                              ->setRotation(90)
                              ->setStartColor(new Color('ffedebeb'))
                              ->setEndColor(new Color('ffedebeb'));
                  }
                  $cell->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

                  // $cell->getActiveParagraph()->getAlignment()
                  //       ->setMarginBottom($margin)
                  //       ->setMarginLeft($margin)
                  //       ->setMarginRight($margin)
                  //       ->setMarginTop($margin);


                  if ($row_i % $boxSize == 0) {
                        //boder-top
                        $cell->getBorders()->getTop()->setLineWidth(3)
                              ->setLineStyle(Border::LINE_SINGLE);
                  }
                  if ($col_i % $boxSize == 0) {
                        //boder-left
                        $cell->getBorders()->getLeft()->setLineWidth(3)
                              ->setLineStyle(Border::LINE_SINGLE);
                  }
                  if (($row_i + 1) % $boxSize == 0) {
                        //boder-bottom
                        $cell->getBorders()->getBottom()->setLineWidth(3)
                              ->setLineStyle(Border::LINE_SINGLE);
                  }
                  if (($col_i + 1) % $boxSize == 0) {
                        //boder-right
                        $cell->getBorders()->getRight()->setLineWidth(3)
                              ->setLineStyle(Border::LINE_SINGLE);
                  }

                  $col_i++;
            }
            $row_i++;
      }
}
	  
	  
	  
	  
	  
	  
	  
	  for ($iz = 0; $iz < count($puzzles); $iz++) {
	
	  $solution = $_SESSION['solutions'][$iz];
      // // Create slide
      //$currentSlide = $objPHPPowerPoint->getActiveSlide();
      $currentSlide = $objPHPPowerPoint->createSlide();




      // Create a shape (drawing)
      $shape = $currentSlide->createDrawingShape();
      $shape->setName('PHPPresentation logo')
            ->setDescription('PHPPresentation logo')
            ->setPath('app_header.png')
            ->setHeight(72)
            ->setOffsetX(0)
            ->setOffsetY(5);

      // Create a shape (text)
      $shape = $currentSlide->createRichTextShape()
            ->setWidth(800)
            ->setOffsetX(50)
            ->setOffsetY(50);
      $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      $textRun = $shape->createTextRun($words[$iz]);
      $textRun->getFont()->setSize(18);
      //->setColor( new Color( 'FFE06B20' ) );
	  
	  
	  $shape = $currentSlide->createRichTextShape()
            ->setWidth(800)
            ->setOffsetX(50)
            ->setOffsetY(5);
      $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      //$textRun = $shape->createTextRun('Solution '.$iz+1);
      //$textRun->getFont()->setSize(18);


      // Create a shape (text)
      $shape = $currentSlide->createRichTextShape()
            ->setWidth(75)
            ->setHeight(50)
            ->setOffsetX(850)
            ->setOffsetY(5);
      $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      $textRun = $shape->createTextRun('S-'.($iz + 1));
      $textRun->getFont()->setSize(16);
      //->setColor( new Color( 'FFFFFF' ) );
      $shape->getFill()->setFillType(Fill::FILL_GRADIENT_LINEAR)
            ->setRotation(90)
            ->setStartColor(new Color('FFE06B20'))
            ->setEndColor(new Color('0000FF99'));
	

      if ($total_images > 0) {

            $image_index = $iz;
            if ($total_puzzles > $total_images) {
                  $image_index  = $iz % $total_images;
            }
            if (file_exists($images[$image_index])) {

                  // Create a shape (drawing)
                  $shape2 = $currentSlide->createDrawingShape();
                  $shape2->setName('Puzzle image ' . $iz)
                        ->setDescription('Image image' . $iz)
                        ->setResizeProportional(true)
                        ->setPath($images[$image_index])
                        ->setWidth(($width * 0.75))
                        //->setHeight($width)
                        ->setOffsetX(30)
                        ->setOffsetY(100);
            }
      
      /// Set the image in middle
      if ($shape2->getHeight() <  $width) {

            $offset = ($width - $shape2->getHeight()) / 2 +  $shape2->getOffsetY();
            $shape2->setOffsetY($offset);
      } elseif ($shape2->getHeight() >  $width) {

            $shape2->setHeight($width);
      }
	  }


      $shape = $currentSlide->createTableShape($cols);
      $shape->setHeight($width);
      $shape->setWidth($width);
      $shape->setOffsetX(($width * 0.75) + 45);
      $shape->setOffsetY(100);

      $row_i = 0;
      foreach ($solution as $key => $value) {


            $row = $shape->createRow();
            //       $row->getFill()->setFillType(Fill::FILL_SOLID)
            //     ->setStartColor(new Color('FFE06B20'))
            //     ->setEndColor(new Color('FFE06B20'));

            $row->setHeight($width / $cols);

            $col_i = 0;
            foreach ($value as $k => $val) {

                  $cell = $row->nextCell();
                  $cell->createTextRun($val)->getFont()->setSize($fontSize);;
                  $cell->setWidth($width / $cols);
                  if ($val != " ") {
                        $cell->getFill()->setFillType(Fill::FILL_GRADIENT_LINEAR)
                              ->setRotation(90)
                              ->setStartColor(new Color('ffedebeb'))
                              ->setEndColor(new Color('ffedebeb'));
                  }
                  $cell->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

                  // $cell->getActiveParagraph()->getAlignment()
                  //       ->setMarginBottom($margin)
                  //       ->setMarginLeft($margin)
                  //       ->setMarginRight($margin)
                  //       ->setMarginTop($margin);


                  if ($row_i % $boxSize == 0) {
                        //boder-top
                        $cell->getBorders()->getTop()->setLineWidth(3)
                              ->setLineStyle(Border::LINE_SINGLE);
                  }
                  if ($col_i % $boxSize == 0) {
                        //boder-left
                        $cell->getBorders()->getLeft()->setLineWidth(3)
                              ->setLineStyle(Border::LINE_SINGLE);
                  }
                  if (($row_i + 1) % $boxSize == 0) {
                        //boder-bottom
                        $cell->getBorders()->getBottom()->setLineWidth(3)
                              ->setLineStyle(Border::LINE_SINGLE);
                  }
                  if (($col_i + 1) % $boxSize == 0) {
                        //boder-right
                        $cell->getBorders()->getRight()->setLineWidth(3)
                              ->setLineStyle(Border::LINE_SINGLE);
                  }

                  $col_i++;
            }
            $row_i++;
      }
	}


$filename = 'uploads/' . $size . '_wordoku.pptx';

$oWriterPPTX = IOFactory::createWriter($objPHPPowerPoint, 'PowerPoint2007');
$oWriterPPTX->save($filename);
//$oWriterPPTX->save(__DIR__ . "/" . $filename);
?>
</head>
<body>
<h1 style="text-align:center; margin-top:50px"> Wordoku has been saved </h1><p style="text-align:center; margin-top:20px">Click the link below to download now</p>';
<p style="text-align:center; margin-top:20px; font-size:20px"><a href="<?php echo $filename; ?>">Download now</a></p>';
</body>
<?php exit(); ?>
