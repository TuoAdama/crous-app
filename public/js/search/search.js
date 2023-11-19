$(document).ready(function () {
    const criteria = {};
    const parameters = {
        url: "https://trouverunlogement.lescrous.fr/photon/api?limit=18&osm_tag=amenity%3Acollege&osm_tag=amenity%3Alibrary&osm_tag=amenity%3Aschool&osm_tag=amenity%3Auniversity&osm_tag=place%3Acountry&osm_tag=place%3Aregion&osm_tag=place%3Astate&osm_tag=place%3Acity&osm_tag=place%3Atown&osm_tag=place%3Avillage&osm_tag=place%3Ahouse&osm_tag=landuse%3Aresidential",
        country: "France",
        limit: 18,
        lang: "fr",
        minimumInputLength: 3,
    }

    $('select').select2({
        minimumInputLength: parameters.minimumInputLength,
        width: "100%",
        placeholder: "Exemple: Rennes, Résidence ou lieu d'étude",
        dropdownParent: $('#dropdown'),
        ajax: {
            url: parameters.url,
            data: function (params) {
                return {
                    q: params.term
                }
            },
            processResults: function (data) {
                let cities = data.features;
                if (cities.length === 0) {
                    return [];
                }
                cities = cities.filter(function (city) {
                    return city.properties.country === parameters.country
                });
                if (cities.length === 0) {
                    return [];
                }
                const items = cities.map(function (result) {
                    return {
                        id: result.properties.osm_id,
                        text: result.properties.name,
                        results: result,
                    }
                });
                return {
                    results: items
                }
            }
        }
    });

    $("#location").on("select2:select", function (e) {
        criteria['location'] = e.params.data.results;
    });

    $("#criteria-form").on('submit', function (e) {
        e.preventDefault();
        criteria['type'] = [];
        $('input[name="type[]"]:checked').each(function () {
            criteria['type'].push($(this).val())
        });
        criteria['price'] = $("input[name='price']").val();
        let headers = new Headers();
        headers.append("Content-Type", "application/json");
        headers.append("Accept", "application/json");
        $('#submit-btn').prop("disabled",true);
        fetch('/api/search', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(criteria)
        }).then(res => {
            if (res.status === 200){
                return res.json();
            }
            throw new Error(JSON.stringify(res.json()))
        })
        .then(res => {
            alert("Opération effectuée avec succès. Status: ")
        })
        .catch((error) => {
            alert(error);
        })
        .finally(() => {
            $('#submit-btn').prop("disabled",false);
        })
    })

});