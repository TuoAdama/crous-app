type URLParams = {
    q: string,
    type: string,
    budgetMax?: number,
    surface?: number,
};

export default class HistoryService {
    static updateHistory(domain: string, properties: URLParams): URL {
        const url = new URL(domain);
        url.searchParams.set('q', properties.q);
        if (properties.budgetMax) {
            url.searchParams.set('price_min', properties.budgetMax.toString());
        }
        if (properties.surface){
            url.searchParams.set('area', properties.surface.toString());
        }
        if (properties.type.length > 0) {
            url.searchParams.set('type', properties.type);
        }
        window.history.pushState({}, '', url.toString());
        return url;
    }
}
