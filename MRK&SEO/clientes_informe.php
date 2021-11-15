<?/*@ini_set('display_errors', TRUE);
error_reporting(version_compare(PHP_VERSION, 5.3, '>=') ? E_ALL & ~E_DEPRECATED & ~E_NOTICE : version_compare(PHP_VERSION, 6.0, '>=') ? E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT : E_ALL & ~E_NOTICE);
*/?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css"
    href="https://webservice.piensavirtual.com/static/dist/vendor/simplebar/dist/simplebar.min.css?2021-10-04" />
  <link rel="stylesheet" type="text/css"
    href="https://webservice.piensavirtual.com/static/dist/vendor/tiny-slider/dist/tiny-slider.css?2021-10-04" />
  <link rel="stylesheet" type="text/css"
    href="https://webservice.piensavirtual.com/static/dist/vendor/drift-zoom/dist/drift-basic.min.css?2021-10-04" />
  <link rel="stylesheet" type="text/css"
    href="https://webservice.piensavirtual.com/static/dist/css/theme.css?2021-10-04" />


  <script src="https://code.jquery.com/jquery-1.12.4.min.js?v=1.0"></script>
  <script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js?v=1.0"></script>
  <script src="https://webservice.piensavirtual.com/static/dist/vendor/bootstrap/dist/js/bootstrap.bundle.min.js?v=1.0">
  </script>
  <script src="https://webservice.piensavirtual.com/static/dist/vendor/simplebar/dist/simplebar.min.js?v=1.0"></script>
  <script src="https://webservice.piensavirtual.com/static/dist/vendor/tiny-slider/dist/min/tiny-slider.js?v=1.0">
  </script>
  <script
    src="https://webservice.piensavirtual.com/static/dist/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js?v=1.0">
  </script>
  <script src="https://webservice.piensavirtual.com/static/dist/vendor/drift-zoom/dist/Drift.min.js?v=1.0"></script>
  <script src="https://webservice.piensavirtual.com/static/dist/js/theme.min.js?v=1.0"></script>

  <style type="text/css">
    @page {
      size: auto;
      /* auto is the initial value */
      margin: 0;
      /* this affects the margin in the printer settings */
    }

    @media print {
      .saltopagina {
        break-before: page;
        position: relative;

      }
    }

    @media print {
      .no-print {
        visibility: hidden;
      }

      #header {
        position: absolute;
        top: 0;
        margin-top: 20px;
        margin-bottom: 20px;
        width: 150px;
        font-size: 20px;
        content: url("http://webservice.piensavirtual.com/img/logo1-1.png");
      }

      .intro {
        display:block;
        position: absolute;
        margin-top: 200px;
        margin-left:80px;
        opacity: 0.8;
        transform: scale(0.5, 0.5);
      }

    }

    @media print {

      div {
        float: none !important;
        position: static !important;
        display: inline;
        box-sizing: content-box !important;
      }
    }

    @media print {
      #mainBody {
        size: auto;
        border: none;
        margin: 0px;
        padding-left: 30px;
        padding-right: 40px;

      }
    }

    @media screen {
      .hide {
        display: none;
      }
    }

  </style>
</head>

<body class="container">
  <?php
require "../../llibreries/application_admin_top.php";
// Load the Google API PHP Client Library.
//

require_once __DIR__ . '/vendor/autoload.php'; 

// Start a session to persist credentials.
session_start();

if(isset($_POST['salir'])) {
  unset($_SESSION['access_token']);
}

$client = new Google_Client();
$client->setAuthConfig(__DIR__ . '/account-credentials.json');
$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY); 

// Set Language variable
if(isset($_POST['lang']) && !empty($_POST['lang'])){
  $_SESSION['lang'] = $_POST['lang'];
}
 // Iude Language file
 if(isset($_SESSION['lang'])){
  include $_SESSION['lang'].".php";
 }else{
  include "es.php";
 }

?><script>
    function changeLang() {
      document.getElementById('form_lang').submit();
    }
  </script>
  <?

$lenguages= array(
  array('value'=>'es','title'=>'Español'),
  array('value'=>'ca','title'=>'Català'),
  array('value'=>'us','title'=>'English'),
  array('value'=>'todo','title'=>'Todos'),
);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
   // Set the access token on the client.
   $client->setAccessToken($_SESSION['access_token']);

   // Create an authorized analytics service object.
   $analytics = new Google_Service_Analytics($client);
}else{
  $redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . '/nuestros_clientes/administracio/clientes/oauth2callback.php';
  echo "NO HAY RESPUESTA";
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}


