import { Inject, Injectable } from '@nestjs/common';
import { ClientProxy } from '@nestjs/microservices';

@Injectable()
export class AppService {
  constructor(@Inject('GREETING_SERVICE') private client: ClientProxy) {}

  getHello(): string {
    return '<h2>Hello World!</h2>';
  }

  sendToLaravel(data) {
    console.log('send to laravel executed', data);
    const send = this.client.emit('test-channel-laravel', data);
    console.log(send);
  }
}
