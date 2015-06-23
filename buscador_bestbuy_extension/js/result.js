document.body.onload = function() {
	chrome.storage.sync.get("data", function(items) {
		if (!chrome.runtime.error) {
            $(".loading").show();
            var articulo = items.data;
			/* call the php that has the php array which is json_encoded */
            $.getJSON('http://localhost/buscador_bestbuy_extension/bestbuy_search.php', {articulo}, function(data) {
                /* data will hold the php array as a javascript object */
                $.each(data, function(key, val) {
                    $('#tabla-resultados').append('<tr><td><img src="' + val.imagen_articulo +'"/></td><td><a href="' + val.url_articulo + '"target = "_blank">' + val.nombre_articulo + '</a></td><td>$' +  val.precio_articulo + ' US</td></tr>');
                });
                $(".loading").hide(); 
            });
		}
        
	});
}