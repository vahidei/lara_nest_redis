import { Observable } from 'rxjs';
import { AppService } from './app.service';
export declare class AppController {
    private readonly appService;
    constructor(appService: AppService);
    getHello(name: string): string;
    testThisShit(data: string): void;
    getObservable(): Observable<number>;
}
