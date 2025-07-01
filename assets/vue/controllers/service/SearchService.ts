export default class SearchService {
    static async findHousing(apiUrl: string, requestBody: Object) {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(requestBody),
        });
        if (!response.ok) {
            console.error('Error fetching housing data:', response.statusText);
            return [];
        }
        return await response.json();
    }

    static async search(apiUrl: string): Promise<Object> {
        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        if (!response.ok) {
            return null;
        }
        return await response.json();
    }
}
