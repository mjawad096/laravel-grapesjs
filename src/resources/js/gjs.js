import grapesjs from 'grapesjs';
import 'grapesjs-blocks-basic';
import 'grapesjs-blocks-bootstrap4';
import CodeEditor from "./plugins/code-editor"
import ImageEditor from "./plugins/image-editor"

import customFontFamily from "./plugins/custom-font-family"
import loader from "./plugins/loader"
import notifications from "./plugins/notifications"
import saveButton from "./plugins/save-button"
import backButton from "./plugins/back-button"

let config = window.editorConfig;
delete window.editorConfig;

let plugins = [
	customFontFamily,
	loader,
	notifications,
]
let pluginsOpts = {
	[customFontFamily]: {fonts: config.pluginManager.customFonts},
	[loader]: {},
	[notifications]: {},
};

if(config.pluginManager.basicBlocks){
	plugins.push('gjs-blocks-basic')
	pluginsOpts['gjs-blocks-basic'] = config.pluginManager.basicBlocks;
}

if(config.pluginManager.bootstrap4Blocks){
	plugins.push('grapesjs-blocks-bootstrap4')
	pluginsOpts['grapesjs-blocks-bootstrap4'] = config.pluginManager.bootstrap4Blocks;
}

if(config.pluginManager.codeEditor){	
	plugins.push(CodeEditor)
	pluginsOpts[CodeEditor] = config.pluginManager.codeEditor
}

if(config.pluginManager.imageEditor){	
	plugins.push(ImageEditor)
	pluginsOpts[ImageEditor] = config.pluginManager.imageEditor
}

plugins.push(saveButton, backButton)
pluginsOpts[saveButton] = {}
pluginsOpts[backButton] = {}

config.plugins = plugins
config.pluginsOpts = pluginsOpts

console.log(config);
let editor = grapesjs.init(config);

if(config.exposeApi){
	Object.defineProperty(window, 'gjsEditor', {
		value: editor
	})
}

editor.on('load',()=>{
	const event = new Event('gjs_loaded');
	event.editor = editor;

	window.document.dispatchEvent(event);
})

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

