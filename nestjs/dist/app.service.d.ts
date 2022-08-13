import { ClientProxy } from '@nestjs/microservices';
export declare class AppService {
    private client;
    constructor(client: ClientProxy);
    getHello(): string;
    sendToLaravel(data: any): void;
}
