export default (editor, opts = {}) => {
  const COMMAND_ID = 'css-edit';
  let div, codeViewer, timer;

  let setCanvasWidth = w => {
    const canvas = document.querySelector(".gjs-cv-canvas");
    const panel = document.querySelector(".gjs-pn-views-container");

    let canvas_width = w;
    let panel_width = 100 - w;

    canvas.style.width = `${canvas_width}%`;
    panel.style.width = `${panel_width}%`;
  };

  let updateCodeViewerContent = () => codeViewer && codeViewer.setContent(editor.getCss());

  let setEventListners = (div) => {
    div.querySelector('.jd-expand-handle').addEventListener('click', function(e){
      let handle = e.target;

      setCanvasWidth(handle.classList.contains('active') ? 85 : 50);
      handle.classList.toggle('active')
    });

    codeViewer.editor.on('changes', (e, changes) => {
      if(changes.length == 1 && changes[0].origin != "setValue"){
        clearTimeout(timer);

        timer = setTimeout(() => {
          editor.setStyle(e.getValue());
        }, 500);
      }
    })
    
    function resize(e){
      const container = document.querySelector(".gjs-pn-views-container");
      const canvas = document.querySelector(".gjs-cv-canvas");
      const item = div.querySelector(".CodeMirror");

      let height = e.pageY - item.getBoundingClientRect().top - 4;
      let container_width = 100 - Math.ceil(e.clientX / window.innerWidth * 100);
      let canvas_width = 100 - container_width;

      item.style.width = `100%`;
      if(height >= 300){
        item.style.height = `${height}px`;
      }

      if(container_width >= 15){
        canvas.style.width = `${canvas_width}%`;
        container.style.width = `${container_width}%`;
      }
    }

    div.querySelector('.gjs-input-holder i').addEventListener("mousedown", function(e){
      document.addEventListener("mousemove", resize, false);
    }, false);

    document.addEventListener("mouseup", function(){
        document.removeEventListener("mousemove", resize, false);
    }, false);
  };

  let initCodeViewer = () => {
    if(div) return;

    codeViewer = editor.CodeManager.getViewer('CodeMirror').clone();

    div = document.createElement('div')
    div.classList.add('jd-style-editor');
    div.innerHTML= `
      <div>
        <i class="jd-expand-handle fa fa-arrows-h"></i>
        <div class="gjs-trt-header">Update styles</div>
      </div>
      <div class="jd-field-containter">
        <div class="gjs-field gjs-field-styles">
          <div class="gjs-input-holder">
            <textarea></textarea>
            <i></i>
          </div>
        </div>
      </div>
    `;
  
    codeViewer.set({
      codeName: 'css',
      readOnly: 0,
      theme: 'hopscotch',
      autoBeautify: true,
      autoCloseTags: true,
      autoCloseBrackets: true,
      lineWrapping: true,
      styleActiveLine: true,
      smartIndent: true,
      indentWithTabs: true,
    });

    codeViewer.init(div.querySelector('.jd-field-containter textarea'));

    setEventListners(div);

    editor
      .Panels
      .getPanel('views-container')
      .set('appendContent', div)
      .trigger('change:appendContent');
  };

  editor.on('update', updateCodeViewerContent);

  editor.Commands.add(COMMAND_ID, {
    run(editor, sender){    
      initCodeViewer();
      updateCodeViewerContent();
      div.style.display = 'block';
    },

    stop(editor, sender){ 
      if(div){
        setCanvasWidth(85);
        div.querySelector('.jd-expand-handle').classList.remove('active')

        div.style.display = 'none';
      }
    }
  });

  editor.Panels.addButton('views', {
    id: COMMAND_ID,
    className: 'fa fa-css3',
    command: COMMAND_ID,
    attributes: {
      title: 'Modify styles'
    },
    active: false,
  });
};