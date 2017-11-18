var admin = angular.module('admin', ['ui.sortable', 'ui.router','ngFileUpload','emoji','ngSanitize','ui.bootstrap','checklist-model'])

admin.service('userService',['$http','$q', '$state', function($http,$q,$state){
	

}]);

admin.run(function ($rootScope, $timeout, $state, userService,$http) {
		$rootScope.loading=false;
		
})

/**** UI Router ****/
admin.config(function ($stateProvider, $urlRouterProvider,$httpProvider) {
    $urlRouterProvider.otherwise("/main");

    $stateProvider
		.state("login", {
		    url: "/login",
		    views: {
		        "main": {
		            templateUrl: "components/login/login.html",
		            controller: "login"
		        }
		    }
		})
		.state("main", {
		    url: "/main",
		    views: {
		        "main": {
		            templateUrl: "components/main/main.html",
		            controller: "main"
		        }
		    }
		})
		.state("search", {
		    url: "/search",
		    views: {
		        "main": {
		            templateUrl: "components/search/search.html",
		            controller: "search"
		        }
		    }
		})
        .state("fileUploader", {
            url: "/fileUploader",
            views: {
                "main": {
                    templateUrl: "components/fileUploader/fileUploader.html",
                    controller: "fileUploader"
                }
            }
        })
	
	var counter=0;
	$httpProvider.interceptors.push(function($document,$rootScope) {
		return {
		 'request': function(config) {
			counter++;
			$rootScope.loading=true;
			 // here we can for example add some class or show somethin
			 $document.find("body").css("cursor","wait");

			 return config;
		  },

		  'response': function(response) {
			  
			counter--;			
			// here ajax ends
			 //here we should remove classes added on request start
			 if(counter==0){
				$rootScope.loading=false;
				 $document.find("body").css("cursor","auto");
			}
			 return response;
		  },
		  'responseError': function(response) {
			counter--;			
			// here ajax ends
			 //here we should remove classes added on request start
			 if(counter==0){
				$rootScope.loading=false;
				 $document.find("body").css("cursor","auto");
			}
			 return response;
		  }
		};
	  });

 
});


function shuffle(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;

    // While there remain elements to shuffle...
    while (0 !== currentIndex) {

        // Pick a remaining element...
        randomIndex = Math.floor(Math.random() * currentIndex);
        currentIndex -= 1;

        // And swap it with the current element.
        temporaryValue = array[currentIndex];
        array[currentIndex] = array[randomIndex];
        array[randomIndex] = temporaryValue;
    }

    return array;
}
