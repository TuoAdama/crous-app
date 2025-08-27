export default class TypeLocationService {
    static translateType(type: string): string {
        switch (type) {
            case 'alone':
                return 'Individuel';
            case 'house_sharing':
                return 'Colocation';
            case 'couple':
                return 'Couple';
            default:
                return type;
        }
    }
}
