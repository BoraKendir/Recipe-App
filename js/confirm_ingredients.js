function confirm_ingredients(element, ingredients) {
    return new Promise((resolve, reject) => {
        var button = document.createElement("button");
        button.innerHTML = "If ingredients are not correct or some are missing, press this button to select them by hand";
        element.appendChild(button);
        var EarlyConfirm = document.createElement("button");
        EarlyConfirm.innerHTML = "Confirm if ingredients are correct";
        element.appendChild(EarlyConfirm);
        EarlyConfirm.addEventListener("click", function() {
            EarlyConfirm.disabled = true;
            EarlyConfirm.style.display = "none";
            button.disabled = true;
            button.style.display = "none";
            resolve(ingredients); // Resolve with an the ingredients that were passed
        });
        button.addEventListener("click", function() {
            var selectedIngredients = [];
            var grid = document.createElement("div");
            grid.className = "grid";
            button.disabled = true;
            const IngredientList = [
                'Beef',
                'Bread',
                'Broccoli',
                'Butter',
                'Carrot',
                'Cheddar Cheese',
                'Chicken',
                'Cucumber',
                'Egg',
                'Eggplant',
                'Fish',
                'Garlic',
                'Lettuce',
                'Mushroom',
                'Onion',
                'Potato',
                'Red Bell Pepper',
                'Stuffed Bell Pepper',
                'Tomato',
                'Zucchini'
            ];
            for (var i = 1; i <= 5; i++) {
                for (var j = 1; j <= 4; j++) {
                    var grid_button = document.createElement("button");
                    grid_button.innerHTML = IngredientList[(i + (j - 1) * 5) - 1];
                    grid_button.addEventListener("click", function() {
                        var index = selectedIngredients.indexOf(this.innerHTML);
                        if (index > -1) {
                            selectedIngredients.splice(index, 1);
                            this.style.backgroundColor = "";
                        } else {
                            selectedIngredients.push(this.innerHTML);
                            this.style.backgroundColor = "#0B5ED7";
                        }
                    });
                    grid.appendChild(grid_button);
                }
            }
            element.appendChild(grid);

            var LateConfirm = document.createElement("button");
            LateConfirm.innerHTML = "Confirm";
            element.appendChild(LateConfirm);
            LateConfirm.addEventListener("click", function() {
                LateConfirm.disabled = true;
                LateConfirm.style.display = "none";
                button.disabled = true;
                button.style.display = "none";
                grid.style.display = "none";
                EarlyConfirm.style.display = "none";
                var ul = document.createElement("ul");
                ul.className = "selectedList";
                var header = document.createElement("h3");
                header.innerHTML = "Selected Ingredients";
                ul.appendChild(header);

                selectedIngredients.forEach(function(ingredient) {
                    var li = document.createElement("li");
                    li.innerHTML = ingredient;
                    ul.appendChild(li);
                });

                element.appendChild(ul);
                resolve(selectedIngredients);
            });
        });
    });
}