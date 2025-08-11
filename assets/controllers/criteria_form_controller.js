import {Controller} from "@hotwired/stimulus";

export default class extends Controller {

    static targets = ['addressHidden', 'address', 'submitBtn']

    connect() {
        this.initializeSelect2();
    }

    initializeSelect2() {
        // Configuration des paramètres de recherche
        const parameters = {
            url: "https://trouverunlogement.lescrous.fr/photon/api?limit=18&osm_tag=amenity%3Acollege&osm_tag=amenity%3Alibrary&osm_tag=amenity%3Aschool&osm_tag=amenity%3Auniversity&osm_tag=place%3Acountry&osm_tag=place%3Aregion&osm_tag=place%3Astate&osm_tag=place%3Acity&osm_tag=place%3Atown&osm_tag=place%3Avillage&osm_tag=place%3Ahouse&osm_tag=landuse%3Aresidential",
            country: "France",
            limit: 18,
            lang: "fr",
            minimumInputLength: 3,
        }

        // Initialisation de Select2 avec configuration moderne
        $('select').select2({
            minimumInputLength: parameters.minimumInputLength,
            width: "100%",
            placeholder: "Rechercher une ville...",
            allowClear: true,
            language: {
                inputTooShort: function() {
                    return "Veuillez saisir au moins 3 caractères";
                },
                noResults: function() {
                    return "Aucun résultat trouvé";
                },
                searching: function() {
                    return "Recherche en cours...";
                }
            },
            ajax: {
                url: parameters.url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    }
                },
                processResults: function (data) {
                    let cities = data.features;
                    if (cities.length === 0) {
                        return { results: [] };
                    }
                    
                    // Filtrer les villes françaises
                    cities = cities.filter(function (city) {
                        return city.properties.country === parameters.country
                    });
                    
                    if (cities.length === 0) {
                        return { results: [] };
                    }
                    
                    const items = cities.map(function (result) {
                        const postcode = result.properties.postcode;
                        const name = result.properties.name + (
                            postcode ? ` (${result.properties.postcode})` : ''
                        );
                        return {
                            id: result.properties.osm_id,
                            text: name,
                            results: result,
                        }
                    });
                    
                    return {
                        results: items
                    }
                },
                cache: true
            }
        });

        // Gestion de la sélection d'une ville
        $(".address-select").on("select2:select", (e) => {
            const selectedData = e.params.data;
            $('.location').val(JSON.stringify(selectedData.results));
            
            $(e.target).closest('.form-group').addClass('has-selection');
            
        });

        // Gestion de la suppression de la sélection
        $(".address-select").on("select2:clear", (e) => {
            $('.location').val('');
            $(e.target).closest('.form-group').removeClass('has-selection');
        });
    }
}