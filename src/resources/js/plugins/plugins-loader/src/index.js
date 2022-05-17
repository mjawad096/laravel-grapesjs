export default (editor, plugins = []) => {
  if(!plugins || !Array.isArray(plugins) || !plugins.length) return;

  plugins.forEach(plugin => {
    try {
      let callback = window.grapesjs.plugins.get(plugin.name);
      
      if(!callback){
        callback = (window[plugin.name] || {}).default;
      }

      if(!callback){
        console.error(`The defination for plugin '${plugin.name}' not found.`);
        return;
      }
  
      callback(editor, plugin.options);
    } catch (e) {
      console.error(e);
    }
  });
};