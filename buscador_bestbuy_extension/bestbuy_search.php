<?php

$apiKey='8authyqhkda9c2nm4d5mbs7q';

session_start();

$connection = mysql_connect("localhost", "root", ""); 
$db = mysql_select_db("bestbuy_search", $connection); 


if(isset($_GET['articulo'])){
    $query = $_GET['articulo'];
    $keywords = explode(" ",$query);
    $first_keyword = $keywords[0];
    $sk = array();

    //Se llama a la api para buscar el producto
    if(count($keywords) >= 2){
        //Si es más de una palabra de búsqueda
        for ($i = 1; $i < count($keywords); $i++){
            $sk[$i] = "&search=".$keywords[$i];  
        }
        $s = implode("",$sk);
        $search_terms = $first_keyword.$s;
        $requestUrl="https://api.remix.bestbuy.com/v1/products(search={$search_terms})?show=name,salePrice,thumbnailImage,url&pageSize=10&sort=salePrice.asc&apiKey={$apiKey}";
        }
        else{
            $requestUrl="https://api.remix.bestbuy.com/v1/products(search={$first_keyword})?show=name,salePrice,thumbnailImage,url&pageSize=10&sort=salePrice.asc&apiKey={$apiKey}";
        }

    //Obtenemos el xml
    $xml = simplexml_load_file($requestUrl);

    //Lista de productos 
    $productsList = array();

    $results=$xml['total'];

    //Si se encontraron resultados
    if($results>0)
    {
        foreach($xml->product as $product)
        {
            //Agregamos los resultados obtenidos de la api en la base de datos
            $consulta = mysql_query("insert into articulos(imagen_articulo, url_articulo, nombre_articulo, precio_articulo) values ('$product->thumbnailImage', '$product->url', '$product->name', '$product->salePrice')");

        }

        //Seleccionamos los artículos que se encuentran en la base de datos
        $result = mysql_query("SELECT * FROM articulos");
        $to_encode = array();

        //cada fila de la tabla lo almacenamos en la variable $to_encode
        while($row = mysql_fetch_assoc($result)) {
            $to_encode[] = $row;
        }

        //Codificamos los resultados de la base de datos con JSON y lo asignamos a $productsList
        $productsList = json_encode($to_encode);

        echo $productsList;

        return $productsList;


    }
    else{
        echo 'No se encontraron productos...';
    }
}
else{
    $productsDB = array();
    //Seleccionamos los artículos que se encuentran en la base de datos
    $result = mysql_query("SELECT * FROM articulos");
    $to_encode = array();
    
    //cada fila de la tabla lo almacenamos en la variable $to_encode
    while($row = mysql_fetch_assoc($result)) {
        $to_encode[] = $row;
    }
    
    //Codificamos los resultados de la base de datos con JSON y lo asignamos a $productsList
    $productsDB = json_encode($to_encode);
    
    echo $productsDB;
       
    return $productsDB;
	
}
mysql_close($connection); 

?>