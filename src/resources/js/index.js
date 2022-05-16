let config = window.editorConfig;

if(Object.keys(config).length === 0){
	throw new Error('No config found');
}else{
	require('./gjs')
}