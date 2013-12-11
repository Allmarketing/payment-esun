

   PowerGraphic
   version 1.0



 Author: Carlos Reche
 E-mail: carlosreche@yahoo.com
 Sorocaba, SP - Brazil

 Created: Sep 20, 2004
 Last Modification: Sep 20, 2004



  Authors' comments:

  PowerGraphic creates 6 different types of graphics with how many parameters you want. You can
  change the appearance of the graphics in 3 different skins, and you can still cross data from 2 
  graphics in only 1! It's a powerful script, and I recommend you read all the instructions 
  to learn how to use all of this features. Don't worry, it's very simple to use it.

  This script is free. Please keep the credits.








   INSTRUNCTIONS OF HOW TO USE THIS SCRIPT  (Please, take a minute to read it. It's important!)


   NOTE: make sure that your PHP is compiled to work with GD Lib.

   NOTE: You may create test images using a form that comes with this script. Just add a "showform" 
   as a query string. (Example: "graphic.php?showform")


   PowerGraphic works with query strings (information sent after the "?" of an URL). Here is an 
   example of how you will have to send the graphic information. Let's suppose that you want to 
   show a graphic of your user's sex:

       <img src="graphic.php?title=Sex&type=5&x1=male&y1=50&x2=female&y2=55" />

   This will create a pie graphic (set by type=5) with title as "Sex" and default skin. 
   Let's see the other parameters:

       x1 = male
       y1 = 50 (quantity of males)
       x2 = female
       y2 = 55 (quantity of females)

   See how it's simple! :)
   For those who don't know, to create a query string you have to put an "?" at the end of the URL and join
   the parameters with "&". Example: "graphic.php?Parameter_1=Value_1&Parameter_2=Value_2" (and so on). You 
   can set how many parameters you want.

   The boring step would be to create this query string. Well, "would be", if I didn't create a function to do that. :)
   Let's see an example of how you can use this function in a PHP document:


///// START OF EXAMPLE /////

<?php

require "class.graphics.php";
$PG = new PowerGraphic;

$PG->title = "Sex";
$PG->type  = "5";
$PG->x1    = "male";
$PG->y1    = "50";
$PG->x2    = "female";
$PG->y2    = "55";

echo '<img src="graphic.php?' . $PG->create_query_string() . '" />';


// If you're going to create more than 1 graphic, it'll be important to reset the values before 
// create the next query string:
$PG->reset_values();

?>

///// END OF EXAMPLE /////



   Here is a list of all parameters you may set:

   title      =>  Title of the graphic
   axis_x     =>  Name of values from Axis X
   axis_y     =>  Name of values from Axis Y
   graphic_1  =>  Name of Graphic_1 (only shown if you are gonna cross data from 2 different graphics)
   graphic_2  =>  Name of Graphic_2 (same comment from above)

   type  =>  Type of graphic (values 1 to 6)
                1 => Vertical bars (default)
                2 => Horizontal bars
                3 => Dots
                4 => Lines
                5 => Pie
                6 => Donut

   skin   => Skin of the graphic (values 1 to 3)
                1 => Office (default)
                2 => Matrix
                3 => Spring

   credits => Only if you want to show my credits in the image. :)
                0 => doesn't show (default)
                1 => shows

   x[0]  =>  Name of the first parameter in Axis X
   x[1]  =>  Name of the second parameter in Axis X
   ... (etc)

   y[0]  =>  Value from "graphic_1" relative for "x[0]"
   y[1]  =>  Value from "graphic_1" relative for "x[1]"
   ... (etc)

   z[0]  =>  Value from "graphic_2" relative for "x[0]"
   z[1]  =>  Value from "graphic_2" relative for "x[1]"
   ... (etc)


   NOTE: You can't cross data between graphics if you use "pie" or "donut" graphic. Values for "z"
   won't be considerated.



   That's all! Hope you make a good use of it!
   It would be nice to receive feedback from others users. All comments are welcome!

   Regards,

   Carlos Reche


