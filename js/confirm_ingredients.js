//This file contains the function that allows the user to confirm the ingredients that were detected by the model.
// If the user is not satisfied with the detected ingredients, they can select the ingredients manually from a dropdown list.
function confirm_ingredients(element, ingredients) {
    return new Promise((resolve, reject) => {
        var button = document.createElement("button");
        button.className = "btn btn-primary";
        button.id = "ListCreator";
        button.innerHTML = "If ingredients are not correct or some are missing, press this button to select them manually";
        element.appendChild(button);

        var EarlyConfirm = document.createElement("button");
        EarlyConfirm.className = "btn btn-primary";
        EarlyConfirm.id = "EarlyConfirm";
        EarlyConfirm.innerHTML = "Confirm if ingredients are correct";
        element.appendChild(EarlyConfirm);

        EarlyConfirm.addEventListener("click", function() {
            EarlyConfirm.disabled = true;
            EarlyConfirm.style.display = "none";
            button.disabled = true;
            button.style.display = "none";
            console.log(ingredients);
            var confirmationMessage = document.createElement("h2");
            confirmationMessage.innerHTML = "You confirmed the list above";
            element.appendChild(confirmationMessage);
            resolve(ingredients); // Resolve with the ingredients that were passed
        });

        button.addEventListener("click", function() {
            button.disabled = true;
            EarlyConfirm.disabled = true;
            
            const IngredientList = [   
                'Aubergine',
                'Bell-Pepper',
                'Bread',
                'Broccoli',
                'Cabbage',
                'Carrot',
                'Cauliflower',
                'Cheese',
                'Chicken',
                'Cucumber',
                'Egg',
                'Fish',
                'Garlic',
                'Meat',
                'Mushroom',
                'Onion',
                'Pepper',
                'Potato',
                'Tomato'
            ];
            const resultContainer = document.createElement("div");
            resultContainer.className = "result-container";

            const dropdown = document.createElement("select");
            dropdown.addEventListener("change", function() {
                const selectedIngredient = dropdown.value;
                const selectedBox = document.getElementById("selectedBox");

                let existingItem = Array.from(selectedBox.children).find(child => child.dataset.ingredient === selectedIngredient);

                let selectAmount = prompt("Enter the amount for " + selectedIngredient);

                if (selectAmount === null) {
                    return; // If cancel is pressed, do nothing
                }

                if (!selectAmount || isNaN(selectAmount) || selectAmount <= 0) {
                    selectAmount = 1; // Default to 1 if input is invalid or empty
                }

                if (existingItem) {
                    existingItem.innerHTML = `${selectedIngredient},${selectAmount}`;
                } else {
                    const itemDiv = document.createElement("div");
                    itemDiv.dataset.ingredient = selectedIngredient;
                    itemDiv.innerHTML = `${selectedIngredient}: ${selectAmount}`;
                    selectedBox.appendChild(itemDiv);
                }
            });

            IngredientList.forEach(ingredient => {
                const option = document.createElement("option");
                option.text = ingredient;
                option.value = ingredient;
                dropdown.add(option);
            });
            resultContainer.appendChild(dropdown);

            const selectedBox = document.createElement("div");
            selectedBox.id = "selectedBox";

            const resetButton = document.createElement("button");
            resetButton.innerHTML = "Reset";
            resetButton.addEventListener("click", function() {
                selectedBox.innerHTML = "";
            });

            resultContainer.appendChild(resetButton);
            resultContainer.appendChild(selectedBox);
            element.appendChild(resultContainer);

            
            const finalConfirmButton = document.createElement("button");
            finalConfirmButton.className = "btn btn-primary";
            finalConfirmButton.id = "finalConfirmButton";
            finalConfirmButton.innerHTML = "Confirm Selected Ingredients";
            finalConfirmButton.addEventListener("click", function() {
                const selectedIngredients = Array.from(selectedBox.children).map(child => {
                    const [ingredient, amount] = child.innerHTML.split(': ');
                    
                    return { ingredient, amount };
                });
                if (selectedIngredients.length > 0) {
                    console.log(selectedIngredients);
                    resolve(selectedIngredients);
                } else {
                    alert("No ingredients selected.");
                }
            });

            element.appendChild(finalConfirmButton);
        });
    });
}
