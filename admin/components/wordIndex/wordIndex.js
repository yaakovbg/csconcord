admin.controller('wordIndex', ['$rootScope', '$scope', '$state', '$http', 'userService', '$sce','$q', function ($rootScope, $scope, $state, $http, userService, $sce,$q) {

        var canceller = $q.defer();
        $scope.maxSize = 5;
        $scope.numPerPage = 25;
        $scope.params = {page: 1, numPerPage: 25, order: {}, search: ''};
        $scope.getData = function () {
              canceller.resolve();
               canceller = $q.defer();
            $http({method: 'POST', url: '../server/distinctWords', data: $scope.params,timeout: canceller.promise}).
                    success(function (data, status, headers, config) {
                        $scope.articleWords = data;


                    });
        }
        $scope.searchf = function () {
            $scope.params.numPerPage = $scope.numPerPage;
            $scope.params.search = $scope.search;
        }
        $scope.trustHtml = function (str) {
            return $sce.trustAsHtml(str);
        }
        $scope.$watch('params', function () {
            $scope.getData();
        }, true)

    }]);