?>
  <div class="no-print">
    <div class="row">
      <div class="col-xl-11 ">
        <form action="" method="post">
          <fieldset id="buscador">
          <select name="lang" style="height: 30px; widht: 8px;">
            <option value="es" <?echo ($_POST['lang']=='es' ? "selected" : "" )?>>Español</option>
            <option value="ca" <?echo ($_POST['lang']=='ca' ? "selected" : "" )?>>Català</option>
          </select>

          <input type="text" value="<?echo $_POST['web']?>" name="web">

          <select name="tipo" style="height: 30px">
            <option value="0">Tipo</option>
            <option value="volume" <?echo ($_POST['tipo']=='volume' ? "selected" : "" )?>>Numero de busquedes</option>
            <option value="competition" <?echo ($_POST['tipo']=='competition' ? "selected" : "" )?>>Competencia</option>
            <option value="price" <?echo ($_POST['tipo']=='price' ? "selected" : "" )?>>Preu</option>
            <option value="traffic" <?echo ($_POST['tipo']=='traffic' ? "selected" : "" )?>>Trafico</option>
          </select>

          <select name="order" style="height: 30px">
            <option value="0">Ordre</option>
            <option value="asc" <?echo ($_POST['order']=='asc' ? "selected" : "" )?>>Ascendet</option>
            <option value="desc" <?echo ($_POST['order']=='desc' ? "selected" : "" )?>>Descendent</option>
          </select>

          <select name="idioma" style="height: 30px; widht: 8px;">
            <option value="0">🌐</option>
            <?foreach ($lenguages as $key => $value) {
          echo '<option value="'.$value['value'].'"'.($_POST['idioma']==$value['value'] ? "selected" : "").'>'.$value['title'].'</option>';
        } ?>

          </select>
          <input type="number" value="<?echo $_POST['num']?>" name="num"
            onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
          <input type="submit" value="Buscar">

          <i type="submit" class="btn btn-danger btn-shadow; ci-download" name="descarga"
            style="float:right;font-size:1.2rem;" onclick="window.print()"></i>
            </fieldset>
        </form>
        <div class="col-xl-12 " >
          <form action="" method="post" >
          <fieldset id="logout">
            <button type="submit" class="btn btn-warning btn-shadow; ci-sign-out " name="salir"
              style="float:right;font-size:1.2rem"></button>
              </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>


  <div class="hide" style="position:absolute">
    <h1 style="margin-top:50%;text-align:center">
      <?echo _Title?>
    </h1>
    <h4 style="margin-top:50%;text-align:center;text-transform: uppercase;">
      <?echo _Web.$_POST['web']?>
    </h4>

  </div>
  <img class="intro hide" src="http://webservice.piensavirtual.com/img/logo1-1.png" alt="" />

  <?
$_POST['web'];
$dom = $_POST['web'];

$resultado = $db->Execute("SELECT * FROM `clientes` WHERE `url_real` LIKE '%$dom%'" );
$dbRow = mysqli_fetch_array($resultado);

$host = $dbRow['host_real'];
$usuario = $dbRow['usuario_real'];
$contrasenya = $dbRow['contrasena_real'];
$nom_db = $dbRow['nombre_base_datos_real'];
$prefijo = $dbRow['prefijo'].($dbRow['prefijo']!=''?'_':'');



$db->Connect($host,$usuario,$contrasenya,$nom_db,$prefijo) or die("Unable to connect to database");


