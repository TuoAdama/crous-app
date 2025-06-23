
// Create a custom type for the properties array
type HouseProperties = {
    extent: Array<number>;
};

export default class SearchService {
    static async findHousing(data: { properties: HouseProperties, type?: string, minPrice?: number, maxPrice?: number, minArea?: number, maxArea?: number }) {
        return fetch('https://trouverunlogement.lescrous.fr/api/fr/search/37', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(
                {
                    "idTool": 37,
                    "need_aggregation": true,
                    "page": 1,
                    "residence": null,
                    "sector": null,
                    "precision": 6,
                    "occupationModes": [],
                    "equipment": [],
                    "price": {
                        "min": data.minPrice ?? 0,
                        "max": data.maxPrice ?? 300000
                    },
                    "location": [
                        {
                            "lon": data.properties.extent[0],
                            "lat": data.properties.extent[1]
                        },
                        {
                            "lon": data.properties.extent[2],
                            "lat": data.properties.extent[3]
                        }
                    ]
                }
            ),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
            throw error;
        });
    }
}
