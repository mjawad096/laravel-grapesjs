export default (editor, opts = {}) => {
  editor.BlockManager.add("iframe", {
    category: 'Basic',
    label: "iframe",
    type: "iframe",
    content: "<iframe> </iframe>",
    selectable: true,
    attributes: { class: 'fa fa-file' },
  });

  editor.DomComponents.addType("iframe", {
    isComponent: el => el.tagName === "IFRAME",
    model: {
      defaults: {
        type: "iframe",
        traits: [
          {
            type: "text",
            label: "src",
            name: "src"
          }
        ]
      }
    }
  });

  editor.DomComponents.addType('image', {
    isComponent: el => el.tagName == 'IMG',
    model: {
      defaults: {
        traits: [
          {
            name: 'src',
            placeholder: 'Insert image url here.',
          },
          {
            type: 'button',
            text: 'Choose Image',
            full: true, // Full width button
            command: function (editor) {
              editor.getSelected().trigger('active')
            },

          },
          'alt',
        ],
      },
    },
  });
};