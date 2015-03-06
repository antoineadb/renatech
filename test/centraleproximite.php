<!DOCTYPE html>
<html >
<head>

    <link rel="stylesheet" href="../js/dijit/themes/claro/claro.css">
	
	<script>dojoConfig = {parseOnLoad: true}</script>
	<script src='../js/dojo/dojo.js'></script>
	
	<script>
require(["dojo/parser", "dijit/Editor"]);
	</script>
</head>
<body class="claro">
    <fieldset style="  padding:10px 15px 15px; border:solid 1px #5D8BA2; margin-right: 20px;margin-top: 15px;width:958px;
                                          margin-bottom: 15px;margin-left: 20px;color:midnightblue;font-family:verdana;border-radius: 5px;border-radius: 5px 5px 5px 5px;border-color: #5D8BA2;" >
<legend>Centrale de proximité</legend>	
	<table>
		<tr>
			<td>
				<label for="centraleproximite" style="width:695px;margin-left: 20px;">Utilisez vous dans votre projet des moyens technologiques d'une centrale de proximité?</label>                                    
				<input type= "radio" data-dojo-type="dijit/form/RadioButton" name="centraleproximite"   value="TRUE"  class="btRadio" style="margin-left: 20px;" onclick="oui()">
				Oui
				<input type= "radio" data-dojo-type="dijit/form/RadioButton" name="centraleproximite"  value="FALSE" checked="checked" class="btRadio" style="margin-left: 20px;" onclick="non()">
				Non
			</td>
		</tr>
	</table>
     <script>
	 function oui(){
		document.getElementById('oui').style.display = 'block';
	 }
	 function non(){
		document.getElementById('oui').style.display = 'none';
	 }
	 </script>
	 <br><br>
<div id="oui" style="display:none;margin-left:20px">
    Vous devez sélectionnez une ou plusieurs régions<br>
    <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id="grandest" name='grandest' >
    <label for = 'grandest' class='opt' >Grand Est</label>
    <br>
    <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id="grandsudouest" name='grandsudouest' >
    <label for = 'grandsudouest' class='opt' >Grand Sud Ouest</label>
    <br>
    <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id="grandnord" name='grandnord' >
    <label for = 'grandnord' class='opt' >Grand Nord</label>
    <br>
    <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id="rhonealpe" name='rhonealpe' >
    <label for = 'rhonealpe' class='opt' >Rhône Alpe</label>
    <br>
    <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id="iledefrance" name='iledefrance' >
    <label for = 'rhonealpe' class='opt' >Île de France</label>
    <br>
    <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id="paca" name='paca' >
    <label for = 'paca' class='opt' >PACA</label>
    <br>
    <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id="crea" name='crea' >
    <label for = 'paca' class='opt' >CREATH</label>
    <br>
    <input  class='opt'  type='checkbox' data-dojo-type='dijit/form/CheckBox' id="ln2" name='ln2' >
    <label for = 'ln2' class='opt' >LN2</label>
    <br>
    <br>         
    <b>Descriptions</b>
    <div style="width:850px" height="150" data-dojo-type="dijit/Editor" id="editor1" data-dojo-props="plugins:['cut','copy','paste','|','bold','italic','underline','strikethrough','subscript','superscript','|', 'indent', 'outdent', 'justifyLeft', 'justifyCenter', 'justifyRight']">
    </div>
</div>
</body>
</html>