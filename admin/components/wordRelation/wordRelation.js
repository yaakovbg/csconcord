admin.controller('wordRelation', ['$rootScope', '$scope', '$state', '$http', 'userService', function ($rootScope, $scope, $state, $http, userService) {

    $scope.newWordRelation={};
    $scope.wordRelations=[];
       
    $scope.reset=function(){
        $scope.newWordRelation={};
        $scope.getWordRelations();
    }
    $scope.getWordRelations=function(){
       
        $http({ method: 'GET', url: '../server/wordRelations' }).
		success(function (data, status, headers, config) {
			$scope.wordRelations=data;
                });
    }
    $scope.getWordRelations();
    $scope.editWordRelation=function(wordRelation){
        
        $http({ method: 'POST', url: '../server/wordRelation', data:  wordRelation }).
		success(function (data, status, headers, config) {
			$scope.reset();
                });
    }
    $scope.addWordRelation=function(wordRelation){
        
        $http({ method: 'POST', url: '../server/wordRelation', data:  wordRelation }).
		success(function (data, status, headers, config) {
			$scope.reset();
                });
    }
     $scope.removeWordRelation=function(wordRelation){
       
        $http({ method: 'DELETE', url: '../server/wordRelation', data:  wordRelation }).
		success(function (data, status, headers, config) {
			$scope.reset();
                });
    }
} ]);