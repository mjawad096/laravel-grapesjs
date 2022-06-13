import grapesjs from 'grapesjs';
import 'grapesjs-blocks-basic';
import 'grapesjs-blocks-bootstrap4';
import CodeEditor from "./plugins/code-editor"
import ExtraButtons from "./plugins/extra-buttons"
import ImageEditor from "./plugins/image-editor"
import CustomFontFamily from "./plugins/custom-font-family"
import Loader from "./plugins/loader"
import Notifications from "./plugins/notifications"
import SaveButton from "./plugins/save-button"
import BackButton from "./plugins/back-button"
import Templates from "./plugins/templates"
import CustomTypes from "./plugins/custom-types"
import DeviceButtons from './plugins/device-buttons'
import BackgroundImage from "./plugins/background-image"
import PluginsLoader from "./plugins/plugins-loader"
import StyleEditor from "./plugins/style-editor"
import LinkableImage from "./plugins/linkable-image"

let config = window.editorConfig;
delete window.editorConfig;

let plugins = []
let pluginsOpts = {}

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

if(config.pluginManager.templates){	
	plugins.push(Templates)
	pluginsOpts[Templates] = config.pluginManager.templates
}

plugins = [
	...plugins,
	CustomFontFamily,
	BackgroundImage,
	Loader,
	Notifications,
	CustomTypes,
	ExtraButtons,
	SaveButton,
	BackButton,
	DeviceButtons,
	PluginsLoader,
	StyleEditor,
	LinkableImage,
]

pluginsOpts = {
	...pluginsOpts,
	[BackgroundImage]: {},
	[CustomFontFamily]: {fonts: config.pluginManager.customFonts},
	[Loader]: {},
	[Notifications]: {},
	[CustomTypes]: {},
	[ExtraButtons]: {},
	[SaveButton]: {},
	[BackButton]: {},
	[DeviceButtons]: {},
	[PluginsLoader]: config.pluginManager.pluginsLoader,
	[StyleEditor]: {},
	[LinkableImage]: {},
};

config.plugins = plugins
config.pluginsOpts = pluginsOpts

let editor = grapesjs.init(config);

if(config.exposeApi){
	Object.defineProperty(window, 'gjsEditor', {
		value: editor
	})
}
