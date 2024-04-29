const axios = require("axios");
const fs = require("fs");

const image = fs.readFileSync("png-clipart-tomato-tomato-thumbnail.png", {
    encoding: "base64"
});

axios({
    method: "POST",
    url: "https://detect.roboflow.com/bitirme-abrpx/3",
    params: {
        api_key: "ii8ah948PuEkfwBX5ojS",
        confidence: "0.3"
    },
    data: image,
    headers: {
        "Content-Type": "application/x-www-form-urlencoded"
    }
})
.then(function(response) {
    console.log(response.data.predictions);
})
.catch(function(error) {
    console.log(error.message);
});
