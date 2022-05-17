export default (editor, opts = {}) => {
  const options = { 
    url: null,
    ...opts 
  };

  if (options.url) {
    fetch(options.url)
      .then(resp => resp.json())
      .then(data => {
        data.forEach(block => {
          editor.BlockManager.add('block-' + block.id, block);
        });
      })
      .catch(error => {
        console.log(error);
      })
  }
};