export default (editor, opts = {}) => {
  const options = {
    fonts: [],
    ...opts
  };

  let fonts = options.fonts;

  editor.on('load', () => {
    if (!fonts || !Array.isArray(fonts)) {
      fonts = [];
    }

    fonts.push({
      value: '',
      name: 'Unset',
      prepend: true,
    });

    let styleManager = editor.StyleManager;
    let fontProperty = styleManager.getProperty('typography', 'font-family');
    let options = fontProperty.getOptions();

    fonts.forEach(font => {
      if (typeof font === 'string' || font instanceof String) {
        font = {
          name: font,
          value: font,
        };
      }

      if (typeof font.value === 'undefined') {
        console.error('Invalid font', font)
        return;
      }
      
      options[font.prepend ? 'unshift' : 'push']({
        id: font.value,
        label: font.name || font.value,
      });
    })

    fontProperty.setOptions(options);
    styleManager.render();
  })
};