const axios = require("axios");
const fs = require("fs");

function predictImage() {
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
        const predictions = response.data.predictions;

        const uniqueClasses = [...new Set(predictions.map(prediction => prediction.class))];
        console.log(uniqueClasses);
    })
    .catch(function(error) {
        console.log(error.message);
    });
}

predictImage();
