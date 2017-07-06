/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	config.language = 'zh-cn'; //配置语言 
	//config.skin = 'office2013';
	config.skin = 'moonocolor';
	//config.skin = 'Moono_blue';
	//config.uiColor = '#AADC6E'; //界面颜色
	config.width = 1000; //宽度 
	config.height = 350; //高度
	
	config.toolbar = 'MyToolbar';
	config.toolbar_MyToolbar =
	[
	        { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
	        { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	        { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
	        
	        
	        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	        '/',
	        { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
	        '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
	        { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
	        { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','CodeSnippet' ] },
	        '/',
	        { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
	        { name: 'colors', items : [ 'TextColor','BGColor' ] },
	        { name: 'tools', items : [ 'Maximize', 'ShowBlocks'] }
	];
	config.extraPlugins = 'codeSnippet';
	codeSnippet_theme: 'Pojoaque';
};
