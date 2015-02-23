<?php


$uPtype = strip_tags($_GET['ut']);
//echo "type=";echo $uPtype;echo "<br><br>";
$uPdelivery = strip_tags($_GET['ud']);
//echo "type=";echo $uPdelivery;echo "<br><br>";
$uPscaler = strip_tags($_GET['uscr']);
//echo "type=";echo $uPscaler;echo "<br><br>";
$uPscalec = strip_tags($_GET['uscc']);
//echo "type=";echo $uPscalec;echo "<br><br>";
$uPmyfn = strip_tags($_GET['ufn']);
//echo "my_filename=";echo $uPmyfn;echo "<br><br>";
$uPc = strip_tags($_GET['umc']);
//echo "c=";echo $uPc;echo "<br><br>";
$uPr = strip_tags($_GET['umr']);
//echo "r=";echo $uPr;echo "<br><br>";
$uPdata = strip_tags($_GET['uPd']);
//echo "data=";echo $uPdata;echo "<br><br>";

/* Fix the filename appropriately */
$uPmyfn = $uPmyfn.$uPc."x".$uPr."_sxy".$uPscalec."x".$uPscaler;
//echo "my_filename=";echo $uPmyfn;echo "<br><br>";

$uPscaler = 0 + $uPscaler;
$uPscalec = 0 + $uPscalec;
$uPtype = 0 + $uPtype;
$uPc = 0 + $uPc;
$uPr = 0 + $uPr;




//echo "c=[".$uPc."]<br>";echo "r=[".$uPr."]<br>";

$uPcellRGBarr = explode(";",$uPdata);
/*
for($i=0;$i<sizeof($uPcellRGBarr);$i++){
echo "[".$i."] = [".$uPcellRGBarr[$i]."]<br>";
}
*/
//echo "<br><br>HERE WE GO:<br><br>";

$iPPm = imagecreatetruecolor(($uPc * $uPscalec),($uPr * $uPscaler)); // IMAGE CREATION PROCESS: First --> Create the image resource to work with
switch($uPtype){
case 1: // "1" for jpg
$iPPmBGcolor = imagecolorallocate($iPPm, 255,255,255); //                IMAGE CREATION PROCESS: Second-A --> __MUST__ be called BEFORE all other color allocations to set the background color :-|
break;
case 2: // ALSO "2" for alpha and png

        /*  THESE TWO LINE ARE _REQUIRED_ TO HAVE ALPHA WORK!! */
                imagealphablending($iPPm, false);
                imagesavealpha($iPPm, true);

break;
}

//imagefilledrectangle($iPPm, 0, 0, ($uPc * $uPscalec) - 1, ($uPr * $uPscaler) - 1, $iPPmBGcolor);  // IMAGE CREATION PROCESS: Second-B --> __MUST__ be called BEFORE all other color allocations to set the background color :-|
$i = 0;
for($ir=0;$ir<$uPr;$ir++){
for($ic=0;$ic<$uPc;$ic++){
$uPcellRGBarrRGB2 = explode("@",$uPcellRGBarr[$i]);
$uPcellRGBarrRGB2R = 0 + $uPcellRGBarrRGB2[0];
$uPcellRGBarrRGB2G = 0 + $uPcellRGBarrRGB2[1];
$uPcellRGBarrRGB2B = 0 + $uPcellRGBarrRGB2[2];


switch($uPtype){
case "1": // "1" for jpg
$iPPmPaletteColor = imagecolorallocate($iPPm,$uPcellRGBarrRGB2R,$uPcellRGBarrRGB2G,$uPcellRGBarrRGB2B); //  IMAGE CREATION PROCESS: Second --> Allocate a color to the image resource's "color palette"
break;
case "2": // ALSO "2" for alpha and png
$uPcellRGBarrRGB2A = 0 + $uPcellRGBarrRGB2[3];
//echo $uPcellRGBarrRGB2A;echo "<br>";
$iPPmPaletteColor = imagecolorallocatealpha($iPPm,$uPcellRGBarrRGB2R,$uPcellRGBarrRGB2G,$uPcellRGBarrRGB2B, $uPcellRGBarrRGB2A); //  IMAGE CREATION PROCESS: Second --> Allocate a color to the image resource's "color palette"
break;
}

/* This section will do the actual pixel drawing for EACH Edit Box Cell!! */
/*  ^-- So use a switch case and another variable to specify various effects to use such as drawing with a border or a rounded border or drawing a gradient or etc etc */

for($iscr=($ir * $uPscaler);$iscr<(($ir * $uPscaler)+$uPscaler);$iscr++){
for($iscc=($ic * $uPscalec);$iscc<(($ic * $uPscalec)+$uPscalec);$iscc++){
$works = imagesetpixel($iPPm,$iscc,$iscr,$iPPmPaletteColor); // IMAGE CREATION PROCESS: Third --> set the desired pixel to the desired Allocated color from the image resources "color palette"
//echo "works=[".$works."]<br>";
}
}
$i++;
}
}





/*
  *
  *
  *  NOTE: If you specify a filename for the imagejpeg() or imagepng() then the file will be created where this php script is located -BUT- no image will be downloadee to the user and you get an error!
  *
  *  NOTE: I don't 'currently' know a way to both name the image AND have it output to the browser so that may be a security thing from the php devs.
  *
  *
*/







switch($uPtype){
case 1: // "1" for jpg

   $uPmyfn = $uPmyfn.".jpg";
   
   switch($uPdelivery){
   case "b": // "b" for deliver to browser
      header('Content-type: image/jpeg'); 
   break;
   case "sa": // "sa" for deliver via browser save as download box
      header('Content-Disposition: Attachment;filename='.$uPmyfn); // <---- This allows us to just send the file down to the user "save-as mode" :-D
   break;
   }
   //imagejpeg($iPPm,$uPmyfn,100); // IMAGE CREATION PROCESS: Fourth --> Create a JPG file form the image resource with 100% highest quality setting :-)
   imagejpeg($iPPm,null,100); // IMAGE CREATION PROCESS: Fourth --> Create a JPG file form the image resource with 100% highest quality setting :-)
   
   
break;
case 2: // "2" for alpha and png

   $uPmyfn = $uPmyfn.".png";

   switch($uPdelivery){
   case "b": // "b" for deliver to browser
      header('Content-type: image/png'); 
   break;
   case "sa": // "sa" for deliver via browser save as download box
      header('Content-Disposition: Attachment;filename='.$uPmyfn); // <---- This allows us to just send the file down to the user "save-as mode" :-D
   break;
   }
   //imagepng($iPPm,$uPmyfn); // IMAGE CREATION PROCESS: Fourth --> Create a PNG file form the image resource
   imagepng($iPPm); // IMAGE CREATION PROCESS: Fourth --> Create a PNG file form the image resource
   
   
break;
}






imagedestroy($iPPm); // IMAGE CREATION PROCESS: FINAL --> Destroy the image resource to free memory ( ie: the clean up )









?>
