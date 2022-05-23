export default (editor, opts = {}) => {
  const commandToAdd = 'jd-change-bg-model';
  const commandIcon = 'fa fa-image';

  editor.Commands.add(commandToAdd, {
    run(editor, sender, options = {}) {
      let am = editor.AssetManager;

      am.open({
        types: ['image'],

        select(asset, complete) {
          const component = editor.getSelected();
       
          if (component) {
            component.setStyle({ 'background-image': `url(${asset.getSrc()})` });
            complete && am.close();
          }
        }
       });
    },
  })

  editor.on('component:selected', () => {

    const component = editor.getSelected();
    const toolbar = component.get('toolbar');

    const commandExists = toolbar.some(item => item.command === commandToAdd);

    // if it doesn't already exist, add it
    if (!commandExists) {
      let tool = {  
        attributes: {'class' : commandIcon}, 
        command: commandToAdd
      };

      toolbar.splice(-2, 0, tool);

      component.set('toolbar', toolbar);
    }

  });
};