import { Controller } from '@nestjs/common';
import { EventPattern, MessagePattern } from '@nestjs/microservices';
import { from, Observable } from 'rxjs';
import { AppService } from './app.service';

@Controller()
export class AppController {
  constructor(private readonly appService: AppService) {}

  getHello() {
    return 'aaaa';
  }

  @MessagePattern('test-*')
  getHello1(name: string): string {
    console.log('safsafa');
    return `Hello ${name}!`;
  }

  @EventPattern('test-channel')
  testThisShit(data: string): void {
    console.log(data);
    this.appService.sendToLaravel(data);
  }

  @MessagePattern({ cmd: 'observable' })
  getObservable(): Observable<number> {
    return from([1, 2, 3]);
  }
}
