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

  let setCodeExpandHandler = (div) => {
    return;
    const BORDER_SIZE = 4;
    
    let m_pos;
    function resize(e){
      const item = document.querySelector(".CodeMirror");
      const dx = m_pos - e.y;
      m_pos = e.y;
      item.style.height = (parseInt(getComputedStyle(item, '').height) + dx) + "px";
    }

    div.querySelector('.gjs-input-holder').addEventListener("mousedown", function(e){
      console.log(e.offsetY);
      if (e.offsetY < BORDER_SIZE) {
        m_pos = e.y;
        document.addEventListener("mousemove", resize, false);
      }
    }, false);

    document.addEventListener("mouseup", function(){
        document.removeEventListener("mousemove", resize, false);
    }, false);
  };

  editor.Panels.addButton('views', {
    id: COMMAND_ID,
    className: 'fa fa-css3',
    command: COMMAND_ID,
    attributes: {
      title: 'Modify styles'
    },
    active: false,
  });

  editor.on('update', updateCodeViewerContent);

  editor.Commands.add(COMMAND_ID, {
    run(editor, sender){ 
      const panel = editor.Panels.getPanel('views-container');
      
      if(!div){
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
              </div>
            </div>
          </div>
        `;

        div.querySelector('.jd-expand-handle').addEventListener('click', function(e){
          let handle = e.target;

          setCanvasWidth(handle.classList.contains('active') ? 85 : 50);
          handle.classList.toggle('active')
        });
      
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

        setTimeout(setCodeExpandHandler(div));

        codeViewer.editor.on('changes', (e, changes) => {
          if(changes.length == 1 && changes[0].origin != "setValue"){
            clearTimeout(timer);

            timer = setTimeout(() => {
              editor.setStyle(e.getValue());
            }, 500);
          }
        })        

        panel.set('appendContent', div).trigger('change:appendContent')
      }

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
  })
};