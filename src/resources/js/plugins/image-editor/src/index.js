export default (editor, options = {}) => {
  const opts = {
    ...{
      proxy_url : null,

      proxy_url_input: 'file',

      // TOAST UI's configurations
      // http://nhnent.github.io/tui.image-editor/latest/ImageEditor.html
      config: {
        includeUI: {
          initMenu: 'filter',
        }
      },

      // Pass the editor constructor. By default, the `tui.ImageEditor` will be called
      constructor: '',

      // Label for the image editor (used in the modal)
      labelImageEditor: 'Image Editor',

      // Label used on the apply button
      labelApply: 'Apply',

      // Default editor height
      height: '650px',

      // Default editor width
      width: '100%',

      // Id to use to create the image editor command
      commandId: 'tui-image-editor',

      // Icon used in the component toolbar
      toolbarIcon: `<svg viewBox="0 0 24 24">
                    <path d="M20.71 7.04c.39-.39.39-1.04 0-1.41l-2.34-2.34c-.37-.39-1.02-.39-1.41 0l-1.84 1.83 3.75 3.75M3 17.25V21h3.75L17.81 9.93l-3.75-3.75L3 17.25z">
                    </path>
                  </svg>`,

      // Hide the default editor header
      hideHeader: 1,

      // By default, GrapesJS takes the modified image, adds it to the Asset Manager and update the target.
      // If you need some custom logic you can use this custom 'onApply' function
      // eg.
      // onApply: (imageEditor, imageModel) => {
      //   const dataUrl = imageEditor.toDataURL();
      //   editor.AssetManager.add({ src: dataUrl }); // Add it to Assets
      //   imageModel.set('src', dataUrl); // Update the image component
      // }
      onApply: 0,

      // If no custom `onApply` is passed and this option is `true`, the result image will be added to assets
      addToAssets: 1,

      // If no custom `onApply` is passed, on confirm, the edited image, will be passed to the AssetManager's
      // uploader and the result (eg. instead of having the dataURL you'll have the URL) will be
      // passed to the default `onApply` process (update target, etc.)
      upload: 1,

      // The apply button (HTMLElement) will be passed as an argument to this function, once created.
      // This will allow you a higher customization.
      onApplyButton: () => { },

      // The TOAST UI editor isn't compiled with icons, so generally, you should download them and indicate
      // the local path in the `includeUI.theme` configurations.
      // Use this option to change them or set it to `false` to keep what is come in `includeUI.theme`
      // By default, the plugin will try to use the editor's remote icons (which involves a cross-origin async
      // request, indicated as unsafe by most of the browsers)
      icons: {
        'menu.normalIcon.path': `${options.dist_path}/svg/icon-d.svg`,
        'menu.activeIcon.path': `${options.dist_path}/svg/icon-b.svg`,
        'menu.disabledIcon.path': `${options.dist_path}/svg/icon-a.svg`,
        'menu.hoverIcon.path': `${options.dist_path}/svg/icon-c.svg`,
        'submenu.normalIcon.path': `${options.dist_path}/svg/icon-d.svg`,
        'submenu.activeIcon.path': `${options.dist_path}/svg/icon-c.svg`,
      },

      // Scripts to load dynamically in case no TOAST UI editor instance was found
      script: [
        'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/1.6.7/fabric.js',
        'https://uicdn.toast.com/tui.code-snippet/v1.5.0/tui-code-snippet.min.js',
        'https://uicdn.toast.com/tui-color-picker/v2.2.0/tui-color-picker.min.js',
        'https://uicdn.toast.com/tui-image-editor/v3.4.0/tui-image-editor.js'
      ],

      // In case the script is loaded this style will be loaded too
      style: [
        'https://uicdn.toast.com/tui-color-picker/v2.2.0/tui-color-picker.min.css',
        'https://uicdn.toast.com/tui-image-editor/v3.4.0/tui-image-editor.min.css'
      ],
    }, ...options
  };

  const { script, style, height, width, hideHeader, icons, onApply, upload, addToAssets, commandId } = opts;
  const getConstructor = () => opts.constructor || (window.tui && window.tui.ImageEditor);
  let constr = getConstructor();

  // Dynamic loading of the image editor scripts and styles
  if (!constr && script) {
    const { head } = document;
    const scripts = Array.isArray(script) ? [...script] : [script];
    const styles = Array.isArray(style) ? [...style] : [style];
    const appendStyle = styles => {
      if (styles.length) {
        const link = document.createElement('link');
        link.href = styles.shift();
        link.rel = 'stylesheet';
        head.appendChild(link);
        appendStyle(styles);
      }
    }
    const appendScript = scripts => {
      if (scripts.length) {
        const scr = document.createElement('script');
        scr.src = scripts.shift();
        scr.onerror = scr.onload = appendScript.bind(null, scripts);
        head.appendChild(scr);
      } else {
        constr = getConstructor();
      }
    }
    appendStyle(styles);
    appendScript(scripts);
  }

  // Update image component toolbar
  const domc = editor.DomComponents;
  const typeImage = domc.getType('image').model;
  domc.addType('image', {
    model: {
      initToolbar() {
        typeImage.prototype.initToolbar.apply(this, arguments);
        const tb = this.get('toolbar');
        const tbExists = tb.some(item => item.command === commandId);

        if (!tbExists) {
          tb.unshift({
            command: commandId,
            label: opts.toolbarIcon,
          });
          this.set('toolbar', tb);
        }
      }
    }
  })

  // Add the image editor command
  editor.Commands.add(commandId, {
    run(ed, s, options = {}) {
      const { id } = this;

      if (!constr) {
        ed.log('TOAST UI Image editor not found', {
          level: 'error',
          ns: commandId,
        });
        return ed.stopCommand(id);
      }

      this.editor = ed;
      this.target = options.target || ed.getSelected();
      const content = this.createContent();
      const title = opts.labelImageEditor;
      const btn = content.children[1];
      ed.Modal.open({ title, content })
        .getModel().once('change:open', () => ed.stopCommand(id));
      this.imageEditor = new constr(content.children[0], this.getEditorConfig());
      ed.getModel().setEditing(1);
      btn.onclick = () => this.applyChanges();
      opts.onApplyButton(btn);
    },

    stop(ed) {
      const { imageEditor } = this;
      imageEditor && imageEditor.destroy();
      ed.getModel().setEditing(0);
    },

    getEditorConfig() {
      const config = { ...opts.config };
      let path = this.target.get('src');

      if ( opts.proxy_url && !path.startsWith('data:')){
        path = `${opts.proxy_url}?${opts.proxy_url_input}=${encodeURI(path)}`
      }

      if (!config.includeUI) config.includeUI = {};
      config.includeUI = {
        theme: {},
        ...config.includeUI,
        loadImage: { path, name: 1 },
        uiSize: { height, width },
      };
      if (hideHeader) config.includeUI.theme['header.display'] = 'none';
      if (icons) config.includeUI.theme = {
        ...config.includeUI.theme,
        ...icons,
      }

      return config;
    },

    createContent() {
      const content = document.createElement('div');
      content.style = 'position: relative';
      content.innerHTML = `
        <div></div>
        <button class="tui-image-editor__apply-btn" style="
          position: absolute;
          top: 0; right: 0;
          margin: 10px;
          background-color: #fff;
          font-size: 1rem;
          border-radius: 3px;
          border: none;
          padding: 10px 20px;
          cursor: pointer
        ">
          ${opts.labelApply}
        </botton>
      `;

      return content;
    },

    applyChanges() {
      const { imageEditor, target, editor } = this;
      const { AssetManager } = editor;

      if (onApply) {
        onApply(imageEditor, target);
      } else {
        if (imageEditor.getDrawingMode() === 'CROPPER') {
          imageEditor.crop(imageEditor.getCropzoneRect()).then(() => {
            this.uploadImage(imageEditor, target, AssetManager);
          });
        } else {
          this.uploadImage(imageEditor, target, AssetManager);
        }
      }
    },

    uploadImage(imageEditor, target, am) {
      const dataURL = imageEditor.toDataURL();
      if (upload) {
        const file = this.dataUrlToBlob(dataURL);
        am.FileUploader().uploadFile({
          dataTransfer: { files: [file] }
        }, res => {
          const obj = res && res.data && res.data[0];
          const src = obj && (typeof obj === 'string' ? obj : obj.src);
          src && this.applyToTarget(src);
        });
      } else {
        addToAssets && am.add({
          src: dataURL,
          name: (target.get('src') || '').split('/').pop(),
        });
        this.applyToTarget(dataURL);
      }
    },

    applyToTarget(result) {
      this.target.set({ src: result });
      this.editor.Modal.close();
    },

    dataUrlToBlob(dataURL) {
      const data = dataURL.split(',');
      const byteStr = window.atob(data[1]);
      const type = data[0].split(':')[1].split(';')[0];
      const ab = new ArrayBuffer(byteStr.length);
      const ia = new Uint8Array(ab);

      for (let i = 0; i < byteStr.length; i++) {
        ia[i] = byteStr.charCodeAt(i);
      }

      return new Blob([ab], { type });
    },
  });
};