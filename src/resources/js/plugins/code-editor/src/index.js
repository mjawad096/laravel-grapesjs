export default (editor, opts = {}) => {
  const options = {
    btn_icon: 'fa fa-edit',
    btn_title: 'Edit code.',
    model: {
      title: 'Edit code',
      message: 'Code chagnes Applied.',
      btn_text: 'Save',
    },
    ...opts
  };

  let stylePrefix = editor.getConfig().stylePrefix;
  let modal = editor.Modal;
  let codeViewer = editor.CodeManager.getViewer('CodeMirror').clone();

  let container = document.createElement('div');
  let btnEdit = document.createElement('button');

  codeViewer.set({
    codeName: 'htmlmixed',
    readOnly: 0,
    theme: 'hopscotch',
    autoBeautify: true,
    autoCloseTags: true,
    autoCloseBrackets: true,
    lineWrapping: true,
    styleActiveLine: true,
    smartIndent: true,
    indentWithTabs: true
  });

  btnEdit.innerHTML = options.model.btn_text;
  btnEdit.style.float = 'right';
  btnEdit.style.backgroundColor = '#090';
  btnEdit.className = stylePrefix + 'btn-prim ' + stylePrefix + 'btn-import';
  btnEdit.onclick = function () {
    let html = (codeViewer.editor.getValue() || '').trim();
    let css = editor.getCss();

    editor.DomComponents.getWrapper().set('content', '');
    editor.setComponents(html);
    editor.setStyle(css);

    modal.close();

    editor.runCommand('notify',{
      type: 'info',
      title: 'Success',
      message: options.model.message,
    })
  };

  editor.Commands.add('html-edit', {
    run: function (editor, sender) {
      sender && sender.set('active', 0);
      var viewer = codeViewer.editor;
      modal.setTitle(options.model.title);
      if (!viewer) {
        let txtarea = document.createElement('textarea');
        container.appendChild(txtarea);
        container.appendChild(btnEdit);
        codeViewer.init(txtarea);
        viewer = codeViewer.editor;
      }

      modal.setContent('');
      modal.setContent(container);

      codeViewer.setContent(editor.getHtml());

      modal.open();
      viewer.refresh();
    }
  });

  editor.Panels.addButton('options', {
    id: 'edit',
    className: options.btn_icon,
    command: 'html-edit',
    attributes: {
      title: options.btn_title
    }
  });
};