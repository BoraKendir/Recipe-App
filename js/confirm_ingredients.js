function confirm_ingredients(element) {
    var button = document.createElement("button");
    button.innerHTML = "If ingredients are not correct or some are missed, press this button to select them by hand";
    element.appendChild(button);
    var EarlyConfirm = document.createElement("button");
    EarlyConfirm.innerHTML = "Confirm if ingredients are correct";
    element.appendChild(EarlyConfirm);
    EarlyConfirm.addEventListener("click", function() {
        
    });
    button.addEventListener("click", function() {
        var selectedIngredients = [];
        var grid = document.createElement("div");
        grid.className = "grid";
        const IngredientList = [
            'Fish',
            'Broccoli',
            'Tomato',
            'Stuffed Bell Pepper',
            'Bread',
            'Carrot',
            'Cheddar Cheese',
            'Zucchini',
            'Red Bell Pepper',
            'Beef Cubes',
            'Garlic',
            'Onion',
            'Lettuce',
            'Mushroom',
            'Potato',
            'Eggplant',
            'Cucumber',
            'Chicken',
            'Egg',
            'Butter'
        ];
        for (var i = 1; i <= 5; i++) {
            for (var j = 1; j <= 4; j++) {
                var grid_button = document.createElement("button");
                grid_button.innerHTML = IngredientList[(i + (j - 1) * 5) - 1];
                grid_button.addEventListener("click", function() {
                    selectedIngredients.push(this.innerHTML);
                    this.style.backgroundColor = "#0B5ED7";
                });
                grid.appendChild(grid_button);
            }
        }
        element.appendChild(grid);

        var LateConfirm = document.createElement("button");
        LateConfirm.innerHTML = "Confirm";
        element.appendChild(LateConfirm);
        LateConfirm.addEventListener("click", function() {
            console.log(selectedIngredients);
        });
    });
    
}