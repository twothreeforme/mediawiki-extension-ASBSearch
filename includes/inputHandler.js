function copyToClipboard() {

    //navigator.clipboard.writeText("test value");

    //alert("test value");


    let url = document.location.href

    navigator.clipboard.writeText(url).then(function() {
        console.log('Copied!');
    }, function() {
        console.log('Copy error')
    });
  } 

  console.log('here');