var fs = require('fs');

var src = "/Users/oliverwreath/Documents/IM/note.txt";
var dst = "/Users/oliverwreath/Documents/IM/note222.txt";
var rs = fs.createReadStream(src);
var ws = fs.createWriteStream(dst);

rs.on('data', function (chunk) {
    if (ws.write(chunk) === false) {
        rs.pause();
    }
});

rs.on('end', function () {
    ws.end();
});

ws.on('drain', function () {
    rs.resume();
});
