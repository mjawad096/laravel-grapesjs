import grapesjs from 'grapesjs';
import 'grapesjs-blocks-basic';
import 'grapesjs-blocks-bootstrap4';
import CodeEditor from "./plugins/code-editor"
import ImageEditor from "./plugins/image-editor"

import CustomFontFamily from "./plugins/custom-font-family"
import Loader from "./plugins/loader"
import Notifications from "./plugins/notifications"
import SaveButton from "./plugins/save-button"
import BackButton from "./plugins/back-button"
import Templates from "./plugins/templates"

let config = window.editorConfig;
delete window.editorConfig;

let plugins = [
	CustomFontFamily,
	Loader,
	Notifications,
]
let pluginsOpts = {
	[CustomFontFamily]: {fonts: config.pluginManager.customFonts},
	[Loader]: {},
	[Notifications]: {},
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

plugins.push(SaveButton, BackButton)
pluginsOpts[SaveButton] = {}
pluginsOpts[BackButton] = {}

if(config.pluginManager.templates){	
	plugins.push(Templates)
	pluginsOpts[Templates] = config.pluginManager.templates
}

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

editor.BlockManager.add("iframe", {
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
