import * as Y from 'https://esm.sh/yjs'
import { HocuspocusProvider } from 'https://esm.sh/@hocuspocus/provider'
import { Editor } from 'https://esm.sh/@tiptap/core'
import StarterKit from 'https://esm.sh/@tiptap/starter-kit'
import Collaboration from 'https://esm.sh/@tiptap/extension-collaboration'
lucide.createIcons()

const ydoc = new Y.Doc()

const provider = new HocuspocusProvider({
    url: 'ws://localhost:1234',
    name: 'documento-teste',
    document: ydoc,
})

provider.on('status', event => {
    console.log('Status:', event.status)
})

const editor = new Editor({
    element: document.querySelector('#editor'),
    extensions: [
        StarterKit,
        Collaboration.configure({
            document: ydoc,
        }),
    ],
})

const commands = {
  bold: () => editor.chain().focus().toggleBold().run(),
  italic: () => editor.chain().focus().toggleItalic().run(),
  strike: () => editor.chain().focus().toggleStrike().run(),

  paragraph: () => editor.chain().focus().setParagraph().run(),
  h1: () => editor.chain().focus().toggleHeading({ level: 1 }).run(),
  h2: () => editor.chain().focus().toggleHeading({ level: 2 }).run(),

  bulletList: () => editor.chain().focus().toggleBulletList().run(),
  orderedList: () => editor.chain().focus().toggleOrderedList().run(),
}

/* ===============================
   EVENTOS DOS BOTÕES
================================ */

document.querySelectorAll('#toolbar button').forEach(button => {
  button.addEventListener('click', () => {
    const cmd = button.getAttribute('data-cmd')
    commands[cmd]()
    updateToolbar()
  })
})

/* ===============================
   ATUALIZAR ESTADO (ativo)
================================ */

function updateToolbar() {
  document.querySelectorAll('#toolbar button').forEach(button => {
    const cmd = button.getAttribute('data-cmd')

    let isActive = false

    switch (cmd) {
      case 'bold':
        isActive = editor.isActive('bold')
        break
      case 'italic':
        isActive = editor.isActive('italic')
        break
      case 'strike':
        isActive = editor.isActive('strike')
        break
      case 'paragraph':
        isActive = editor.isActive('paragraph')
        break
      case 'h1':
        isActive = editor.isActive('heading', { level: 1 })
        break
      case 'h2':
        isActive = editor.isActive('heading', { level: 2 })
        break
      case 'bulletList':
        isActive = editor.isActive('bulletList')
        break
      case 'orderedList':
        isActive = editor.isActive('orderedList')
        break
    }

    button.classList.toggle('active', isActive)
  })
}

/* ===============================
   ESCUTAR MUDANÇAS NO EDITOR
================================ */

editor.on('update', () => {
  updateToolbar()
})

editor.on('selectionUpdate', () => {
  updateToolbar()
})