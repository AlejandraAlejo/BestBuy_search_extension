$("#send").click(function(){
    var articulo = document.getElementById("product").value;
    chrome.storage.sync.set({ "data" : articulo }, function() {
		if (chrome.runtime.error) {
			console.log("Runtime error.");
		}
	});
    window.location = "result.html";    
});