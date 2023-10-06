$(document).ready(function() {

    const parameters = {
        url: "https://trouverunlogement.lescrous.fr/photon/api",
        country: "France",
        limit: 18,
        lang: "fr",
        minimumInputLength: 3,
    }

    $('select').select2({
        minimumInputLength: parameters.minimumInputLength,
        width: "100%",
        placeholder: 'Exemple: Rennes',
        dropdownParent: $('#dropdown'),
        ajax: {
            url: parameters.url,
            data: function(params){
                return {
                    q: params.term,
                    limit: 18,
                    lang: parameters.lang,
                    osm_tag: "place:city"
                }
            },
            processResults: function (data) {
                let cities = data.features;
                if (cities.length === 0){
                    return [];
                }
                cities = cities.filter(function (city) {
                    return city.properties.country === parameters.country
                });
                if (cities.length === 0){
                    return [];
                }
                const items = cities.map(function (result){
                    return {
                        id: result.properties.osm_id,
                        text: result.properties.name,
                    }
                });
                return {
                    results: items
                }
            }
        }
    });
});