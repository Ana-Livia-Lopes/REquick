import * as Y from 'https://esm.sh/yjs'
import { HocuspocusProvider } from 'https://esm.sh/@hocuspocus/provider'
import { Editor } from 'https://esm.sh/@tiptap/core'
import StarterKit from 'https://esm.sh/@tiptap/starter-kit'
import Collaboration from 'https://esm.sh/@tiptap/extension-collaboration'

lucide.createIcons()

const params = new URLSearchParams(location.search)
const idProjeto = params.get('id')

const ydoc = new Y.Doc()

const provider = new HocuspocusProvider({
  url: 'ws://localhost:1234',
  name: `projeto-${idProjeto}`,
  document: ydoc,
})

provider.on('status', event => {
  console.log('Status:', event.status)
})

const editor = new Editor({
  element: document.querySelector('#editor'),

  extensions: [
    StarterKit.configure({
      history: false,
    }),

    Collaboration.configure({
      document: ydoc,
    }),
  ],
})

const commands = {
  bold: () => editor.chain().focus().toggleBold().run(),
  italic: () => editor.chain().focus().toggleItalic().run(),
  strike: () => editor.chain().focus().toggleStrike().run(),
  h1: () => editor.chain().focus().toggleHeading({ level: 1 }).run(),
  h2: () => editor.chain().focus().toggleHeading({ level: 2 }).run(),
  bulletList: () => editor.chain().focus().toggleBulletList().run(),
  orderedList: () => editor.chain().focus().toggleOrderedList().run(),
}

document.querySelectorAll('#toolbar button').forEach(button => {
  button.addEventListener('click', () => {
    const cmd = button.dataset.cmd

    if (commands[cmd]) {
      commands[cmd]()
      updateToolbar()
    }
  })
})

function updateToolbar() {
  document.querySelectorAll('#toolbar button').forEach(button => {
    const cmd = button.dataset.cmd

    let active = false

    switch (cmd) {
      case 'bold':
        active = editor.isActive('bold')
        break

      case 'italic':
        active = editor.isActive('italic')
        break

      case 'strike':
        active = editor.isActive('strike')
        break

      case 'h1':
        active = editor.isActive('heading', { level: 1 })
        break

      case 'h2':
        active = editor.isActive('heading', { level: 2 })
        break

      case 'bulletList':
        active = editor.isActive('bulletList')
        break

      case 'orderedList':
        active = editor.isActive('orderedList')
        break
    }

    button.classList.toggle('active', active)
  })
}

editor.on('update', updateToolbar)
editor.on('selectionUpdate', updateToolbar)