<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe</title>
    <link href="../css/recipe.css" rel="stylesheet"/>
    <script src="https://cdn.roboflow.com/0.2.26/roboflow.js"></script>
    <script src="../js/confirm_ingredients.js"></script>
    <script src="../js/get_recipe.js"></script>
    <link href="https://fonts.cdnfonts.com/css/chirp-2" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php
    session_start();
    
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $userId = $_SESSION["user_id"];
    } else {
        echo "You are not logged in. Redirecting you to the login page...";
        header("refresh:3;url=../index.html");
        exit;
    }
    ?>

    <h1>First Step: Upload an Image</h1>
    <div class="flex-container">
        
        <div class="image-container">
            <input type="file" accept="image/*" onchange="previewImageAndPredict(event)">
            <div class="image-preview"></div>
        </div>

        <div class="prediction-container"></div>
        <div class="recipe-container"></div>

    </div>
    

    <footer>
        <div class="footer-btn">
            <a class="btn btn-primary" href="contact.html">Contact</a>  
        </div>
        <div class="footer-btn">
            <a class="btn btn-primary" href="about.html">About the App</a>  
        </div>
        <div class="footer-btn">
            <a class="btn btn-primary" href="homepage.php">Homepage</a>  
        </div>
        <div class="footer-btn">
            <form method="get" action="logout.php">
                <input type="submit" value="Logout" class="btn btn-primary" />
            </form>
        </div>
    </footer>

    <script>

    let model; // Global variable to hold the loaded model
    // Load the model
    roboflow.auth({
        publishable_key: "rf_DXQGN6XTTgPmbi4MEqiAvtUtj3b2"
    }).load({
        model: "bitirme-abrpx",
        version: 8 // <--- YOUR VERSION NUMBER
    }).then(function(loadedModel) {
        model = loadedModel;
        model.configure({
            threshold: 0.3,
            overlap: 0.3,
            max_objects: 20
        });
    });
    // Function to preview the image and make predictions
    function previewImageAndPredict(event) {
        var userID = <?php echo $userId; ?>;
        const preview = document.querySelector('.image-preview');
        preview.innerHTML = '';

        const file = event.target.files[0];
        const reader = new FileReader();
        // If a file is selected, read the file and make predictions
        reader.onload = function(event) {
            const image = new Image();
            image.onload = function() {
                // Loaded model is used to make predictions
                model.detect(image).then(function(predictions) {
                    //Create a map to store the class and count of each class
                    const classCountMap = new Map();
                    predictions.forEach(prediction => {
                        const className = prediction.class;
                        if (classCountMap.has(className)) {
                            classCountMap.set(className, classCountMap.get(className) + 1);
                        } else {
                            classCountMap.set(className, 1);
                        }
                    });
                    // Display the predictions
                    const predictionContainer = document.querySelector('.prediction-container');
                    predictionContainer.innerHTML = '';
                    predictionContainer.innerHTML += '<h2>Ingredients found in this image:</h2>';
                    const predictionList = document.createElement('div');
                    predictionList.classList.add('prediction-list');
                    predictionContainer.appendChild(predictionList);
                    const unorderedList = document.createElement('ul');
                    classCountMap.forEach((count, className) => {
                        const listItem = document.createElement('li');
                        listItem.textContent = className + ': ' + count ;
                        unorderedList.appendChild(listItem);
                    });
                    predictionList.appendChild(unorderedList);
                    predictionContainer.innerHTML += '<h1>Second Step: Confirm the Ingredients</h1>';
                    //User is asked to confirm the ingredients or deny and upload their own list
                    const ingredientsArray = Array.from(classCountMap.entries()).map(([ingredient, amount]) => ({ ingredient, amount }));
                    confirm_ingredients(predictionContainer, ingredientsArray).then(function(selectedIngredients) {
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
                        get_recipe(selectedIngredientsArray).then(function(recipeList) {
                            const recipeContainer = document.querySelector('.recipe-container');
                            recipeContainer.innerHTML = '';
                            //Pagination logic starts here
                            if (Array.isArray(recipeList)) {
                                let recipeIndex = 0; // Variable to keep track of the current recipe index
                                function displayRecipes(recipeList) {
                                    recipeContainer.innerHTML = '';
                                    for (let i = recipeIndex; i < recipeIndex + 5 && i < recipeList.length; i++) {
                                        const recipe = recipeList[i];
                                        const recipeDiv = document.createElement('div');
                                        recipeDiv.classList.add('indiv-recipe');
                                        recipeDiv.innerHTML = `
                                            <a id="recipe_url_${i}" href="${recipe.url}" target="_blank">
                                                <h3 id="recipe_name_${i}">${recipe.name}</h3>
                                            </a>
                                            <form onsubmit="return add_recipe_ajax(event, '${recipe.url}', '${recipe.name}')">
                                                <button type="submit" class="btn btn-primary">Add</button>
                                            </form>
                                        `;
                                        console.log(recipe.url,recipe.url, userID);
                                        recipeContainer.appendChild(recipeDiv);
                                        
                                    }
                                    if (recipeIndex > 0) {
                                        const backButton = document.createElement('button');
                                        backButton.textContent = 'Back';
                                        backButton.className = "page-link";
                                        backButton.id = 'back-btn';
                                        backButton.addEventListener('click', () => {
                                            recipeIndex -= 5;
                                            displayRecipes(recipeList);
                                        });
                                        recipeContainer.appendChild(backButton);
                                    }
                                    if (recipeIndex + 5 < recipeList.length) {
                                        const nextButton = document.createElement('button');
                                        nextButton.textContent = 'Next';
                                        nextButton.id = 'next-btn';
                                        nextButton.className = "page-link";
                                        nextButton.addEventListener('click', () => {
                                            recipeIndex += 5;
                                            displayRecipes(recipeList);
                                        });
                                        recipeContainer.appendChild(nextButton);
                                    }
                                }
                                displayRecipes(recipeList);
                            } else {
                                console.error('Recipe is not an array:', recipeList);
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
    // Function to add a recipe to the user's profile
    function add_recipe_ajax(event, recipeUrl, recipeName){
        event.preventDefault();
        var data = new FormData();
        data.append('recipe_url', recipeUrl);
        data.append('recipe_name', recipeName);
        data.append('user_id', <?php echo $userId; ?>);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'add_recipe_toProfile.php');
        xhr.onload = function() {
            if (xhr.status === 200) {
                alert('Recipe added successfully!');
            } else {
                alert('Error adding recipe: ' + xhr.responseText);
            }
        };
        xhr.send(data);

        return false;
    }
    </script>
</body>
</html>
