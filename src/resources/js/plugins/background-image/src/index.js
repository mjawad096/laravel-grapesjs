export default (editor, opts = {}) => {
  const COMMAND_ID = 'jd-open-change_bg-modal';
  const TOOL_ICON = 'fa fa-image';
  const BG_IMAGE = 'background-image';

  let modal = editor.Modal;

  editor.Commands.add(COMMAND_ID, {
    run: (editor, sender) => setModalContent(),
  });

  let setModalContent = content => {
    modal.setTitle('Change Background settings');

    modal.setContent('');
    modal.setContent(content || createModalContent());

    !content && bindEventHandlers();

    modal.open();
  }

  let createModalContent = () => {
    let fields = [
      {
        name: 'background-image',
        title: 'Image',
        type: 'image',
        full_width: true,
      },
      {
        name: 'background-color',
        title: 'Color',
        type: 'color',
        full_width: true,
      },
      {
        name: 'background-repeat',
        title: 'Repeat',
        type: 'select',
        options: ['repeat', 'repeat-x', 'repeat-y', 'no-repeat'],
      },
      {
        name: 'background-position',
        title: 'Position',
        type: 'select',
        options: ['left top', 'left center', 'left bottom', 'right top', 'right center', 'right bottom', 'center top', 'center center', 'center bottom'],
      },
      {
        name: 'background-attachment',
        title: 'Attachment',
        type: 'select',
        options: ['scroll', 'fixed', 'local'],
      },
      {
        name: 'background-size',
        title: 'Size',
        type: 'select',
        options: ['auto', 'cover', 'contain'],
      },
    ];

    let styles = editor.getSelected().getStyle();
    let fields_html = fields.map(field => {
      let { name, title, type, options, full_width } = field,
        field_html = '',
        isImage = type == 'image',
        value = styles[name] || null;

      if (isImage) {
        field_html = `
          <div class="gjs-sm-field gjs-sm-file">
              <div id="gjs-sm-input-holder">
                  <div class="gjs-sm-btn-c">
                      <button class="gjs-sm-btn jd-bg-setting ${name}" data-property="${name}" id="gjs-sm-images" type="button"">
                          Choose Image
                      </button>
                  </div>
                  <div style=" clear:both;"></div>
              </div>
              <div id="gjs-sm-preview-box" class="gjs-sm-preview-file jd-bg-setting ${name}-preview" style="display: ${value ? 'block' : 'none'};">
                  <div id="gjs-sm-preview-file" class="gjs-sm-preview-file-cnt" style='background-image: ${value};'></div>
                  <div id="gjs-sm-close" class="gjs-sm-preview-file-close">
                    <svg viewBox="0 0 24 24"><path fill="currentColor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"></path></svg>
                  </div>
              </div>
          </div>
        `;
      } else if (type == 'color') {
        field_html = `
          <div class="gjs-field gjs-field-color">
            <div class="gjs-input-holder">
              <input type="color" placeholder="black" class="jd-bg-setting ${name}" data-property="${name}" value="${value}">
            </div>
          </div>
        `;
      } else if (type == 'select') {
        let options_html = '';

        (options || []).forEach(option => {
          options_html += `<option value="${option}" ${value == option ? 'selected' : ''}>${option}</option>`;
        });

        field_html = `
          <div class="gjs-field gjs-select">
              <span id="gjs-sm-input-holder">
                  <select class="jd-bg-setting ${name}" data-property="${name}" >
                      ${options_html}
                  </select>
              </span>
              <div class="gjs-sel-arrow">
                  <div class="gjs-d-s-arrow"></div>
              </div>
          </div>
        `;
      }

      return `
        <div class="gjs-sm-property gjs-sm-file gjs-sm-property__${name} ${full_width ? 'gjs-sm-property--full' : ''}">
            <div class="gjs-sm-label">
                <span class="gjs-sm-icon " title="${title}">
                    ${title}
                </span>
                <div class="gjs-sm-clear" style="display: none;">
                  <svg viewBox="0 0 24 24"><path fill="currentColor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"></path></svg>
                </div>
            </div>
            <div class="gjs-fields">
                ${field_html}
            </div>
        </div>
      `;
    }).join('');

    return `
      <div class="gjs-sm-properties jd-bg-settings">${fields_html}</div>
    `;
  };

  let bindEventHandlers = () => {
    let elements = document.querySelectorAll('.jd-bg-settings .jd-bg-setting[data-property]');

    elements.forEach(element => {
      let property = element.dataset.property;

      if (property == BG_IMAGE) {
        element.addEventListener('click', () => openAssetModal(property));
        let previewClose = document.querySelector(`.jd-bg-settings .jd-bg-setting.${property}-preview #gjs-sm-close svg`);

        previewClose.addEventListener('click', e => setSelectedComponentStyle(property));
      } else {
        element.addEventListener('change', function (e) {
          setSelectedComponentStyle(property, this.value);
        });
      }
    });

  };

  let openAssetModal = (property) => {
    let am = editor.AssetManager;
    let oldContent = modal.getContentEl().childNodes[1] || '';

    am.open({
      types: ['image'],
      select(asset, complete) {
        am.close();

        setModalContent(oldContent);

        setSelectedComponentStyle(property, asset.getSrc());
      }
    });
  };

  let setSelectedComponentStyle = (property, value) => {
    let styles = editor.getSelected().getStyle();

    if (property == BG_IMAGE) {

      let previewContainer = document.querySelector(`.jd-bg-settings .jd-bg-setting.${property}-preview`);
      let preview = previewContainer.firstElementChild;

      if (value) {
        value = `url(${value})`;

        previewContainer.style.display = 'block';
      } else {
        previewContainer.style.display = 'none';
      }

      preview.style.backgroundImage = value || null;
    }

    if (value) {
      styles[property] = value;
    } else {
      delete styles[property];
    }

    editor.getSelected().setStyle(styles);
  };

  editor.on('component:selected', () => {
    const component = editor.getSelected();
    const toolbar = component.get('toolbar');

    const commandExists = toolbar.some(item => item.command === COMMAND_ID);

    // if it doesn't already exist, add it
    if (!commandExists && !component.is('image') && component.get('tagName') !== 'body') {
      let tool = {
        attributes: { 'class': TOOL_ICON },
        command: COMMAND_ID
      };

      toolbar.splice(-2, 0, tool);

      component.set('toolbar', toolbar);
    }
  });
};