document.body.onload = function() {
	$.getJSON('http://localhost/buscador_bestbuy_extension/bestbuy_search.php', function(data) {
        $.each(data, function(key, val) {
            $('#tabla-resultados-DB').append('<tr><td><img src="' + val.imagen_articulo  + '"/></td><td><a href="' + val.url_articulo + '"target = "_blank">' + val.nombre_articulo + '</a></td><td>$' + val.precio_articulo + ' US</td></tr>');
        });
    });
}