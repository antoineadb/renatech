<html>
    <head>
        <title>PHP AJAX Image Upload</title>
        <link href="styles.css" rel="stylesheet" type="text/css" />
        <script src="../../js/jquery-1.8.0.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function (e) {
                $("#uploadForm").on('change',(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "upload.php",
                        type: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data)
                        {
                            $("#targetLayer").html(data);
                        },
                        error: function ()
                        {
                        }
                    });
                }));
            });
        </script>
    </head>
    <body>
        <div class="bgColor">
            <form id="uploadForm" action="upload.php" method="post">
                <div id="targetLayer">No Image</div>
                <div id="uploadFormLayer">
                    <label>Upload Image File:</label><br/>
                    <input name="userImage" type="file" class="inputFile" id="userImage"/>
                    
                </div>
            </form>
        </div>
    </body>
</html>