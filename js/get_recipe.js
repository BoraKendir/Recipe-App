function get_recipe(ingredient_list){
    return new Promise(function(resolve, reject) {
        const url_for_api = 'https://api.edamam.com/api/recipes/v2';
        var application_id = "e32a863b";
        var application_keys = "1d0845814c63c169e1982077fb26ccb0";
        var MIN = ingredient_list.length;
        var MAX = MIN < 10 ? MIN + 2 : MIN + 3;
        var ingr = MIN.toString() + "-" + MAX.toString();
        console.log(ingr);
        var q = ingredient_list.join(" ");
        console.log(q);

        const params = {
            type: 'public',
            q: q,
            app_id: application_id,
            app_key: application_keys,
            ingr: ingr,
            random: true
        };
        const queryString = new URLSearchParams(params).toString();
        console.log(`${url_for_api}?${queryString}`);
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
            console.log(recipes);
            resolve(recipes);
            
        })
        .catch(error => {
            console.error('Error:', error);
            resolve([]);
        });
        
    });
    
}
