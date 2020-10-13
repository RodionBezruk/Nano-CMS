var FCKAutoGrow_Min = window.frameElement.offsetHeight ;
function FCKAutoGrow_Check()
{
	var oInnerDoc = FCK.EditorDocument ;
	var iFrameHeight, iInnerHeight ;
	if ( FCKBrowserInfo.IsIE )
	{
		iFrameHeight = FCK.EditorWindow.frameElement.offsetHeight ;
		iInnerHeight = oInnerDoc.body.scrollHeight ;
	}
	else
	{
		iFrameHeight = FCK.EditorWindow.innerHeight ;
		iInnerHeight = oInnerDoc.body.offsetHeight ;
	}
	var iDiff = iInnerHeight - iFrameHeight ;
	if ( iDiff != 0 )
	{
		var iMainFrameSize = window.frameElement.offsetHeight ;
		if ( iDiff > 0 && iMainFrameSize < FCKConfig.AutoGrowMax )
		{
			iMainFrameSize += iDiff ;
			if ( iMainFrameSize > FCKConfig.AutoGrowMax )
				iMainFrameSize = FCKConfig.AutoGrowMax ;
		}
		else if ( iDiff < 0 && iMainFrameSize > FCKAutoGrow_Min )
		{
			iMainFrameSize += iDiff ;
			if ( iMainFrameSize < FCKAutoGrow_Min )
				iMainFrameSize = FCKAutoGrow_Min ;
		}
		else
			return ;
		window.frameElement.height = iMainFrameSize ;
	}
}
FCK.AttachToOnSelectionChange( FCKAutoGrow_Check ) ;
function FCKAutoGrow_SetListeners()
{
	if ( FCK.EditMode != FCK_EDITMODE_WYSIWYG )
		return ;
	FCK.EditorWindow.attachEvent( 'onscroll', FCKAutoGrow_Check ) ;
	FCK.EditorDocument.attachEvent( 'onkeyup', FCKAutoGrow_Check ) ;
}
if ( FCKBrowserInfo.IsIE )
{
	FCK.Events.AttachEvent( 'OnAfterSetHTML', FCKAutoGrow_SetListeners ) ;
}
function FCKAutoGrow_CheckEditorStatus( sender, status )
{
	if ( status == FCK_STATUS_COMPLETE )
		FCKAutoGrow_Check() ;
}
FCK.Events.AttachEvent( 'OnStatusChange', FCKAutoGrow_CheckEditorStatus ) ;
