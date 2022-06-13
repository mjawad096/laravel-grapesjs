export default (editor, opts = {}) => {
  const LINK_COMMAND_ID = 'jd-add-link-image';
  const UNLINK_COMMAND_ID = 'jd-remove-link-image';
  const LINK_TOOL_ICON = 'fa fa-link';
  const UNLINK_TOOL_ICON = 'fa fa-unlink';

  editor.on('component:selected', () => {
    const component = editor.getSelected();
    if(!component) return;

    const toolbar = component.get('toolbar');
    
    if (component.is('image')) {
      let command, icon;

      if(component.closest('a') === 0){
        icon = LINK_TOOL_ICON;
        command = LINK_COMMAND_ID;
      }else{
        let parent = component.parent();
        if(parent.get('tagName') == 'a' && parent.components().length == 1){
          icon = UNLINK_TOOL_ICON;
          command = UNLINK_COMMAND_ID;
        }
      }

      if(command && !toolbar.some(item => item.command === command)){
        toolbar.splice(-2, 0, {
          attributes: { 'class': icon },
          command
        });
  
        component.set('toolbar', toolbar);
      }
    }
  });

  editor.Commands.add(LINK_COMMAND_ID, {
    run: (editor, sender) => {
      let component = editor.getSelected();
      if(!component || component.closest('a') !== 0) return;

      let toolbar = component.get('toolbar');
      let toolIndex = toolbar.findIndex(item => item.command === LINK_COMMAND_ID)
      toolbar.splice(toolIndex, 1);

      component.set('toolbar', toolbar);
      let new_component = component.replaceWith('<a href="#"></a>');
      new_component.components(component = component.clone())      

      editor.select();
      editor.select(new_component);

      document.querySelector('.gjs-pn-panels .gjs-pn-views .gjs-pn-buttons [title="Settings"]').click();
      let hrefInput = document.querySelector('.gjs-pn-panels .gjs-pn-views-container .gjs-trt-trait__wrp-href input');

      hrefInput.focus();
      hrefInput.select();
    },
  });

  editor.Commands.add(UNLINK_COMMAND_ID, {
    run: (editor, sender) => {
      let component = editor.getSelected();
      if(!component) return;

      let parent = component.parent();
      if(!parent || !(parent.get('tagName') == 'a' && parent.components().length == 1)) return;

      if(!confirm('Are you sure?')) return;

      let toolbar = component.get('toolbar');
      let toolIndex = toolbar.findIndex(item => item.command === UNLINK_COMMAND_ID)
      toolbar.splice(toolIndex, 1);

      component.set('toolbar', toolbar);
      parent.replaceWith(component = component.clone());
      editor.select()
      editor.select(component)
    },
  });
};