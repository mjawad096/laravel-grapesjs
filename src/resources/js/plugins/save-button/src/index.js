export default (editor, opts = {}) => {
  editor.Panels.addButton('options', {
    id: 'save',
    className: 'fa fa-save',
    command(editor) {
      editor.store(res => {
        editor.runCommand('notify',{
          type: 'success',
          title: 'Success',
          message: "Page Saved Successfully"
        })
      });
    },
    attributes: {
      title: 'Save'
    }
  });
};