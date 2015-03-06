
<script type="text/javascript" src="../js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" src="../js/html2canvas.js"></script>
<script type="text/javascript" src="../js/jquery.plugin.html2canvas.js"></script>
<form method="POST" enctype="multipart/form-data" action="save.php" target="__blank" id="myForm">
    <input type="hidden" name="img_val" id="img_val" value="" />
</form>
<table>
    <tr>
        <td colspan="2">
            <table width="100%">
                <tr>
                    <td>
                        <input type="submit" value="Take Screenshot Of Div Below" onclick="capture();" />
                    </td>                    
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="top" style="padding: 10px;">
            <b>Div:</b>
        </td>
        <td>
            <div id="target">
                <table cellpadding="10">
                    <tr>
                        <td colspan="2">
                            This is sample implementation
                        </td>
                    </tr>                    
                    <tr>
                        <td>
                            <img src="soc.jpg" alt="SOC" />
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
<script type="text/javascript">
    function capture() {
        $('#target').html2canvas({
            onrendered: function(canvas) {
                $('#img_val').val(canvas.toDataURL("image/png"));
                document.getElementById("myForm").submit();
            }
        });
    }
</script>