

<!DOCTYPE html>
<html>
<head>
	<title>Galería de Imágenes</title>
	<style type="text/css">
		.galeria{
			width: 600px;
			margin: 0 auto;
		}

		.galeria img{
		    width: 30%;  /*ancho de las imagenes*/ 
    	    padding: 20px; /*espacio entre imagenes*/ 

     }

     .galeria img:hover{  /*efecto al pasar el mouse por encima de la imagen*/ 
      opacity: 0.5;   /*opacidad al pasar el mouse por encima de la imagen*/ 

     }

    </style>
</head>
<body>

 <div class="galeria">   <!--contenedor para las imagenes--> 

        <img src="imagen1.jpg" alt="imagen1">   <!--primera imagen--> 



        
        <img src="imagen2.jpg" alt="imagen2">   <!--segunda imagen--> 

        <img src="imagen3.jpg" alt="imagen3">   <!--tercera imagen--> 

        <img src="imagen4.jpg" alt="imagen4">   <!--cuarta imagen--> 

        <img src="imagen5.jpg" alt="imagen5">   <!--quinta imagen--> 

 </div>    <!--fin del contenedor para las imagenes--> 
</body>
</html>