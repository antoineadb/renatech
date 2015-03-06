<?php 
include_once 'outils/constantes.php';
?>
<script type="text/javascript" src="js/rgbcolor.js"></script> 
<script type="text/javascript" src="js/StackBlur.js"></script>
<script type="text/javascript" src="js/canvg.js"></script> 
<canvas id="canvas" width="1000px" height="600px"></canvas> 

<script type="text/javascript">
window.onload = function() {
  //load '../path/to/your.svg' in the canvas with id = 'canvas'
  canvg('canvas', '/');
  //load a svg snippet in the canvas with id = 'drawingArea'
  canvg(document.getElementById('drawingArea'), '<svg>...</svg>');    
  //ignore mouse events and animation
  canvg('canvas', '<?php echo  $_POST['svgchart']?>', { ignoreMouse: true, ignoreAnimation: true });
};
</script>
<canvas id="drawingArea" ></canvas>

