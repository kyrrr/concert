//src = web/assets/custom/js/events.js
$( document ).ready(function(){

	function timedOnPageSearch(searchString, classToSearch, k) {
		if(classToSearch.charAt(0)!="."){
			console.log("searchString: " + searchString + " classToSearch: " + classToSearch);
		}else{
			//searchString can be empty!!
			setTimeout(function(){
			//time out a bit so it doesnt fire immidiately			
			$( classToSearch ).each(function(i, obj){
				
				var text =  $( this ).html();

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

	function updateCounter(i) {
		setTimeout(function(){  
		    numberOfItemsShown = $( '.rowIsShown' ).length;
		    $( '#rowCount' ).html(numberOfItemsShown);  
	    }, i);
	}

	$( '#search' ).on('keyup', function(){
		searchString = $( '#search' ).val();
		classToSearch = '.pokeRow > td';
		timedOnPageSearch(searchString, classToSearch, 290);
		updateCounter(300);
	});
});