function buscarSEO($origen,$web,$order,$tipo,$num){
  $url = "https://api4.seranking.com/research/$origen/keywords/?domain=$web$order$tipo$num";
  $token = "cea1fa7494f2b3e3efa90cfffde282da7e5e1182";
  $curl = curl_init($url);
  curl_setopt_array($curl, [
      CURLOPT_HTTPHEADER => ["Authorization: Token ".$token],
      CURLOPT_HEADER => 0,
      CURLOPT_RETURNTRANSFER => 1,
    
  ]);
  $content = curl_exec($curl);
  $info = curl_getinfo($curl);

  if (200 == $info["http_code"]&& $content ) {
    return json_decode($content);
  }
  return false;
}
function mostrarSEO($result,$len,$web){

   ?>
  <table class="table">
    <thead>
      <tr>
        <form method='post' action=''>
          <th>
            <?echo _NOM?>
          </th>
          <th>
            <?echo _BUSCAR?>
          </th>
          <th>CPC</th>
          <th>
            <?echo _COMP?>
          </th>
          <th>KEI</th>
          <th>
            <?echo _TPAG?>
          </th>
          <th>
            <?echo _POS?>
          </th>
          <th>
            <?echo _POSP?>
          </th>
          <th>
            <?echo _PREU?>
          </th>
          <th>
            <?echo _TRAFIC?>
          </th>
        </form>
      </tr>
    </thead>
    <tbody>

      <?
          foreach ($result as $key => $value) {
            if(($value->position)>($value->prev_pos)){
              $n_position = ($value->prev_pos)-($value->position);
              $n_position =  "<p style='color:red;font-size:x-small;display:inline;vertical-align: text-top'>$n_position</p>";
            }
            else if(($value->position)<($value->prev_pos)){
              $n_position = ($value->prev_pos)-($value->position);
              $n_position =  "<p style='color:green;font-size:x-small;display:inline;vertical-align: text-top'>$n_position</p>";
            }
            else{
              $n_position =  null;
            }
            ?>

      <tr>
        <?
                echo "<td>".$value->keyword."</td>" ; //nombre
                echo "<td>".$value->volume."</td>" ; //numero de busquedas
                echo "<td>".$value->cpc."</td>" ;  // coste por click
                echo "<td>".$value->competition."</td>" ; //competencia
                echo "<td>".$value->kei."</td>" ; //Indice de efectividad de palabras clave
                echo "<td>".$value->total_sites."</td>" ;  //Numero total de sitios para consultar
                echo "<td>".$value->position." ".$n_position."</td>" ; // posicion
                echo "<td>".$value->prev_pos."</td>" ; // previa posicion
                echo "<td>".$value->price."€</td>" ; //precio
                echo "<td>".$value->traffic."</td>" ; //trafic
             ?>
      </tr>

      <? } ?>
    </tbody>
  </table>

  <?
      
}

