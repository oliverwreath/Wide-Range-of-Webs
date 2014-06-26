var fs = require('fs');
var path = require('path');

travel('/Users/oliverwreath/Documents/IM/', function(pathname){
	console.log(pathname);
});


function travel(dir, callback){
	fs.readdirSync(dir).forEach(function(file){
		var pathname = path.join(dir, file);

		if(fs.statSync(pathname).isDirectory()){
			travel(pathname, callback);
		}else{
			callback(pathname);
		}
	});

}
