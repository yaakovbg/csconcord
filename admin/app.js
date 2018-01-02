var admin = angular.module('admin', ['ui.sortable', 'ui.router','ngFileUpload','emoji','ngSanitize','ui.bootstrap','checklist-model'])

admin.service('userService',['$http','$q', '$state', function($http,$q,$state){
	

}]);

admin.run(function ($rootScope, $timeout, $state, userService,$http) {
		$rootScope.loading=false;
		
})
admin.filter('htmlEscape', function() {
    return function(input) {
        if (!input) {
            return '';
        }
        return input.
            replace(/&/g, '&amp;').
            replace(/</g, '&lt;').
            replace(/>/g, '&gt;').
            replace(/'/g, '&#39;').
            replace(/"/g, '&quot;')
        ;
    };
});
admin.filter('textToHtml', ['$sce', 'htmlEscapeFilter', function($sce, htmlEscapeFilter) {
    return function(input) {
        if (!input) {
            return '';
        }
     //   input = htmlEscapeFilter(input);

        var output = '';
        $.each(input.split("\r\n"), function(key, paragraph) {
            output += '<br>' + paragraph ;
        });

        return $sce.trustAsHtml(output);
    };
}])
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
		    url: "/main?search",
		    views: {
		        "main": {
		            templateUrl: "components/search/search.html",
		            controller: "search"
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
        .state("article", {
            url: "/article/:aid?search",
            views: {
                "main": {
                    templateUrl: "components/article/article.html",
                    controller: "article"
                }
            }
        })
        .state("wordGroup", {
            url: "/wordGroup",
            views: {
                "main": {
                    templateUrl: "components/wordGroup/wordGroup.html",
                    controller: "wordGroup"
                }
            }
        })
        .state("wordRelation", {
            url: "/wordRelation",
            views: {
                "main": {
                    templateUrl: "components/wordRelation/wordRelation.html",
                    controller: "wordRelation"
                }
            }
        })
        .state("wordIndex", {
            url: "/wordIndex",
            views: {
                "main": {
                    templateUrl: "components/wordIndex/wordIndex.html",
                    controller: "wordIndex"
                }
            }
        })
        .state("statistics", {
            url: "/statistics",
            views: {
                "main": {
                    templateUrl: "components/statistics/statistics.html",
                    controller: "statistics"
                }
            }
        })
        .state("export", {
            url: "/export",
            views: {
                "main": {
                    templateUrl: "components/export/export.html",
                    controller: "export"
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
function csvExport(data,fileName){
	var csvContent = "data:text/csv;charset=utf-8,\ufeff";
	data.forEach(function(infoArray, index){
		var row=[];
		infoArray.forEach(function(col){
			row.push( col.replace(/["]/g, '""'));
		})
		dataString = '"'+row.join('","')+'"';
                dataString = dataString.replace(/(\r\n|\n|\r)/gm,"");
		csvContent += index < data.length ? dataString+ "\n" : dataString;
	}); 
	
	var encodedUri = encodeURI(csvContent);
	var link = document.createElement("a");
	link.setAttribute("href", encodedUri);
	link.setAttribute("download", fileName);

	link.click(); // This will download the data file named "my_data.csv".
}

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
