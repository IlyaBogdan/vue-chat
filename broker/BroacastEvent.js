
var evt = new CustomEvent("MyEventType", {detail: "Any Object Here"});
window.dispatchEvent(evt);

import EventEmitter from 'node:events';

class Broadcast extends EventEmitter {
    
}

const myEmitter = new MyEmitter();
// Only do this once so we don't loop forever
myEmitter.once('newListener', (event, listener) => {
  if (event === 'event') {
    // Insert a new listener in front
    myEmitter.on('event', () => {
      console.log('B');
    });
  }
});
myEmitter.on('event', () => {
  console.log('A');
});
myEmitter.emit('event');