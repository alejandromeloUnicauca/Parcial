<?php 
    $num_emp = 0;
    if(isset($_POST["b_inc"])){
        actualizarIncremental();
    }

    function actualizarIncremental(){
        $dep = $_POST['n_dependencias'];
        $subDep = $_POST['n_subDep'];
        $jsonString = file_get_contents('consecutivos.json');
        $arrayConsecutivos = json_decode($jsonString, true);
        $longitud = count($arrayConsecutivos);

        $arrayConsecutivos[$dep]['incremental']+=1;
        echo '<script>alert(" Se creo el documento con codigo:';
        echo  "8.".$arrayConsecutivos[$dep]['codigo'].".".$arrayConsecutivos[$dep]['Sub_Dependencias'][$subDep]['codigo']."/".$arrayConsecutivos[$dep]['incremental'];
        echo '");</script>';
        
        $datos = json_encode($arrayConsecutivos);
        file_put_contents('consecutivos.json', $datos);
    }
?>
<script>
function cambiarSubDependencias(obj){
    if(obj.value != -1){
        var filePath = 'consecutivos.json'
        xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET",filePath,false);
        xmlhttp.send(null);
        var fileContent = xmlhttp.responseText;
        var fileArray = JSON.parse(fileContent);
        var select = document.getElementById("id_slc_subDep");
        while (select.firstChild) {
            select.removeChild(select.firstChild);
        }
        var divd = document.getElementById("id_div_dep");
        while (divd.firstChild) {
            divd.removeChild(divd.firstChild);
        }
        h = document.createElement("h5");
        h.textContent = "Consecutivo actual por dependencia";
        divd.appendChild(h);

        var option = document.createElement("option");
        option.value = -1;
        option.textContent = "-Seleccione-";
        select.appendChild(option);
        for (i = 0; i < fileArray[obj.value].Sub_Dependencias.length; i++) { 
            
            option = document.createElement("option");
            option.value = i;
            option.textContent = fileArray[obj.value].Sub_Dependencias[i].nombre+"("+fileArray[obj.value].Sub_Dependencias[i].codigo    +")";
            select.appendChild(option);
        }
    }
}

function mostrarConsecutivo(obj){
    var slcSub = document.getElementById("id_slc_dep");
    if(obj.value != -1){
        var filePath = 'consecutivos.json?_='+(new Date().getTime());
        xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET",filePath,false);
        xmlhttp.send(null);
        var fileContent = xmlhttp.responseText;
        var fileArray = JSON.parse(fileContent);
        var divd = document.getElementById("id_div_dep");
        while (divd.firstChild) {
            divd.removeChild(divd.firstChild);
        }
        h = document.createElement("h5");
        h.textContent = "Consecutivo actual por dependencia";
        divd.appendChild(h);

        h = document.createElement("h6");
        h.textContent = fileArray[slcSub.value].nombre;
        divd.appendChild(h);

        h = document.createElement("h6");
        h.textContent = fileArray[slcSub.value].Sub_Dependencias[obj.value].nombre;
        divd.appendChild(h);

        hActual = document.createElement("h6");
        hActual.textContent = "Actual:8."+fileArray[slcSub.value].codigo+"."+fileArray[slcSub.value].Sub_Dependencias[obj.value].codigo+"/"+fileArray[slcSub.value].incremental;
        divd.appendChild(hActual);

        hNuevo = document.createElement("h6");
        hNuevo.style = "backGround:#01DF3A;";
        hNuevo.textContent = "Nuevo:8."+fileArray[slcSub.value].codigo+"."+fileArray[slcSub.value].Sub_Dependencias[obj.value].codigo+"/"+(fileArray[slcSub.value].incremental+1);
        divd.appendChild(hNuevo);
    }
}
    
function validarDependencias(){
    var select1 = document.getElementById("id_slc_dep");
    var select2 = document.getElementById("id_slc_subDep");
    if(select1.value != -1 && select2.value != -1){
        if (confirm('Esta seguro de crear el documento?')) {
            return true;
        } else {
            return false;
        }
    }else{
        alert("Debe seleccionar la dependencia y la sub dependencia");
        return false;
    }
}
</script>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/skeleton.css"/>
        <link rel="stylesheet" href="css/normalize.css"/>
        <link rel="stylesheet" href="css/style.css"/>
        <title>Gestion de documentos</title>
    </head>
    <body>
        <!--<img style="width:100px; height:100px;" src="unicauca.png"/>-->
        <br><br><br>
        <h2> 
            Gestion de documentos<br>
            por dependencias<br>
            Universidad del Cauca
        </h2>
        <div class="row">
            <div class="six columns" id="id_div_dep">
                <h5>Consecutivo actual por dependencia</h5>
            </div>
            <hr class="one columns">
            <div class="five columns" >
                <h5 id="doc">Documento</h5>
                <form style="text-align:center" method="post" action="index.php" onsubmit="return validarDependencias()">
                    <label>Dependencias</label>
                    <select style="width:300px; background: #dbdbdb; color:black" id="id_slc_dep" name="n_dependencias" onchange="cambiarSubDependencias(this)">  
                        <option value="-1">-Seleccione-</option>
                    </select><br><br>
                    <label >Sub Dependencias</label>
                    <select style="width:300px; background: #dbdbdb; color:black" id="id_slc_subDep" name="n_subDep" onchange="mostrarConsecutivo(this)">  
                         <option value="-1">-Seleccione-</option>
                    </select><br><br>
                    <button class="button-primary" name="b_inc" onclick= "this.form.submit()" type="submit">Crear Documento</button>
                </form>
            </div>
        </div>
       
        <div>
            <h5> Funcionamiento de la aplicacion </h5>
            <p>
                <p>En la parte superior en el area de <b>documento</b> se encuentra la opcion de  <b>Depencias</b> debera seleccionar una de las opciones que se despliegan.</p>
                
                <p>En la misma seccion se encuentra la opcion de <b>Sub Depencias</b>, debe seleccionar una de las opciones  que se despliegan.</p>
                
                <p>Al tener las dos opciones seleccionadas en la parte izquierda en la seccion de <br><b>Consecutivo actual por dependencia</b>, aparecera el consecutivo actual y el consecutivo que se va crear.<br> Una vez este seguro de crear el documento se debera de precionar el boton de <b>Crear documento.</b> </p>
            </p>    
        </div>
        <script>
            var filePath = 'consecutivos.json?_='+(new Date().getTime());
            xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET",filePath,false);
            xmlhttp.send(null);
            var fileContent = xmlhttp.responseText;
            var fileArray = JSON.parse(fileContent);
            for (i = 0; i < fileArray.length; i++) { 
                var divd = document.getElementById("id_div_dep");
                var select = document.getElementById("id_slc_dep");
                var option = document.createElement("option");
                option.value = i;
                option.textContent = fileArray[i].nombre + "("+fileArray[i].codigo+")";
                select.appendChild(option);
            }
        </script>
        
        
    </body>
</html>
