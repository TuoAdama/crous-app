export default interface User {
    id: number;
    username: string;
    email: string;
    number: string;
    emailIsVerified: boolean;
    numberVerified: boolean;
    notifyByEmail: boolean;
    notifyByNumber: boolean;
}