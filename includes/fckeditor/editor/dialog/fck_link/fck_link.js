var oEditor		= window.parent.InnerDialogLoaded() ;
var FCK			= oEditor.FCK ;
var FCKLang		= oEditor.FCKLang ;
var FCKConfig	= oEditor.FCKConfig ;
var FCKRegexLib	= oEditor.FCKRegexLib ;
window.parent.AddTab( 'Info', FCKLang.DlgLnkInfoTab ) ;
if ( !FCKConfig.LinkDlgHideTarget )
	window.parent.AddTab( 'Target', FCKLang.DlgLnkTargetTab, true ) ;
if ( FCKConfig.LinkUpload )
	window.parent.AddTab( 'Upload', FCKLang.DlgLnkUpload, true ) ;
if ( !FCKConfig.LinkDlgHideAdvanced )
	window.parent.AddTab( 'Advanced', FCKLang.DlgAdvancedTag ) ;
function OnDialogTabChange( tabCode )
{
	ShowE('divInfo'		, ( tabCode == 'Info' ) ) ;
	ShowE('divTarget'	, ( tabCode == 'Target' ) ) ;
	ShowE('divUpload'	, ( tabCode == 'Upload' ) ) ;
	ShowE('divAttribs'	, ( tabCode == 'Advanced' ) ) ;
	window.parent.SetAutoSize( true ) ;
}
var oRegex = new Object() ;
oRegex.UriProtocol = /^(((http|https|ftp|news):\/\/)|mailto:)/gi ;
oRegex.UrlOnChangeProtocol = /^(http|https|ftp|news):\/\/(?=.)/gi ;
oRegex.UrlOnChangeTestOther = /^((javascript:)|[#\/\.])/gi ;
oRegex.ReserveTarget = /^_(blank|self|top|parent)$/i ;
oRegex.PopupUri = /^javascript:void\(\s*window.open\(\s*'([^']+)'\s*,\s*(?:'([^']*)'|null)\s*,\s*'([^']*)'\s*\)\s*\)\s*$/ ;
oRegex.OnClickPopup = /^\s*on[cC]lick="\s*window.open\(\s*this\.href\s*,\s*(?:'([^']*)'|null)\s*,\s*'([^']*)'\s*\)\s*;\s*return\s*false;*\s*"$/ ;
oRegex.PopupFeatures = /(?:^|,)([^=]+)=(\d+|yes|no)/gi ;
var oParser = new Object() ;
oParser.ParseEMailUrl = function( emailUrl )
{
	var oEMailInfo = new Object() ;
	oEMailInfo.Address	= '' ;
	oEMailInfo.Subject	= '' ;
	oEMailInfo.Body		= '' ;
	var oParts = emailUrl.match( /^([^\?]+)\??(.+)?/ ) ;
	if ( oParts )
	{
		oEMailInfo.Address = oParts[1] ;
		if ( oParts[2] )
		{
			var oMatch = oParts[2].match( /(^|&)subject=([^&]+)/i ) ;
			if ( oMatch ) oEMailInfo.Subject = decodeURIComponent( oMatch[2] ) ;
			oMatch = oParts[2].match( /(^|&)body=([^&]+)/i ) ;
			if ( oMatch ) oEMailInfo.Body = decodeURIComponent( oMatch[2] ) ;
		}
	}
	return oEMailInfo ;
}
oParser.CreateEMailUri = function( address, subject, body )
{
	var sBaseUri = 'mailto:' + address ;
	var sParams = '' ;
	if ( subject.length > 0 )
		sParams = '?subject=' + encodeURIComponent( subject ) ;
	if ( body.length > 0 )
	{
		sParams += ( sParams.length == 0 ? '?' : '&' ) ;
		sParams += 'body=' + encodeURIComponent( body ) ;
	}
	return sBaseUri + sParams ;
}
var oLink = FCK.Selection.MoveToAncestorNode( 'A' ) ;
if ( oLink )
	FCK.Selection.SelectNode( oLink ) ;
window.onload = function()
{
	oEditor.FCKLanguageManager.TranslatePage(document) ;
	LoadAnchorNamesAndIds() ;
	LoadSelection() ;
	SetLinkType( GetE('cmbLinkType').value ) ;
	GetE('divBrowseServer').style.display = FCKConfig.LinkBrowser ? '' : 'none' ;
	GetE('divInfo').style.display = '' ;
	if ( FCKConfig.LinkUpload )
		GetE('frmUpload').action = FCKConfig.LinkUploadURL ;
	SetDefaultTarget() ;
	window.parent.SetOkButton( true ) ;
}
var bHasAnchors ;
function LoadAnchorNamesAndIds()
{
	var aAnchors = new Array() ;
	var i ;
	var oImages = oEditor.FCK.EditorDocument.getElementsByTagName( 'IMG' ) ;
	for( i = 0 ; i < oImages.length ; i++ )
	{
		if ( oImages[i].getAttribute('_fckanchor') )
			aAnchors[ aAnchors.length ] = oEditor.FCK.GetRealElement( oImages[i] ) ;
	}
	var oLinks = oEditor.FCK.EditorDocument.getElementsByTagName( 'A' ) ;
	for( i = 0 ; i < oLinks.length ; i++ )
	{
		if ( oLinks[i].name && ( oLinks[i].name.length > 0 ) )
			aAnchors[ aAnchors.length ] = oLinks[i] ;
	}
	var aIds = oEditor.FCKTools.GetAllChildrenIds( oEditor.FCK.EditorDocument.body ) ;
	bHasAnchors = ( aAnchors.length > 0 || aIds.length > 0 ) ;
	for ( i = 0 ; i < aAnchors.length ; i++ )
	{
		var sName = aAnchors[i].name ;
		if ( sName && sName.length > 0 )
			oEditor.FCKTools.AddSelectOption( GetE('cmbAnchorName'), sName, sName ) ;
	}
	for ( i = 0 ; i < aIds.length ; i++ )
	{
		oEditor.FCKTools.AddSelectOption( GetE('cmbAnchorId'), aIds[i], aIds[i] ) ;
	}
	ShowE( 'divSelAnchor'	, bHasAnchors ) ;
	ShowE( 'divNoAnchor'	, !bHasAnchors ) ;
}
function LoadSelection()
{
	if ( !oLink ) return ;
	var sType = 'url' ;
	var sHRef = oLink.getAttribute( '_fcksavedurl' ) ;
	if ( sHRef == null )
		sHRef = oLink.getAttribute( 'href' , 2 ) || '' ;
	var oPopupMatch = oRegex.PopupUri.exec( sHRef ) ;
	if( oPopupMatch )
	{
		GetE('cmbTarget').value = 'popup' ;
		sHRef = oPopupMatch[1] ;
		FillPopupFields( oPopupMatch[2], oPopupMatch[3] ) ;
		SetTarget( 'popup' ) ;
	}
	if ( !oPopupMatch ) {
		var onclick = oLink.getAttribute( 'onclick_fckprotectedatt' ) ;
		oPopupMatch = oRegex.OnClickPopup.exec( onclick ) ;
		if( oPopupMatch )
		{
			GetE( 'cmbTarget' ).value = 'popup' ;
			FillPopupFields( oPopupMatch[1], oPopupMatch[2] ) ;
			SetTarget( 'popup' ) ;
		}
	}
	var sProtocol = oRegex.UriProtocol.exec( sHRef ) ;
	if ( sProtocol )
	{
		sProtocol = sProtocol[0].toLowerCase() ;
		GetE('cmbLinkProtocol').value = sProtocol ;
		var sUrl = sHRef.replace( oRegex.UriProtocol, '' ) ;
		if ( sProtocol == 'mailto:' )	
		{
			sType = 'email' ;
			var oEMailInfo = oParser.ParseEMailUrl( sUrl ) ;
			GetE('txtEMailAddress').value	= oEMailInfo.Address ;
			GetE('txtEMailSubject').value	= oEMailInfo.Subject ;
			GetE('txtEMailBody').value		= oEMailInfo.Body ;
		}
		else				
		{
			sType = 'url' ;
			GetE('txtUrl').value = sUrl ;
		}
	}
	else if ( sHRef.substr(0,1) == '#' && sHRef.length > 1 )	
	{
		sType = 'anchor' ;
		GetE('cmbAnchorName').value = GetE('cmbAnchorId').value = sHRef.substr(1) ;
	}
	else					
	{
		sType = 'url' ;
		GetE('cmbLinkProtocol').value = '' ;
		GetE('txtUrl').value = sHRef ;
	}
	if ( !oPopupMatch )
	{
		var sTarget = oLink.target ;
		if ( sTarget && sTarget.length > 0 )
		{
			if ( oRegex.ReserveTarget.test( sTarget ) )
			{
				sTarget = sTarget.toLowerCase() ;
				GetE('cmbTarget').value = sTarget ;
			}
			else
				GetE('cmbTarget').value = 'frame' ;
			GetE('txtTargetFrame').value = sTarget ;
		}
	}
	GetE('txtAttId').value			= oLink.id ;
	GetE('txtAttName').value		= oLink.name ;
	GetE('cmbAttLangDir').value		= oLink.dir ;
	GetE('txtAttLangCode').value	= oLink.lang ;
	GetE('txtAttAccessKey').value	= oLink.accessKey ;
	GetE('txtAttTabIndex').value	= oLink.tabIndex <= 0 ? '' : oLink.tabIndex ;
	GetE('txtAttTitle').value		= oLink.title ;
	GetE('txtAttContentType').value	= oLink.type ;
	GetE('txtAttCharSet').value		= oLink.charset ;
	var sClass ;
	if ( oEditor.FCKBrowserInfo.IsIE )
	{
		sClass	= oLink.getAttribute('className',2) || '' ;
		sClass = sClass.replace( FCKRegexLib.FCK_Class, '' ) ;
		GetE('txtAttStyle').value	= oLink.style.cssText ;
	}
	else
	{
		sClass	= oLink.getAttribute('class',2) || '' ;
		GetE('txtAttStyle').value	= oLink.getAttribute('style',2) || '' ;
	}
	GetE('txtAttClasses').value	= sClass ;
	GetE('cmbLinkType').value = sType ;
}
function SetLinkType( linkType )
{
	ShowE('divLinkTypeUrl'		, (linkType == 'url') ) ;
	ShowE('divLinkTypeAnchor'	, (linkType == 'anchor') ) ;
	ShowE('divLinkTypeEMail'	, (linkType == 'email') ) ;
	if ( !FCKConfig.LinkDlgHideTarget )
		window.parent.SetTabVisibility( 'Target'	, (linkType == 'url') ) ;
	if ( FCKConfig.LinkUpload )
		window.parent.SetTabVisibility( 'Upload'	, (linkType == 'url') ) ;
	if ( !FCKConfig.LinkDlgHideAdvanced )
		window.parent.SetTabVisibility( 'Advanced'	, (linkType != 'anchor' || bHasAnchors) ) ;
	if ( linkType == 'email' )
		window.parent.SetAutoSize( true ) ;
}
function SetTarget( targetType )
{
	GetE('tdTargetFrame').style.display	= ( targetType == 'popup' ? 'none' : '' ) ;
	GetE('tdPopupName').style.display	=
		GetE('tablePopupFeatures').style.display = ( targetType == 'popup' ? '' : 'none' ) ;
	switch ( targetType )
	{
		case "_blank" :
		case "_self" :
		case "_parent" :
		case "_top" :
			GetE('txtTargetFrame').value = targetType ;
			break ;
		case "" :
			GetE('txtTargetFrame').value = '' ;
			break ;
	}
	if ( targetType == 'popup' )
		window.parent.SetAutoSize( true ) ;
}
function OnUrlChange()
{
	var sUrl = GetE('txtUrl').value ;
	var sProtocol = oRegex.UrlOnChangeProtocol.exec( sUrl ) ;
	if ( sProtocol )
	{
		sUrl = sUrl.substr( sProtocol[0].length ) ;
		GetE('txtUrl').value = sUrl ;
		GetE('cmbLinkProtocol').value = sProtocol[0].toLowerCase() ;
	}
	else if ( oRegex.UrlOnChangeTestOther.test( sUrl ) )
	{
		GetE('cmbLinkProtocol').value = '' ;
	}
}
function OnTargetNameChange()
{
	var sFrame = GetE('txtTargetFrame').value ;
	if ( sFrame.length == 0 )
		GetE('cmbTarget').value = '' ;
	else if ( oRegex.ReserveTarget.test( sFrame ) )
		GetE('cmbTarget').value = sFrame.toLowerCase() ;
	else
		GetE('cmbTarget').value = 'frame' ;
}
function BuildOnClickPopup()
{
	var sWindowName = "'" + GetE('txtPopupName').value.replace(/\W/gi, "") + "'" ;
	var sFeatures = '' ;
	var aChkFeatures = document.getElementsByName( 'chkFeature' ) ;
	for ( var i = 0 ; i < aChkFeatures.length ; i++ )
	{
		if ( i > 0 ) sFeatures += ',' ;
		sFeatures += aChkFeatures[i].value + '=' + ( aChkFeatures[i].checked ? 'yes' : 'no' ) ;
	}
	if ( GetE('txtPopupWidth').value.length > 0 )	sFeatures += ',width=' + GetE('txtPopupWidth').value ;
	if ( GetE('txtPopupHeight').value.length > 0 )	sFeatures += ',height=' + GetE('txtPopupHeight').value ;
	if ( GetE('txtPopupLeft').value.length > 0 )	sFeatures += ',left=' + GetE('txtPopupLeft').value ;
	if ( GetE('txtPopupTop').value.length > 0 )		sFeatures += ',top=' + GetE('txtPopupTop').value ;
	if ( sFeatures != '' )
		sFeatures = sFeatures + ",status" ;
	return ( "window.open(this.href," + sWindowName + ",'" + sFeatures + "'); return false" ) ;
}
function FillPopupFields( windowName, features )
{
	if ( windowName )
		GetE('txtPopupName').value = windowName ;
	var oFeatures = new Object() ;
	var oFeaturesMatch ;
	while( ( oFeaturesMatch = oRegex.PopupFeatures.exec( features ) ) != null )
	{
		var sValue = oFeaturesMatch[2] ;
		if ( sValue == ( 'yes' || '1' ) )
			oFeatures[ oFeaturesMatch[1] ] = true ;
		else if ( ! isNaN( sValue ) && sValue != 0 )
			oFeatures[ oFeaturesMatch[1] ] = sValue ;
	}
	var aChkFeatures = document.getElementsByName('chkFeature') ;
	for ( var i = 0 ; i < aChkFeatures.length ; i++ )
	{
		if ( oFeatures[ aChkFeatures[i].value ] )
			aChkFeatures[i].checked = true ;
	}
	if ( oFeatures['width'] )	GetE('txtPopupWidth').value		= oFeatures['width'] ;
	if ( oFeatures['height'] )	GetE('txtPopupHeight').value	= oFeatures['height'] ;
	if ( oFeatures['left'] )	GetE('txtPopupLeft').value		= oFeatures['left'] ;
	if ( oFeatures['top'] )		GetE('txtPopupTop').value		= oFeatures['top'] ;
}
function Ok()
{
	var sUri, sInnerHtml ;
	switch ( GetE('cmbLinkType').value )
	{
		case 'url' :
			sUri = GetE('txtUrl').value ;
			if ( sUri.length == 0 )
			{
				alert( FCKLang.DlnLnkMsgNoUrl ) ;
				return false ;
			}
			sUri = GetE('cmbLinkProtocol').value + sUri ;
			break ;
		case 'email' :
			sUri = GetE('txtEMailAddress').value ;
			if ( sUri.length == 0 )
			{
				alert( FCKLang.DlnLnkMsgNoEMail ) ;
				return false ;
			}
			sUri = oParser.CreateEMailUri(
				sUri,
				GetE('txtEMailSubject').value,
				GetE('txtEMailBody').value ) ;
			break ;
		case 'anchor' :
			var sAnchor = GetE('cmbAnchorName').value ;
			if ( sAnchor.length == 0 ) sAnchor = GetE('cmbAnchorId').value ;
			if ( sAnchor.length == 0 )
			{
				alert( FCKLang.DlnLnkMsgNoAnchor ) ;
				return false ;
			}
			sUri = '#' + sAnchor ;
			break ;
	}
	var aLinks = oLink ? [ oLink ] : oEditor.FCK.CreateLink( sUri ) ;
	var aHasSelection = ( aLinks.length > 0 ) ;
	if ( !aHasSelection )
	{
		sInnerHtml = sUri;
		switch ( GetE('cmbLinkType').value )
		{
			case 'anchor':
				sInnerHtml = sInnerHtml.replace( /^#/, '' ) ;
				break ;
			case 'url':
				var oLinkPathRegEx = new RegExp("
				var asLinkPath = oLinkPathRegEx.exec( sUri ) ;
				if (asLinkPath != null)
					sInnerHtml = asLinkPath[1];  
				break ;
			case 'email':
				sInnerHtml = GetE('txtEMailAddress').value ;
				break ;
		}
		aLinks = [ oEditor.FCK.CreateElement( 'a' ) ] ;
	}
	oEditor.FCKUndo.SaveUndoStep() ;
	for ( var i = 0 ; i < aLinks.length ; i++ )
	{
		oLink = aLinks[i] ;
		if ( aHasSelection )
			sInnerHtml = oLink.innerHTML ;		
		oLink.href = sUri ;
		SetAttribute( oLink, '_fcksavedurl', sUri ) ;
		if( GetE('cmbTarget').value == 'popup' )
		{
			SetAttribute( oLink, 'onclick_fckprotectedatt', " onclick=\"" + BuildOnClickPopup() + "\"") ;
		}
		else
		{
			var onclick = oLink.getAttribute( 'onclick_fckprotectedatt' ) ;
			if( oRegex.OnClickPopup.test( onclick ) )
				SetAttribute( oLink, 'onclick_fckprotectedatt', '' ) ;
		}
		oLink.innerHTML = sInnerHtml ;		
		if( GetE('cmbTarget').value != 'popup' )
			SetAttribute( oLink, 'target', GetE('txtTargetFrame').value ) ;
		else
			SetAttribute( oLink, 'target', null ) ;
		if ( i == 0 )
			SetAttribute( oLink, 'id', GetE('txtAttId').value ) ;
		SetAttribute( oLink, 'name'		, GetE('txtAttName').value ) ;
		SetAttribute( oLink, 'dir'		, GetE('cmbAttLangDir').value ) ;
		SetAttribute( oLink, 'lang'		, GetE('txtAttLangCode').value ) ;
		SetAttribute( oLink, 'accesskey', GetE('txtAttAccessKey').value ) ;
		SetAttribute( oLink, 'tabindex'	, ( GetE('txtAttTabIndex').value > 0 ? GetE('txtAttTabIndex').value : null ) ) ;
		SetAttribute( oLink, 'title'	, GetE('txtAttTitle').value ) ;
		SetAttribute( oLink, 'type'		, GetE('txtAttContentType').value ) ;
		SetAttribute( oLink, 'charset'	, GetE('txtAttCharSet').value ) ;
		if ( oEditor.FCKBrowserInfo.IsIE )
		{
			var sClass = GetE('txtAttClasses').value ;
			if ( GetE('txtAttName').value.length != 0 )
				sClass += ' FCK__AnchorC' ;
			SetAttribute( oLink, 'className', sClass ) ;
			oLink.style.cssText = GetE('txtAttStyle').value ;
		}
		else
		{
			SetAttribute( oLink, 'class', GetE('txtAttClasses').value ) ;
			SetAttribute( oLink, 'style', GetE('txtAttStyle').value ) ;
		}
	}
	oEditor.FCKSelection.SelectNode( aLinks[0] );
	return true ;
}
function BrowseServer()
{
	OpenFileBrowser( FCKConfig.LinkBrowserURL, FCKConfig.LinkBrowserWindowWidth, FCKConfig.LinkBrowserWindowHeight ) ;
}
function SetUrl( url )
{
	document.getElementById('txtUrl').value = url ;
	OnUrlChange() ;
	window.parent.SetSelectedTab( 'Info' ) ;
}
function OnUploadCompleted( errorNumber, fileUrl, fileName, customMsg )
{
	switch ( errorNumber )
	{
		case 0 :	
			alert( 'Your file has been successfully uploaded' ) ;
			break ;
		case 1 :	
			alert( customMsg ) ;
			return ;
		case 101 :	
			alert( customMsg ) ;
			break ;
		case 201 :
			alert( 'A file with the same name is already available. The uploaded file has been renamed to "' + fileName + '"' ) ;
			break ;
		case 202 :
			alert( 'Invalid file type' ) ;
			return ;
		case 203 :
			alert( "Security error. You probably don't have enough permissions to upload. Please check your server." ) ;
			return ;
		default :
			alert( 'Error on file upload. Error number: ' + errorNumber ) ;
			return ;
	}
	SetUrl( fileUrl ) ;
	GetE('frmUpload').reset() ;
}
var oUploadAllowedExtRegex	= new RegExp( FCKConfig.LinkUploadAllowedExtensions, 'i' ) ;
var oUploadDeniedExtRegex	= new RegExp( FCKConfig.LinkUploadDeniedExtensions, 'i' ) ;
function CheckUpload()
{
	var sFile = GetE('txtUploadFile').value ;
	if ( sFile.length == 0 )
	{
		alert( 'Please select a file to upload' ) ;
		return false ;
	}
	if ( ( FCKConfig.LinkUploadAllowedExtensions.length > 0 && !oUploadAllowedExtRegex.test( sFile ) ) ||
		( FCKConfig.LinkUploadDeniedExtensions.length > 0 && oUploadDeniedExtRegex.test( sFile ) ) )
	{
		OnUploadCompleted( 202 ) ;
		return false ;
	}
	return true ;
}
function SetDefaultTarget()
{
	var target = FCKConfig.DefaultLinkTarget + '' ;
	if ( oLink || target.length == 0 )
		return ;
	switch ( target )
	{
		case '_blank' :
		case '_self' :
		case '_parent' :
		case '_top' :
			GetE('cmbTarget').value = target ;
			break ;
		default :
			GetE('cmbTarget').value = 'frame' ;
			break ;
	}
	GetE('txtTargetFrame').value = target ;
}