function lomasVendido($prefijo,$numSQL){
  ?>

  <h3>
    <?echo _LMaVe?>
  </h3>

  <?php
  global $db;
  
  $resultado = $db->Execute("SELECT `products_id`,`products_name`,`products_referencia`,`products_model`,COUNT(*) as total 
  FROM `".$prefijo."orders_products` GROUP BY `products_id` ORDER BY total DESC LIMIT $numSQL"); 

  if($resultado == null){
    print_r("<b>NO SE HA ENCONTRADO NINGUNA RESULTADO</b>");
    
  }else{

    ?>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">
          <?echo _PROD?>
        </th>
        <th scope="col">
          <?echo _REF?>
        </th>
        <th scope="col">TOTAL</th>
      </tr>
    </thead>
    <tbody>
      <?
  while($dbRow = mysqli_fetch_array($resultado)){?>
      <tr>
        <th scope="row"><?php echo utf8_encode($dbRow['products_id']);?></th>
        <td><?php echo utf8_encode( $dbRow['products_name']);?></td>
        <td>
          <?php echo utf8_encode( $dbRow['products_referencia'] ? $dbRow['products_model']:$dbRow['products_model']);?>
        </td>
        <td><?php echo $dbRow['total'];?></td>
      </tr>
      <?php }
  ?>
    </tbody>
  </table>


  <?
 } 
}
function lomasVendido30($prefijo,$numSQL){
  ?>

  <h3>
    <?echo _LMaVe30?>
  </h3>

  <?php
  global $db;

  $resultado = $db->Execute("SELECT op.products_id,op.products_name,op.products_referencia,op.products_model,COUNT(*) AS total 
  FROM `".$prefijo."orders_products` AS `op` 
  LEFT JOIN `".$prefijo."orders` AS `o` ON (op.orders_id = o.orders_id)
  where DATE_SUB(CURDATE(),
  INTERVAL 30 DAY)
  < o.date_purchased
   GROUP BY `products_id` ORDER BY total DESC LIMIT $numSQL");

  if($resultado == null){
    print_r("<b>NO SE HA ENCONTRADO NINGUNA RESULTADO</b>");

  }else{

    ?><table class="table">
    <thead>
      <tr>
        <th scope="">ID</th>
        <th scope="">
          <?echo _PROD?>
        </th>
        <th scope="col">
          <?echo _REF?>
        </th>
        <th scope="">TOTAL</th>
      </tr>
    </thead>
    <tbody>
      <?
  while($dbRow = mysqli_fetch_array($resultado)){?>

      <tr>
        <th scope=""><?php echo utf8_encode($dbRow['products_id']);?></th>
        <td><?php echo utf8_encode( $dbRow['products_name']);?></td>
        <td>
          <?php echo utf8_encode( $dbRow['products_referencia'] ? $dbRow['products_model']:$dbRow['products_model']);?>
        </td>
        <td><?php echo $dbRow['total'];?></td>
      </tr>
      <?php }
  ?>
    </tbody>
  </table>


  <?
 } 
}
function Lomasbuscado($prefijo,$numSQL){?>

  <h3>
    <?echo _MaBusc?>
  </h3>

  <?php
  global $db;
  $resultado = $db->Execute("SELECT  CONCAT(UCASE(LEFT(`search_term`,1)),LCASE(SUBSTRING(`search_term`, 2))) AS name,COUNT(`search_term`) as palabras ,`search_log_id`,`search_results` 
  FROM `".$prefijo."search_log`  
  GROUP BY name ORDER BY palabras DESC LIMIT $numSQL");

  if($resultado == null){
    print_r("<b>NO SE HA ENCONTRADO NINGUNA RESULTADO</b>");
    
  }else{
    ?><table class="table">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">
          <?echo _NOM?>
        </th>
        <th scope="col">
          <?echo _NUM?>
        </th>
        <th scope="col">
          <?echo _VIST?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?
  while($dbRow = mysqli_fetch_array($resultado)){ ?>

      <tr>
        <th scope="row"><?php echo($dbRow['search_log_id']);?></th>
        <td><?php echo utf8_encode($dbRow['name']);?></td>
        <td><?php echo $dbRow['palabras'];?></td>
        <td><?php echo utf8_encode($dbRow['search_results']);?></td>
      </tr>
      <?php }
  ?>
    </tbody>
  </table>


  <?
  }
}
function Lomasbuscado30($prefijo,$numSQL){?>

  <h3>
    <?echo _MaBusc30?>
  </h3>

  <?php
    global $db;
    $resultado = $db->Execute("SELECT  CONCAT(UCASE(LEFT(`search_term`,1)),LCASE(SUBSTRING(`search_term`, 2))) AS name,COUNT(`search_term`) as palabras ,`search_log_id`,`search_results`
    FROM `".$prefijo."search_log`  
    where DATE_SUB(CURDATE(),
    INTERVAL 30 DAY)
    < date(`search_time`)
    GROUP BY name ORDER BY palabras DESC LIMIT $numSQL");

    if($resultado == null){
      print_r("<b>NO SE HA ENCONTRADO NINGUNA RESULTADO</b>");
      
    }else{
      ?><table class="table">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">
          <?echo _NOM?>
        </th>
        <th scope="col">
          <?echo _NUM?>
        </th>
        <th scope="col">
          <?echo _VIST?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?
    while($dbRow = mysqli_fetch_array($resultado)){ ?>

      <tr>
        <th scope="row"><?php echo($dbRow['search_log_id']);?></th>
        <td><?php echo utf8_encode($dbRow['name']);?></td>
        <td><?php echo $dbRow['palabras'];?></td>
        <td><?php echo utf8_encode($dbRow['search_results']);?></td>
      </tr>
      <?php }
  ?>
    </tbody>
  </table>
  <?
  }
}
function Lomenosbuscado($prefijo,$numSQL){?>

  <h3>
    <?echo _MeBusc?>
  </h3>

  <?php
    global $db;
    $resultado = $db->Execute("SELECT  CONCAT(UCASE(LEFT(`search_term`,1)),LCASE(SUBSTRING(`search_term`, 2))) AS name,COUNT(`search_term`) as palabras ,`search_log_id`,`search_results`
    FROM `".$prefijo."search_log`  
    GROUP BY name ORDER BY palabras ASC,`search_results` DESC LIMIT $numSQL");

    if($resultado == null){
      print_r("<b>NO SE HA ENCONTRADO NINGUNA RESULTADO</b>");
    }else{
      ?>
  <table class="table">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">
          <?echo _NOM?>
        </th>
        <th scope="col">
          <?echo _NUM?>
        </th>
        <th scope="col">
          <?echo _VIST?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?
      while($dbRow = mysqli_fetch_array($resultado)){ ?>
      <tr>
        <th scope="row"><?php echo($dbRow['search_log_id']);?></th>
        <td><?php echo utf8_encode($dbRow['name']);?></td>
        <td><?php echo $dbRow['palabras'];?></td>
        <td><?php echo utf8_encode($dbRow['search_results']);?></td>
      </tr>
      <?php }
    ?>
    </tbody>
  </table>
  <?
  }
}
function noresult($prefijo,$numSQL){
 ?>
  <h3>
    <?echo _NoRes?>
  </h3>

  <?php
    global $db;
    $resultado = $db->Execute("SELECT CONCAT(UCASE(LEFT(`search_term`,1)),LCASE(SUBSTRING(`search_term`, 2))) AS name ,SUM(search_results) as totalprod, COUNT(*) as palabras
    FROM `".$prefijo."search_log` 
    GROUP BY name
    HAVING SUM(search_results) = 0
    ORDER BY palabras DESC,`search_time` DESC  LIMIT $numSQL");

    if($resultado == null){
      print_r("<b>NO SE HA ENCONTRADO NINGUNA RESULTADO</b>");
    }else{
      ?><table class="table">
    <thead>
      <tr>
        <th scope="col">
          <?echo _NOM?>
        </th>
        <th scope="col">
          <?echo _NUM?>
        </th>
        <th scope="col">
          <?echo _VIST?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?
    while($dbRow = mysqli_fetch_array($resultado)){ ?>
      <tr>
        <td><?php echo utf8_encode(substr($dbRow['name'], 0, 50));?></td>
        <td><?php echo utf8_encode($dbRow['palabras']);?></td>
        <td><?php echo utf8_encode($dbRow['totalprod']);?></td>
      </tr>
      <?php }
    ?>
    </tbody>
  </table>
  <?
  }
}
function noresult30($prefijo,$numSQL){
  ?>
  <h3>
    <?echo _NoRes30?>
  </h3>

  <?php
     global $db;
     $resultado = $db->Execute("SELECT CONCAT(UCASE(LEFT(`search_term`,1)),LCASE(SUBSTRING(`search_term`, 2))) AS name ,SUM(search_results) as totalprod, COUNT(*) as palabras
     FROM `".$prefijo."search_log` 
       where DATE_SUB(CURDATE(),
       INTERVAL 30 DAY)
       < date(`search_time`)
     GROUP BY name
     HAVING SUM(search_results) = 0
     ORDER BY palabras DESC,`search_time` DESC  LIMIT $numSQL");
 
     if($resultado == null){
       print_r("<b>NO SE HA ENCONTRADO NINGUNA RESULTADO</b>");
     }else{
       ?> <table class="table">
    <thead>
      <tr>
        <th scope="col">
          <?echo _NOM?>
        </th>
        <th scope="col">
          <?echo _NUM?>
        </th>
        <th scope="col">
          <?echo _VIST?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?
     while($dbRow = mysqli_fetch_array($resultado)){ ?>
      <tr>
        <td><?php echo utf8_encode(substr($dbRow['name'], 0, 50));?></td>
        <td><?php echo utf8_encode($dbRow['palabras']);?></td>
        <td><?php echo utf8_encode($dbRow['totalprod']);?></td>
      </tr>
      <?php }
     ?>
    </tbody>
  </table>
  <?
   }
}


$web = ($_POST['web'] ? $_POST['web'] : "weboryx.com");
$tipo = "&order_field=".($_POST['tipo'] ? $_POST['tipo'] : "traffic");
$order ="&order_type=".($_POST['order'] ? $_POST['order'] : "desc");
$num="&limit=".($_POST['num']  ? $_POST['num'] : "10");
$len=($_POST['idioma']  ? $_POST['idioma'] : "es");
$numSQL=($_POST['numSQL']  ? $_POST['numSQL'] : "10");
$firstAccountId = ($_POST['marketing'] ? $_POST['marketing'] : "www.weboryx.com");
$mes_atras=($_POST['meses_atras']  ? $_POST['meses_atras'] : "3");

?>
  <div class="saltopagina" id="mainBody">
    <?
if($len == "todo"){

  foreach ($lenguages as $key => $value) {
    $result=buscarSEO($value['value'],$web,$order,$tipo,$num);
    if($result==false || $web==false){
    
    }else{
     
      ?>
    <div class="saltopagina" id="header"> </div>
    <?
        ?>
    <h3 style="text-transform: uppercase;">INFORME SEO
      <?echo $value['value']?>
    </h3>
    <?
        mostrarSEO($result,$value['value'],$web);
   
       
    }
    
  }
}else {
    $result=buscarSEO($len,$web,$order,$tipo,$num);
    if($result==false  || $web==false){
        print_r("<b>NO SE HA ENCONTRADO NINGUNA PAGINA</b>");
      
    }else{
      
      ?>
    <div class="saltopagina" id="header"> </div>
    <?
          ?>
    <h3 style="text-transform: uppercase;">INFORME SEO
      <?echo $len?>
    </h3>
    <?
          mostrarSEO($result,$len,$web);
          


    }
  
}
?>
    <div class="no-print">
      <form action="" method="POST">
        <input type="hidden" name="web" value="<?echo $_POST['web']?>">
        <input type="hidden" name="tipo" value="<?echo $_POST['tipo']?>">
        <input type="hidden" name="order" value="<?echo $_POST['order']?>">
        <input type="hidden" name="num" value="<?echo $_POST['num']?>">
        <input type="hidden" name="idioma" value="<?echo $_POST['idioma']?>">
        <input type="hidden" name="marketing" value="<?echo $_POST['marketing']?>">
        <input type="hidden" name="meses_atras" value="<?echo $_POST['meses_atras']?>" id="mes_atras"
          onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
        <input type="number" value="<?echo $_POST['numSQL']?>" name="numSQL"
          onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
        <input type="submit" value="Buscar SQL"><br>
      </form>
      <br>
    </div>

    <div class="saltopagina" id="header"> </div> <br>
    <?
          lomasVendido($prefijo,$numSQL);
          ?>
    <div class="saltopagina" id="header"> </div> <br>
    <? 
          lomasVendido30($prefijo,$numSQL);
          ?>
    <div class="saltopagina" id="header"> </div> <br>
    <?
          Lomasbuscado($prefijo,$numSQL);
           ?>
    <div class="saltopagina" id="header"> </div> <br>
    <?
          Lomasbuscado30($prefijo,$numSQL);
          ?>
    <div class="saltopagina" id="header"> </div> <br>
    <?
          Lomenosbuscado($prefijo,$numSQL);
          ?>
    <div class="saltopagina" id="header"> </div> <br>
    <?
          noresult($prefijo,$numSQL);
           ?>
    <div class="saltopagina" id="header"> </div> <br>
    <?
          noresult30($prefijo,$numSQL);
          
          ?>
    <div class="no-print">
      <form action="" method="POST">
        <input type="hidden" name="web" value="<?echo $_POST['web']?>">
        <input type="hidden" name="tipo" value="<?echo $_POST['tipo']?>">
        <input type="hidden" name="order" value="<?echo $_POST['order']?>">
        <input type="hidden" name="num" value="<?echo $_POST['num']?>">
        <input type="hidden" name="idioma" value="<?echo $_POST['idioma']?>">
        <input type="hidden" value="<?echo $_POST['numSQL']?>" name="numSQL"
          onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
        <div>
          <select name="marketing" style="height: 30px">
            <?
            $accounts = $analytics->management_accounts->listManagementAccounts();
              if (count($accounts->getItems()) > 0) {
                $items = $accounts->getItems();

                foreach ($items as $key => $value) {
                  echo '<option value="'.$value->getId().'"'.($_POST['marketing']==$value->getId() ? "selected" : "").'>'.$value->getName().'</option>';
                 
                }
              }
              ?>
          </select>
          <input type="number" name="meses_atras" value="<?echo $_POST['meses_atras']?>" id="mes_atras"
            onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
          <button type="submit">Buscar Mes</button>
        </div>
      </form>
      <br>
    </div>
    <?

//analytics
  
if(isset($mes_atras)){
       
  $mesactual = date("Y-m-d");
  
  $meses_atras = $mes_atras ;

  $mes = date("Y-m-01" , strtotime("- ".$meses_atras." month"));

  // Get the first view (profile) id for the authorized user.
  //$profile = getFirstProfileId($analytics);

  // Get the list of properties for the authorized user.
  $properties = $analytics->management_webproperties
  ->listManagementWebproperties($firstAccountId);

  if (count($properties->getItems()) > 0) {
    $items = $properties->getItems();
    $firstPropertyId = $items[0]->getId();

    // Get the list of views (profiles) for the authorized user.
    $profiles = $analytics->management_profiles
        ->listManagementProfiles($firstAccountId, $firstPropertyId);

    if (count($profiles->getItems()) > 0) {
      $items = $profiles->getItems();

      // Return the first view (profile) ID.
      $profile = $items[0]->getId();
          
    }else{
      echo "No tiene permisos";
    }

  }else{
    echo "GA incompatible";
  }

  // Get the results from the Core Reporting API and print the results.
  //bucle mesos
  $mes_loop = date("n" , strtotime("- ".$meses_atras." month"));
  $mes_1 = date("n");

  
    $info = array(); 
    $info2 = array();
    $array_messes = array();

    for ($j = $mes_loop; $j<= $mes_1; $j++) { 

      $data_fin =  ultimo_dia_mes($j);
      $data_ini = primer_dia_mes($j);

    
  $results = getResults($analytics,$profile, $data_ini, $data_fin,'ga:sessions,ga:users,ga:bounceRate,ga:newUsers,ga:avgSessionDuration,ga:transactionsPerSession');
    $results2 = getResults($analytics,$profile, $data_ini, $data_fin,'ga:transactionRevenue',array(
      'dimensions' => 'ga:source'
  ));

  $num_mes  = $j;
  $data   = DateTime::createFromFormat('!m', $num_mes);
  $nom_mes = $data->format('F'); 
  setlocale(LC_TIME, 'es_ES', 'esp_esp');
  $info['cabecera'][] = $nom_mes;
  $info2['cabecera'][] = $nom_mes;
  $array_messes[$num_mes]=$nom_mes;
// Get the profile name.
$profileName = $results->getProfileInfo()->getProfileName();
  if (count($results->getRows()) > 0) {
  
   

    // Get the entry for the first entry in the first row.
    $rows = $results->getRows();
    $sessions = $rows[0][0];
    $users = $rows[0][1];
    $rebot = $rows[0][2];
    $nous = $rows[0][3];
    $temps_mig = $rows[0][4];
    $conversio = $rows[0][5];

    $rebot =  round($rebot,2);
    $conversio = round($conversio,2);
  
    $seconds = $temps_mig ;
      $H = floor($seconds / 3600);
      $i = ($seconds / 60) % 60;
      $s = $seconds % 60;
    $temps= sprintf("%02d:%02d:%02d", $H, $i, $s);

    $info['visites'][] = $sessions;
    $info['sessiones'][] = $users;
    $info['rebot'][] = $rebot;
    $info['nous'][] = $nous;
    $info['tmig'][] = $temps;
    $info['conv'][] = $conversio;

      
  }

  if (count($results2->getRows()) > 0) {


      $row2 = $results2->getRows();


      for($a = 0; $a < sizeof($row2); $a++){
        $data=$row2[$a];
        $transaction = $data[0];
        $source = $data[1];
        $info2['data'][$transaction]['source'][] = array('value'=>$source,'mes'=>$j);
        $info2['total'][$j] += $source;
      }

      

      }
  }


} 

function acqusition($info2,$array_messes){ 

  $mes_atras=($_POST['meses_atras']  ? $_POST['meses_atras'] : "3");

  ?>
      <table class="table">
        <thead>
          <tr>
            <th>
              <?echo _OrgIn?>
            </th>
            <? foreach ($info2['cabecera'] as $key) {
                echo "<th>$key</th>";
              }?>
              <th>
              <?echo "Total"?>
            </th>
          </tr>
        </thead>
        <tbody> 
          <?
          foreach ($info2['data'] as $key => $data ) {

            $totalLin = 0;

            foreach ($data['source'] as $value ) {
              $totalLin += $value['value'];
            }

            if($totalLin == 0) continue;
            
            
            ?>
         <tr
         
         ><td>
              <? echo $key;?>
         </td> <?  
          if(sizeof($data['source']) < (1+$mes_atras)){

            $arr=array();
            foreach ($array_messes as $mes => $nom_mes) {
              $arr[]=array('value'=>"0.0",'mes'=>$mes);
            }
            $data['source']=array_replace($arr,$data['source']); 

            }?>
            <? 
            $totalLin = 0;
           

            foreach ($data['source'] as $key => $value ) {
              
              $totalLin += $value['value'];


                ?><td><?
             echo number_format($value['value'],2,',', '.')." €";
            ?></td><?
           }
        ?>

        <td> 
          <?php echo number_format($totalLin,2,',', '.')." €";?>
        </td>
          </tr>
          <?php }
        ?>
          <tr>

            <th>TOTAL</th>
            <?foreach ($info2['total'] as $key => $value) { 
    echo "<td> ".$value." €</td>";}?>
          </tr>
        </tbody>

      </table>
      <?
}

function behaivour($info){?>
    <h3>GOOGLE ANALYTICS</h3>
    <table class="table">
      <thead>
        <tr>
          <th scope="row">
            <?echo _Ventas?>
          </th>
          <? foreach ($info['cabecera'] as $key => $value) {
              echo "<th>$value</th>";
            }?>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">
            <?echo _Visitas?>
          </th>
          <? foreach ($info['visites'] as $key => $value) {
              echo "<td>$value</td>";
            }?>
        </tr>
        <tr>
          <th scope="row">
            <?echo _Session?>
          </th>
          <? foreach ($info['sessiones'] as $key => $value) {
              echo "<td>$value</td>";
            }?>
        </tr>
        <tr>
          <th scope="row">
            <?echo _Rebot?>
          </th>
          <? foreach ($info['rebot'] as $key => $value) {
              echo "<td>$value%</td>";
            }?>
        </tr>
        <tr>
          <th scope="row">
            <?echo _New?>
          </th>
          <? foreach ($info['nous'] as $key => $value) {
              echo "<td>$value</td>";
            }?>
        </tr>
        <tr>
          <th scope="row">
            <?echo _Time?>
          </th>
          <? foreach ($info['tmig'] as $key => $value) {
              echo "<td>$value</td>";
            }?>
        </tr>
        <tr>
          <th scope="row">
            <?echo _Conv?>
          </th>
          <? foreach ($info['conv'] as $key => $value) {
              echo "<td>$value%</td>";
            }?>
        </tr>

      </tbody>
    </table>


    <?
}             

function getFirstProfileId($analytics) {
  // Get the user's first view (profile) ID.
  // $ordering = new Google_Service_AnalyticsReporting_OrderBy();
  // $ordering->setFieldName("ga:transactionRevenue");
  // $ordering->setOrderType("VALUE"); 
  // $ordering->setSortOrder("DESCENDING");

  // Get the list of accounts for the authorized user.
  $accounts = $analytics->management_accounts->listManagementAccounts();
  if (count($accounts->getItems()) > 0) {
  $items = $accounts->getItems();
  $firstAccountId = $items[0]->getId();

  // Get the list of properties for the authorized user.
  $properties = $analytics->management_webproperties
    ->listManagementWebproperties($firstAccountId);

  if (count($properties->getItems()) > 0) {
  $items = $properties->getItems();
  $firstPropertyId = $items[0]->getId();

  // Get the list of views (profiles) for the authorized user.
  $profiles = $analytics->management_profiles
      ->listManagementProfiles($firstAccountId, $firstPropertyId);

  if (count($profiles->getItems()) > 0) {
    $items = $profiles->getItems();
    print_r($items);

    // Return the first view (profile) ID.
    return $items[0]->getId();

  } else {
    throw new Exception('No views (profiles) found for this user.');
  }
  } else {
  throw new Exception('No properties found for this user.');
  }
  } else {
  throw new Exception('No accounts found for this user.');
  }
}

function ultimo_dia_mes($month) { 
  //$month = date('m');
  $year = date('Y');
  $day = date("d", mktime(0,0,0, $month+1, 0, $year));

  return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
  }

  function primer_dia_mes($month) {
  // $month = date('m');
  $year = date('Y');
  return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
}

function getResults($analytics, $profileId,$data_ini,$data_fin,$tipo,$dimensions=array()) {
  // Calls the Core Reporting API and queries for the number of sessions
  // for the last seven days.
  return $analytics->data_ga->get(
  'ga:' . $profileId,
  $data_ini, $data_fin,
  $tipo,$dimensions);


}
?>
    <div class="saltopagina" id="header"> </div> <br>
    <?
behaivour($info)
?>
    <div class="saltopagina" id="header"> </div> <br>
    <?
acqusition($info2,$array_messes); 
?>


</body>
</html>