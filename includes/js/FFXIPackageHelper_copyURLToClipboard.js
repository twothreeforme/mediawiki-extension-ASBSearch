
/* Copy to Clipboard helper for LSBSearch */

function copyURLToClipboard() {
    
	url = document.location.href;

    navigator.clipboard.writeText(url).then(function() {
        console.log('copyURLToClipboard(): Copied!');
        mw.notify( 'Copied to Clipboard !', { autoHide: true,  type: 'warn' } ); 
    }, function() {
    	mw.notify( 'Error copying to clipboard. Please report on our Discord.', { autoHide: true,  type: 'error' } ); 
        console.log('copyURLToClipboard(): Copy error');
    });
  };
