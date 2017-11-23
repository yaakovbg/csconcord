admin.controller('wordGroup', ['$rootScope', '$scope', '$state', '$http', 'userService', function ($rootScope, $scope, $state, $http, userService) {

    $scope.newWordGroup={};
    $scope.wordGroups=[];
       
    $scope.reset=function(){
        $scope.newWordGroup={};
        $scope.getWordGroups();
    }
    $scope.getWordGroups=function(){
       
        $http({ method: 'GET', url: '../server/wordGroups' }).
		success(function (data, status, headers, config) {
			$scope.wordGroups=data;
                });
    }
    $scope.getWordGroups();
    $scope.editWordGroup=function(wordGroup){
        
        $http({ method: 'POST', url: '../server/wordGroup', data:  wordGroup }).
		success(function (data, status, headers, config) {
			$scope.reset();
                });
    }
    $scope.addWordGroup=function(wordGroup){
        
        $http({ method: 'POST', url: '../server/wordGroup', data:  wordGroup }).
		success(function (data, status, headers, config) {
			$scope.reset();
                });
    }
     $scope.removeWordGroup=function(wordGroup){
       
        $http({ method: 'DELETE', url: '../server/wordGroup', data:  wordGroup }).
		success(function (data, status, headers, config) {
			$scope.reset();
                });
    }
} ]);