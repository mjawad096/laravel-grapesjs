const grapesjs = require('grapesjs');
import pluginBlocks from 'grapesjs-blocks-basic';
// import bootstrap4 from 'grapesjs-blocks-bootstrap4';
import tUIImageEditor from 'grapesjs-tui-image-editor';

const toastr = require('toastr');

let config = window.editorConfig;
delete window.editorConfig;


let remoteIcons = 'https://cdnjs.cloudflare.com/ajax/libs/tui-image-editor/3.15.0/svg/'

let plugins = [
	pluginBlocks, 
	// bootstrap4,
]

let pluginsOpts = {
	'grapesjs-blocks-basic': {},
	// 'grapesjs-blocks-bootstrap4': {},
};

if(config.imageEditor){
	plugins.push(tUIImageEditor)
	pluginsOpts[tUIImageEditor] = {
		config: {
			includeUI: {
				initMenu: 'filter',
			},
		},
		icons: {
			'menu.normalIcon.path': `${remoteIcons}icon-d.svg`,
			'menu.activeIcon.path': `${remoteIcons}icon-b.svg`,
			'menu.disabledIcon.path': `${remoteIcons}icon-a.svg`,
			'menu.hoverIcon.path': `${remoteIcons}icon-c.svg`,
			'submenu.normalIcon.path': `${remoteIcons}icon-d.svg`,
			'submenu.activeIcon.path': `${remoteIcons}icon-c.svg`,
		},
	}
}

config.plugins = plugins
config.pluginsOpts = pluginsOpts

let editor = grapesjs.init(config);

if(config.exposeApi){
	Object.defineProperty(window, 'gjsEditor', {
		value: editor
	})
}

let loader = document.getElementById('loader');
let showLoader = function(){
	if (loader){
		loader.style.display = 'flex';
	}
}

let hideLoader = function(){
	if (loader){
		loader.style.display = 'none';
	}
}


editor.addFontFamily = function(fontFamily, prepend){
	prepend = prepend === true

    let styleManager = this.StyleManager;
    
    let fontProperty = styleManager.getProperty('typography', 'font-family');
    let list = fontProperty.get('list');

    if(prepend){
    	list.unshift(fontFamily);
    }else{
    	list.push(fontFamily);
    }
    
    fontProperty.set('list', list);
    styleManager.render();
}

editor.on('load',()=>{
	hideLoader();

	const event = new Event('gjs_loaded');
	event.editor = editor;

	window.document.dispatchEvent(event);

	editor.addFontFamily({
		name: 'Unset',
		value: '',
	}, true);

	if(config.fonts && Array.isArray(config.fonts) && config.fonts.length){
		config.fonts.forEach(font => {
			if(!font.value){
				console.error('Invalid font', font)
				return;
			}

			editor.addFontFamily({
				name: font.name || font.value,
				value: font.value
			});
		})
	}
})

var pfx = editor.getConfig().stylePrefix;
var modal = editor.Modal;
var commands = editor.Commands;
var codeViewer = editor.CodeManager.getViewer('CodeMirror').clone();
var panels = editor.Panels;
var container = document.createElement('div');
var btnEdit = document.createElement('button');

codeViewer.set({
	codeName: 'htmlmixed',
	readOnly: 0,
	theme: 'hopscotch',
	autoBeautify: true,
	autoCloseTags: true,
	autoCloseBrackets: true,
	lineWrapping: true,
	styleActiveLine: true,
	smartIndent: true,
	indentWithTabs: true
});

btnEdit.innerHTML = 'Save';
btnEdit.style.float = 'right';
btnEdit.style.backgroundColor = '#090';
btnEdit.className = pfx + 'btn-prim ' + pfx + 'btn-import';
btnEdit.onclick = function () {
	let html = (codeViewer.editor.getValue() || '').trim();
    let css = ((html.split('<style>') || [])[1] || '').replace('</style>', '');

	editor.DomComponents.getWrapper().set('content', '');
	editor.setComponents(html);
	editor.setStyle(css);

	modal.close();
	toastr.success('Content Updated', 'Success');
};

