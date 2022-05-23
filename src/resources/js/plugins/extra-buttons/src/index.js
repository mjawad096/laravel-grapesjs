export default (editor, opts = {}) => {
  editor.Panels.addButton('options', [
    {
      id: 'undo',
      className: 'fa fa-undo',
      attributes: {
        title: 'Undo',
      },
      command: e => e.runCommand('core:undo'),
    },
    {
      id: 'redo',
      className: 'fa fa-repeat',
      attributes: {
        title: 'Redo',
      },
      command: e => e.runCommand('core:redo'),
    },
    {
      id: 'canvas-clear',
      className: 'fa fa-trash',
      attributes: {
        title: 'Clear Canvas',
      },
      command: e => {
        if(confirm('Are you sure to clean the canvas?')) {
          e.DomComponents.clear();
          setTimeout(() => localStorage.clear(), 0)
        }
      },
    },
  ]);
};