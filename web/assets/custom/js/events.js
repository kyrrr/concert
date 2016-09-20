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

				//text = text.toLowerCase();
				//searchString = searchString.toLowerCase();

				if(text.indexOf(searchString) !=-1){
					//console.log("show: " + i);
					$( this ).parent('tr').show();
					$( this ).parent('tr').toggleClass( 'rowIsShown', true );

				}else{
					//console.log("hide: " + i);
					$( this ).parent('tr').hide();
					$( this ).parent('tr').toggleClass( 'rowIsShown', false );
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
						//console.log("Match: " + toMatch);
					}

				}else{
					//onsole.log(" " + i);
					
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
		searchString = $( '#search' ).val();
		classToSearch = '.pokeRow > td';
		timedOnPageSearch(searchString, classToSearch, 1);
		updateCounter(2);
	});

	$( '#search' ).on('keydown', function(){
		var toggle = $( '.kyrr-search-select' ).find(':selected').text().toLowerCase();
		//console.log(toggle);

		switch(toggle) {
    		case 'name':
        		console.log(toggle);
        		break;
    		case 'id':
        		console.log(toggle);
        		break;
        	case 'type':
        		console.log(toggle);
    		default:
        		console.log("default");
		}

		//switch case it
		searchString = $( '#search' ).val();
		suggestionClass = '.pokeType';
		suggestion(searchString, suggestionClass);
		
	});






});