import { Server } from '@hocuspocus/server'

const server = new Server({
  port: 1234,

  onConnect() {
    console.log('Cliente conectado')
  },

  onDisconnect() {
    console.log('Cliente desconectado')
  },
})

server.listen()

console.log('Servidor rodando em ws://localhost:1234')