commands.add('html-edit', {
	run: function (editor, sender) {
		sender && sender.set('active', 0);
		var viewer = codeViewer.editor;
		modal.setTitle('Edit code');
		if (!viewer) {
			let txtarea = document.createElement('textarea');
			container.appendChild(txtarea);
			container.appendChild(btnEdit);
			codeViewer.init(txtarea);
			viewer = codeViewer.editor;
		}
		var InnerHtml = editor.getHtml();
		var Css = editor.getCss();
		modal.setContent('');
		modal.setContent(container);
		codeViewer.setContent(InnerHtml + "<style>" + Css + '</style>');
		modal.open();
		viewer.refresh();
	}
});

panels.addButton('options',
	[
		{
			id: 'edit',
			className: 'fa fa-edit',
			command: 'html-edit',
			attributes: {
				title: 'Edit code.'
			}
		}
	]
);

panels.addButton('options',
	[
		{
			id: 'upload-file',
			className: 'fa fa-upload',
			command(editor) {
				modal.setTitle('Upload File');
				modal.backdrop = false;
				let uploadFileContainer = document.createElement('div');
				uploadFileContainer.style.position = 'relative';
				uploadFileContainer.style.overflow = 'hidden';
				let uploadedLink = document.createElement('input');
				uploadedLink.type = 'text';
				uploadedLink.style.width = "100%";
				uploadedLink.readOnly = 'readonly';
				let loader = document.createElement('div');
				loader.style.display = 'none';
				loader.style.alignItems = 'center';
				loader.style.justifyContent = 'center';
				loader.style.width = '100%';
				loader.style.position = 'absolute';
				loader.style.top = '0';
				loader.style.left = '0';
				loader.style.height = '100%';
				loader.style.zIndex = '100';
				loader.style.backgroundColor = '#727272e0';
				loader.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
				uploadFileContainer.append(loader);

				let input = document.createElement('input');
				input.type = "file";
				input.style.width = '100%';
				input.onchange = (event) => {
					if (event.target.files[0] == undefined) { return; }
					loader.style.display = 'flex';
					let formData = new FormData();
					formData.append("file", event.target.files[0]);
					uploadFileContainer.disabled = 'true';
					fetch('/media', {
						method: "POST",
						headers: {
							'X-Requested-With': 'XMLHttpRequest'
						},
						body: formData
					})
						.then(resp => resp.json())
						.then(data => {
							event.target.value = "";
							loader.style.display = 'none';
							if (data.errors) {
								throw data.message;
							}
							uploadedLink.value = data.media_url;
							toastr.success('FIle uploaded and Link Ready', 'Success')
						})
						.catch(error => {
							loader.style.display = 'none';
							toastr.error(error, 'Error');
						});
				}

				uploadFileContainer.append(input);
				uploadFileContainer.append(uploadedLink);

				modal.setContent(uploadFileContainer);
				modal.open();
			},
			attributes: {
				title: 'Upload a file and get its url.'
			}
		}
	]
);

panels.addButton('options',
	[
		{
			id: 'save',
			className: 'fa fa-save',
			command(editor) {
				showLoader();
				editor.store(res => {
					hideLoader();
					toastr.success('Page Saved', 'Success');
				});
			},
			attributes: {
				title: 'Save'
			}
		}
	]
);

panels.addButton('options',
	[
		{
			id: 'cancel',
			className: 'fa fa-arrow-left',
			command(editor) {
				window.history.back()
			},
			attributes: {
				title: 'Go back'
			}
		}
	]
);

let blockManager = editor.BlockManager;

blockManager.add("iframe", {
	category: 'Basic',
    label: "iframe",
    type: "iframe",
    content: "<iframe> </iframe>",
    selectable: true,
    attributes: {class:'fa fa-file'},
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
					command: function(editor){
						editor.getSelected().trigger('active')
					},
					
				},
				'alt',
			],
		},
	},
});

if (config.templatesUrl) {
	fetch(config.templatesUrl)
		.then(resp => resp.json())
		.then(data => {
			data.forEach(block => {
				blockManager.add('block-' + block.id, block);
			});
		})
		.catch(error => {
			console.log(error);
		})
}

