# __friends__

### ___A simple live chat web app.___

<br>

### __TL;DR;__

- Really simple application based on PHP (it uses the [Ratchet](https://github.com/ratchetphp/Ratchet) library)
- It opens a web socket on `7777` port (on the same host which runs the application)
- The server is always listening the socket.
- Every chat has an own `chat room` (security purpose) so only receiver/s can read the message.
- Message are encrypted (_work in progess_ ⚠️)