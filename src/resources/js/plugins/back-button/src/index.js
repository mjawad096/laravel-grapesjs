export default (editor, opts = {}) => {
  let options = {
    icon: 'fa fa-arrow-left',
    title: 'Go back',
    link: null,
    ...opts,
  };

  editor.Panels.addButton('options', {
    id: 'cancel',
    className: options.icon,
    command(editor) {
      if(options.link){
        window.location = options.link;
      }else{
        window.history.back()
      }
    },
    attributes: {
      title: options.title
    }
  });
};