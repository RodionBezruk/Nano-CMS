FCKConfig.DisableEnterKeyHandler = false ;
FCKConfig.CustomConfigurationsPath = '' ;
FCKConfig.EditorAreaCSS = FCKConfig.BasePath + 'css/fck_editorarea.css' ;
FCKConfig.ToolbarComboPreviewCSS = '' ;
FCKConfig.DocType = '' ;
FCKConfig.BaseHref = '' ;
FCKConfig.FullPage = false ;
FCKConfig.Debug = false ;
FCKConfig.AllowQueryStringDebug = true ;
FCKConfig.SkinPath = FCKConfig.BasePath + 'skins/default/' ;
FCKConfig.PreloadImages = [ FCKConfig.SkinPath + 'images/toolbar.start.gif', FCKConfig.SkinPath + 'images/toolbar.buttonarrow.gif' ] ;
FCKConfig.PluginsPath = FCKConfig.BasePath + 'plugins/' ;
FCKConfig.AutoGrowMax = 400 ;
FCKConfig.AutoDetectLanguage	= false ;
FCKConfig.DefaultLanguage		= 'de' ;
FCKConfig.ContentLangDirection	= 'ltr' ;
FCKConfig.ProcessHTMLEntities	= true ;
FCKConfig.IncludeLatinEntities	= true ;
FCKConfig.IncludeGreekEntities	= true ;
FCKConfig.ProcessNumericEntities = false ;
FCKConfig.AdditionalNumericEntities = ''  ;		
FCKConfig.FillEmptyBlocks	= true ;
FCKConfig.FormatSource		= true ;
FCKConfig.FormatOutput		= true ;
FCKConfig.FormatIndentator	= '    ' ;
FCKConfig.ForceStrongEm = true ;
FCKConfig.GeckoUseSPAN	= false ;
FCKConfig.StartupFocus	= false ;
FCKConfig.ForcePasteAsPlainText	= false ;
FCKConfig.AutoDetectPasteFromWord = true ;	
FCKConfig.ForceSimpleAmpersand	= false ;
FCKConfig.TabSpaces		= 0 ;
FCKConfig.ShowBorders	= true ;
FCKConfig.SourcePopup	= false ;
FCKConfig.ToolbarStartExpanded	= true ;
FCKConfig.ToolbarCanCollapse	= true ;
FCKConfig.IgnoreEmptyParagraphValue = true ;
FCKConfig.PreserveSessionOnFileBrowser = false ;
FCKConfig.FloatingPanelsZIndex = 10000 ;
FCKConfig.TemplateReplaceAll = true ;
FCKConfig.TemplateReplaceCheckbox = true ;
FCKConfig.ToolbarLocation = 'In' ;
FCKConfig.ToolbarSets["Default"] = [
	['Source','-','Cut','Copy','Paste','PasteText','PasteWord'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Link','Unlink','Anchor'],
	['Image','Flash','Table','Rule','Smiley','SpecialChar','PageBreak'],
	'/',
	['Style','FontFormat','FontName','FontSize'],
	['TextColor','BGColor'],
	['FitWindow','-','About']
] ;
FCKConfig.ToolbarSets["Basic"] = [
	['Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','-','About']
] ;
FCKConfig.EnterMode = 'br' ;			
FCKConfig.ShiftEnterMode = 'p' ;	
FCKConfig.Keystrokes = [
	[ CTRL + 65 , true ],
	[ CTRL + 67 , true ],
	[ CTRL + 70 , true ],
	[ CTRL + 83 , true ],
	[ CTRL + 88 , true ],
	[ CTRL + 86 , 'Paste' ],
	[ SHIFT + 45 , 'Paste' ],
	[ CTRL + 90 , 'Undo' ],
	[ CTRL + 89 , 'Redo' ],
	[ CTRL + SHIFT + 90 , 'Redo' ],
	[ CTRL + 76 , 'Link' ],
	[ CTRL + 66 , 'Bold' ],
	[ CTRL + 73 , 'Italic' ],
	[ CTRL + 85 , 'Underline' ],
	[ CTRL + SHIFT + 83 , 'Save' ],
	[ CTRL + ALT + 13 , 'FitWindow' ],
	[ CTRL + 9 , 'Source' ]
] ;
FCKConfig.ContextMenu = ['Generic','Link','Anchor','Image','Flash','Select','Textarea','Checkbox','Radio','TextField','HiddenField','ImageButton','Button','BulletedList','NumberedList','Table','Form'] ;
FCKConfig.BrowserContextMenuOnCtrl = false ;
FCKConfig.FontColors = '000000,993300,333300,003300,003366,000080,333399,333333,800000,FF6600,808000,808080,008080,0000FF,666699,808080,FF0000,FF9900,99CC00,339966,33CCCC,3366FF,800080,999999,FF00FF,FFCC00,FFFF00,00FF00,00FFFF,00CCFF,993366,C0C0C0,FF99CC,FFCC99,FFFF99,CCFFCC,CCFFFF,99CCFF,CC99FF,FFFFFF' ;
FCKConfig.FontNames		= 'Arial;Comic Sans MS;Courier New;Tahoma;Times New Roman;Verdana' ;
FCKConfig.FontSizes		= '1/xx-small;2/x-small;3/small;4/medium;5/large;6/x-large;7/xx-large' ;
FCKConfig.FontFormats	= 'p;div;pre;address;h1;h2;h3;h4;h5;h6' ;
FCKConfig.StylesXmlPath		= FCKConfig.EditorPath + 'fckstyles.xml' ;
FCKConfig.TemplatesXmlPath	= FCKConfig.EditorPath + 'fcktemplates.xml' ;
FCKConfig.SpellChecker			= 'ieSpell' ;	
FCKConfig.IeSpellDownloadUrl	= 'http:
FCKConfig.SpellerPagesServerScript = 'server-scripts/spellchecker.php' ;	
FCKConfig.FirefoxSpellChecker	= false ;
FCKConfig.MaxUndoLevels = 15 ;
FCKConfig.DisableObjectResizing = false ;
FCKConfig.DisableFFTableHandles = true ;
FCKConfig.LinkDlgHideTarget		= false ;
FCKConfig.LinkDlgHideAdvanced	= false ;
FCKConfig.ImageDlgHideLink		= false ;
FCKConfig.ImageDlgHideAdvanced	= false ;
FCKConfig.FlashDlgHideAdvanced	= false ;
FCKConfig.ProtectedTags = '' ;
FCKConfig.BodyId = '' ;
FCKConfig.BodyClass = '' ;
FCKConfig.DefaultLinkTarget = '' ;
FCKConfig.CleanWordKeepsStructure = false ;
var _FileBrowserLanguage	= 'php' ;	
var _QuickUploadLanguage	= 'php' ;	
var _FileBrowserExtension = _FileBrowserLanguage == 'perl' ? 'cgi' : _FileBrowserLanguage ;
FCKConfig.LinkBrowser = false ;
FCKConfig.LinkBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Connector=connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension ;
FCKConfig.LinkBrowserWindowWidth	= FCKConfig.ScreenWidth * 0.7 ;		
FCKConfig.LinkBrowserWindowHeight	= FCKConfig.ScreenHeight * 0.7 ;	
FCKConfig.ImageBrowser = false ;
FCKConfig.ImageBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Image&Connector=connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension ;
FCKConfig.ImageBrowserWindowWidth  = FCKConfig.ScreenWidth * 0.7 ;	
FCKConfig.ImageBrowserWindowHeight = FCKConfig.ScreenHeight * 0.7 ;	
FCKConfig.FlashBrowser = false ;
FCKConfig.FlashBrowserURL = FCKConfig.BasePath + 'filemanager/browser/default/browser.html?Type=Flash&Connector=connectors/' + _FileBrowserLanguage + '/connector.' + _FileBrowserExtension ;
FCKConfig.FlashBrowserWindowWidth  = FCKConfig.ScreenWidth * 0.7 ;	
FCKConfig.FlashBrowserWindowHeight = FCKConfig.ScreenHeight * 0.7 ;	
FCKConfig.LinkUpload = false ;
FCKConfig.LinkUploadURL = FCKConfig.BasePath + 'filemanager/upload/' + _QuickUploadLanguage + '/upload.' + _QuickUploadLanguage ;
FCKConfig.LinkUploadAllowedExtensions	= "" ;			
FCKConfig.LinkUploadDeniedExtensions	= ".(html|htm|php|php2|php3|php4|php5|phtml|pwml|inc|asp|aspx|ascx|jsp|cfm|cfc|pl|bat|exe|com|dll|vbs|js|reg|cgi|htaccess|asis|sh|shtml|shtm|phtm)$" ;	
FCKConfig.ImageUpload = false ;
FCKConfig.ImageUploadURL = FCKConfig.BasePath + 'filemanager/upload/' + _QuickUploadLanguage + '/upload.' + _QuickUploadLanguage + '?Type=Image' ;
FCKConfig.ImageUploadAllowedExtensions	= ".(jpg|gif|jpeg|png|bmp)$" ;		
FCKConfig.ImageUploadDeniedExtensions	= "" ;							
FCKConfig.FlashUpload = false ;
FCKConfig.FlashUploadURL = FCKConfig.BasePath + 'filemanager/upload/' + _QuickUploadLanguage + '/upload.' + _QuickUploadLanguage + '?Type=Flash' ;
FCKConfig.FlashUploadAllowedExtensions	= ".(swf|fla)$" ;		
FCKConfig.FlashUploadDeniedExtensions	= "" ;					
FCKConfig.SmileyPath	= FCKConfig.BasePath + 'images/smiley/msn/' ;
FCKConfig.SmileyImages	= ['regular_smile.gif','sad_smile.gif','wink_smile.gif','teeth_smile.gif','confused_smile.gif','tounge_smile.gif','embaressed_smile.gif','omg_smile.gif','whatchutalkingabout_smile.gif','angry_smile.gif','angel_smile.gif','shades_smile.gif','devil_smile.gif','cry_smile.gif','lightbulb.gif','thumbs_down.gif','thumbs_up.gif','heart.gif','broken_heart.gif','kiss.gif','envelope.gif'] ;
FCKConfig.SmileyColumns = 8 ;
FCKConfig.SmileyWindowWidth		= 320 ;
FCKConfig.SmileyWindowHeight	= 240 ;
