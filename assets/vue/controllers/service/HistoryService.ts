export default class HistoryService {
    static updateHistory(domain: string, properties: any): URL {
        const url = new URL(domain);

        const extent = properties.properties.extent.join(',');

        url.searchParams.set('extent', extent);
        url.searchParams.set('type', properties.typeLocation);
        if (properties.minPrice){
            url.searchParams.set('min_price', properties.minPrice);
        }
        if (properties.minArea){
            url.searchParams.set('min_area', properties.minArea);
        }
        url.searchParams.set('name', properties.properties.name);

        window.history.pushState({}, '', url.toString());
        return url;
    }

    static update(url: string){
        window.window.
        window.history.pushState({}, '', url);
    }
}
