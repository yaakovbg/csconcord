admin.controller('fileUploader', ['$rootScope', '$scope', '$state', '$http', 'userService', function ($rootScope, $scope, $state, $http, userService) {

    $scope.newArticle={};
    $scope.articles=[];
       
    $scope.reset=function(){
        $scope.newArticle={};
        $scope.getArticles();
    }
    $scope.getArticles=function(){
       
        $http({ method: 'GET', url: '../server/articles' }).
		success(function (data, status, headers, config) {
			$scope.articles=data;
                });
    }
    $scope.getArticles();
    $scope.editArticle=function(article){
        console.log(article); 
        $http({ method: 'POST', url: '../server/article', data:  article }).
		success(function (data, status, headers, config) {
			$scope.reset();
                });
    }
    $scope.analyzeArticle=function(article){
        console.log(article); 
        $http({ method: 'POST', url: '../server/analyzeArticle', data:  article }).
		success(function (data, status, headers, config) {
			$scope.reset();
                });
    }
    $scope.addArticle=function(article){
        console.log(article); 
        $http({ method: 'POST', url: '../server/article', data:  article }).
		success(function (data, status, headers, config) {
			$scope.reset();
                });
    }
     $scope.removeArticle=function(article){
        console.log(article); 
        $http({ method: 'DELETE', url: '../server/article', data:  article }).
		success(function (data, status, headers, config) {
			$scope.reset();
                });
    }
} ]);