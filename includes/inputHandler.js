function buttonClicked(){
    var descr=document.getElementById('a123').value;
    $.ajax({
        type:"post",
        data: 
        {  
         'descr' :descr
        },
        cache:false,
        success: function (html) 
        {
            console.log("sent:", descr);
        }
    });
    return false;
}

function zonenametextChanged(){
    var text = document.getElementById('zonenametext').value;
    console.log(text);

    $.ajax({
        type:"post",
        data: {  'zonename' :text },
        cache:false,
        success: function (data) 
        {
            console.log("sent:", text);
        }
    });

    return false;
}
    

function SubmitFormData() {
    var name = $("#name").val();
    var email = $("#email").val();
    var phone = $("#phone").val();
    var gender = $("input[type=radio]:checked").val();
    $.post("http://localhost/index.php/Special:ASBSearch", { name: name, email: email, phone: phone, gender: gender },
        function(data) {
        console.log(data);
        $('#myForm')[0].reset();
        });
}


$( document ).ready( function() {
	$( '#a123' ).on( 'click', function() {
		buttonClicked();
	} );
    $( '#zonenametext' ).on( 'input', function() {
		zonenametextChanged();
	} );
    $( '#submitFormData' ).on( 'click', function() {
		SubmitFormData();
	} );
} );

console.log('loaded');