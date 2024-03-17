function copyURLToClipboard() {
    url = document.location.href;

    navigator.clipboard.writeText(url).then(function() {
        console.log('copyURLToClipboard(): Copied!');
        mw.notify( 'Test', { autoHide: true,  type: 'warn' } ); 
    }, function() {
    	alert("Error copying to clipboard. Please report on our Discord.");
        console.log('copyURLToClipboard(): Copy error');
    });
  };

