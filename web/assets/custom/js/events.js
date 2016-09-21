//src = web/assets/custom/js/events.js
$( document ).ready(function(){

	function timedOnPageSearch(searchString, classToSearch, k) {
		if(classToSearch.charAt(0)!="."){
			console.log("searchString: " + searchString + " classToSearch: " + classToSearch);
		}else{
			setTimeout(function(){
			//time out for k??		
			$( classToSearch ).each(function(i, obj){

				var text =  $( this ).html().toString();
				//console.log(text);
				text = text.toLowerCase();
				searchString = searchString.toLowerCase();

				if(text.indexOf(searchString) !=-1){
					console.log("show: " + i);
					$( this ).parent('td').parent('tr').show();
					$( this ).parent('td').parent('tr').toggleClass( 'rowIsShown', true );

				}else{
					console.log("hide: " + i);
					$( this ).parent('td').parent('tr').hide();
					$( this ).parent('td').parent('tr').toggleClass( 'rowIsShown', false );
				}
			});	
		}, k);
		}
	}

	function suggestion(searchString, suggestionClass) {
		$( suggestionClass ).each(function(i, obj){
			 var toMatch = $( this ).html().toString();
			 if(toMatch.indexOf(searchString) !=-1){
					if(!toMatch.indexOf("toMatch") !=-1){
						console.log("Match: " + toMatch);
					}
				}
		});
	}

	function updateCounter(i) {
		setTimeout(function(){  
		    numberOfItemsShown = $( '.rowIsShown' ).length;
		    $( '#rowCount' ).html(numberOfItemsShown);  
	    }, i);
	}

	$( '#search' ).on('keyup', function(){
		var toggle = $( '.kyrr-search-select' ).find(':selected').text();
		searchString = $( '#search' ).val();
		classToSearch = '.poke' + toggle;
		timedOnPageSearch(searchString, classToSearch, 1);
		updateCounter(2);
	});

	$( '#search' ).on('keydown', function(){
		//var toggle = $( '.kyrr-search-select' ).find(':selected').text();
		//searchString = $( '#search' ).val();
		//suggestionClass = '.poke' + toggle;
		//console.log(suggestionClass);
		//suggestion(searchString, suggestionClass);
	});






});