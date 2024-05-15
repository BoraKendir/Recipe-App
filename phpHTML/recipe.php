<html>
<head>
    <title>Recipe</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/recipe.css" rel="stylesheet"/>
    <script src="https://cdn.roboflow.com/0.2.26/roboflow.js"></script>
    <script src="../js/confirm_2.js"></script>
    <script src="../js/get_recipe.js"></script>
    <link href="https://fonts.cdnfonts.com/css/chirp-2" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<?php
    session_start();
    
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
    } else {
        echo "You are not logged in. Redirecting you to the login page...";
        header("refresh:3;url=../firstpage.html");
        exit;
    }
?>

<body>
    <h1>First Step: Upload a Picture</h1>
    <div class="image-container">
        <div>
            <input type="file" accept="image/*" onchange="previewImageAndPredict(event)">
        </div>
        <div class="image-preview"></div>
    </div>

    <div class="prediction-container">
    </div>
    <div class="recipe-container">
        
    </div>
    <footer>
        <div class="footer-btn">
            <a class="btn btn-primary" href="contact.html">Contact</a>  
        </div>
        <div class="footer-btn">
            <a class="btn btn-primary" href="about.html">About the App</a>  
        </div>
        <div class="footer-btn">
            <form method="get" action="logout.php">
                <input type="submit" value="Logout" class="btn btn-primary" />
            </form>
        </div>
    </footer>
</body>

<script>
let model; // Global variable to hold the loaded model
// Load the model
roboflow.auth({
    publishable_key: "rf_DXQGN6XTTgPmbi4MEqiAvtUtj3b2"
}).load({
    model: "bitirme-abrpx",
    version: 2 // <--- YOUR VERSION NUMBER
}).then(function(loadedModel) {
    model = loadedModel;
    model.configure({
        threshold: 0.3,
        overlap: 0.5,
        max_objects: 20
    });
});

function previewImageAndPredict(event) {
    const preview = document.querySelector('.image-preview');
    preview.innerHTML = '';

    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(event) {
        const image = new Image();
        image.onload = function() {

            model.detect(image).then(function(predictions) {
                const classCountMap = new Map();
                predictions.forEach(prediction => {
                    const className = prediction.class;
                    if (classCountMap.has(className)) {
                        classCountMap.set(className, classCountMap.get(className) + 1);
                    } else {
                        classCountMap.set(className, 1);
                    }
                });
                const predictionContainer = document.querySelector('.prediction-container');
                predictionContainer.innerHTML = '';
                predictionContainer.innerHTML += '<h2>Ingredients found in this picture:</h2>';
                const predictionList = document.createElement('div');
                predictionList.classList.add('prediction-list');
                predictionContainer.appendChild(predictionList);
                const unorderedList = document.createElement('ul');
                classCountMap.forEach((count, className) => {
                    const listItem = document.createElement('li');
                    listItem.textContent = className + ': ' + count ;
                    unorderedList.appendChild(listItem);
                });
                const ingredientsArray = Array.from(classCountMap.entries()).map(([ingredient, amount]) => ({ ingredient, amount }));
                predictionList.appendChild(unorderedList);
                predictionContainer.innerHTML += '<h1>Second Step: Confirm the Ingredients</h1>'
                confirm_2(predictionContainer,ingredientsArray).then(function(selectedIngredients) {
                    predictionContainer.innerHTML += '<h1>Third Step: Get a Recipe</h1>';
                    // Create an array called selectedIngredients
                    var selectedIngredientsArray = [];
                    if (selectedIngredients) {
                        selectedIngredients.forEach(item => {
                            if (item.ingredient) { // Check if ingredient is defined
                                selectedIngredientsArray.push(item.ingredient);
                            }
                        });
                    }
                    // Use the selectedIngredientsMap to get the recipe
                    console.log('from confirm_2 '+selectedIngredients);
                    console.log('turned into array '+selectedIngredientsArray);
                    get_recipe(selectedIngredientsArray).then(function(recipeList) {
                        console.log('Recipes:', recipeList);
                        const recipeContainer = document.querySelector('.recipe-container');
                        recipeContainer.innerHTML = '';
                        if (Array.isArray(recipeList)) {
                            let recipeIndex = 0; // Global variable to keep track of the current recipe index

                            function displayRecipes(recipeList) {
                                const recipeContainer = document.querySelector('.recipe-container');
                                recipeContainer.innerHTML = '';

                                for (let i = recipeIndex; i < recipeIndex + 5 && i < recipeList.length; i++) {
                                    const recipe = recipeList[i];
                                    const recipeDiv = document.createElement('div');
                                    recipeDiv.classList.add('indiv-recipe');
                                    recipeDiv.innerHTML = `
                                        <a href="${recipe.url}" target="_blank">
                                            <h3>${recipe.name}</h3>
                                        </a>
                                    `;
                                    recipeContainer.appendChild(recipeDiv);
                                }

                                if (recipeIndex > 0) {
                                    const backButton = document.createElement('button');
                                    backButton.textContent = 'Back';
                                    backButton.addEventListener('click', () => {
                                        recipeIndex -= 5;
                                        displayRecipes(recipeList);
                                    });
                                    recipeContainer.appendChild(backButton);
                                }

                                if (recipeIndex + 5 < recipeList.length) {
                                    const nextButton = document.createElement('button');
                                    nextButton.textContent = 'Next';
                                    nextButton.addEventListener('click', () => {
                                        recipeIndex += 5;
                                        displayRecipes(recipeList);
                                    });
                                    recipeContainer.appendChild(nextButton);
                                }
                            }

                            displayRecipes(recipeList);
                        }
                        else {
                            console.error('Recipe is not an array:');
                        }
                    });
                });
                
            });
        };
        image.src = event.target.result;
        preview.appendChild(image);
    };
    reader.readAsDataURL(file);
}
</script>
</html>
