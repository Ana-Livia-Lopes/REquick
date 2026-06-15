# Servidor WebSocket (Hocuspocus + Yjs + MySQL)

Este projeto utiliza um servidor WebSocket baseado em **Hocuspocus** com suporte ao **Yjs** para colaboração em tempo real e integração com **MySQL** para persistência de dados.

---

## Requisitos

Antes de começar, você precisa ter instalado:

- Node.js (versão recomendada: 18+)
- npm (já vem com o Node.js)
- MySQL rodando localmente ou em um servidor remoto

---

## Instalação

No diretório do projeto, instale as dependências necessárias:

```bash
npm install @hocuspocus/server yjs mysql2
```

## Executando o servidor

Após a instalação, inicie o servidor com o comando:

```bash
node server.js
