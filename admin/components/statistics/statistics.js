admin.controller('statistics', ['$rootScope', '$scope', '$state', '$http', 'userService', function ($rootScope, $scope, $state, $http, userService) {
      
     $http({method: 'GET', url: '../server/wordStatistics'}).
                    success(function (data, status, headers, config) {
                        $scope.wordStatistics = data;
                    });
      $http({method: 'GET', url: '../server/wordOcuranceStatistics'}).
                    success(function (data, status, headers, config) {
                        $scope.wordOccuranceStatistics = data;
                    });
        $http({method: 'GET', url: '../server/letterStatistics'}).
                    success(function (data, status, headers, config) {
                        $scope.letterStatistics = data;
                    });


} ]);