import { Server } from '@hocuspocus/server'
import * as Y from 'yjs'
import mysql from 'mysql2/promise'

const db = await mysql.createPool({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'bd_requick',
  waitForConnections: true,
  connectionLimit: 10,
})

const server = new Server({
  port: 1234,

  async onConnect({ documentName }) {
    console.log('Cliente conectado:', documentName)
  },

  async onLoadDocument({ documentName }) {
    const projectId = documentName.match(/\d+/)?.[0]

    if (!projectId) {
      return new Y.Doc()
    }

    const [rows] = await db.execute(
      `
      SELECT ydoc
      FROM tb_projeto_documentos
      WHERE id_projeto = ?
      AND tipo = 'escopo_inicial'
      LIMIT 1
      `,  
      [projectId]
    )

    const doc = new Y.Doc()

    if (rows.length > 0 && rows[0].ydoc) {
      Y.applyUpdate(doc, rows[0].ydoc)
    } else {
      console.log('Documento novo')
    }
    return doc
  },

  async onStoreDocument({ document, documentName }) {
    const projectId = documentName.match(/\d+/)?.[0]

    if (!projectId) {
      return
    }

    const update = Y.encodeStateAsUpdate(document)

    await db.execute(
      `
      INSERT INTO tb_projeto_documentos
      (
        id_projeto,
        tipo,
        ydoc
      )
      VALUES
      (
        ?,
        'escopo_inicial',
        ?
      )
      ON DUPLICATE KEY UPDATE
      ydoc = VALUES(ydoc)
      `,
      [projectId, update]
    )
  },
})

server.listen()