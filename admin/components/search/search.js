admin.controller('search', ['$rootScope', '$scope', '$state', '$http', 'userService','$sce', function ($rootScope, $scope, $state, $http, userService,$sce) {

      $scope.pagination=[];
            $scope.maxSize = 5;
            $scope.numPerPage=25;
            $scope.params={page:1,numPerPage:25,order:{},search:''};
            $scope.getData=function(){
                    $http({method: 'POST', url: '../server/articlewords', data:$scope.params}).
                            success(function(data, status, headers, config) {
                                    $scope.articleWords=data;
                                    $scope.pagination=[];
                                    console.log($scope.pagination);
                    });	
	}
        $scope.searchf=function(){
		$scope.params.numPerPage=$scope.numPerPage;
		$scope.params.search=$scope.search;	
	}
        $scope.trustHtml=function(str){
            return $sce.trustAsHtml(str);
        }
	$scope.$watch('params',function(){
		$scope.getData();
	},true)

} ]);