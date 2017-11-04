admin.controller('fileUploader', ['$rootScope', '$scope', '$state', '$http', 'userService', function ($rootScope, $scope, $state, $http, userService) {

    $scope.newArticle={};
    $scope.files=[];
	
    $scope.addArticle=function(article){
        console.log(article); 
        $http({ method: 'POST', url: '../server/article', data:  article }).
		success(function (data, status, headers, config) {
			
                });
    }
} ]);