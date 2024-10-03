//Function to get recipe from Edamam API
function get_recipe(ingredient_list){
    return new Promise(function(resolve, reject) {
        const url_for_api = 'https://api.edamam.com/api/recipes/v2';
        //Could not configure .env to contain these keys, so I obsfucated them l4-l4
        var application_id = "e32accb0";
        var application_keys = "1d0845814c63c169e1982077fb26863b";
        var MIN = ingredient_list.length;
        var MAX = MIN < 10 ? MIN + 2 : MIN + 3;
        var ingr = MIN.toString() + "-" + MAX.toString();
        var q = ingredient_list.join(" ");

        const params = {
            type: 'public',
            q: q,
            app_id: application_id,
            app_key: application_keys,
            ingr: ingr,
            random: false
        };
        const queryString = new URLSearchParams(params).toString();
        fetch(`${url_for_api}?${queryString}`)
        .then(response => response.json())
        .then(data=> {
            const recipes = data.hits.map(hit => {
                const recipe = hit.recipe;
                return {
                    name: recipe.label,
                    url: recipe.url,
                };
            });
            resolve(recipes);
            
        })
        .catch(error => {
            console.error('Error:', error);
            resolve([]);
        });
        
    });
    
}
