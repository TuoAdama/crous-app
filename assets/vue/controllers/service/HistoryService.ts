type URLParams = {
    q: string,
    typeLocation: string,
    minPrice?: number,
    minArea?: number,
};

export default class HistoryService {
    static updateHistory(domain: string, properties: URLParams): URL {
        const url = new URL(domain);
        url.searchParams.set('q', properties.q);
        if (properties.minPrice) {
            url.searchParams.set('price_min', properties.minPrice.toString());
        }
        if (properties.minArea){
            url.searchParams.set('area', properties.minArea.toString());
        }
        if (properties.typeLocation.length > 0) {
            url.searchParams.set('type', properties.typeLocation);
        }
        window.history.pushState({}, '', url.toString());
        return url;
    }

    static update(url: string){
        window.window.
        window.history.pushState({}, '', url);
    }
}
