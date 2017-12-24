admin.controller('search', ['$rootScope', '$scope', '$state', '$http', 'userService', '$sce', '$q', '$stateParams', function ($rootScope, $scope, $state, $http, userService, $sce, $q, $stateParams) {

        var canceller = $q.defer();
        $scope.maxSize = 5;
        $scope.numPerPage = 25;
        var initSearch = ($stateParams.search) ? $stateParams.search : '';
        $scope.params = {page: 1, numPerPage: 25, order: {}, search: initSearch};
        $scope.articles=[];
        $scope.groups=[];
        $scope.getFilterData = function () {
            canceller.resolve();
            canceller = $q.defer();
            $http({method: 'GET', url: '../server/articlesForFilter'}).
                    success(function (data, status, headers, config) {
                        $scope.articles = data;
                    });
            $http({method: 'GET', url: '../server/wordGroupsForFilter'}).
                    success(function (data, status, headers, config) {
                        $scope.groups = data;
                    });
        }
        $scope.getFilterData();
        $scope.getData = function () {
            canceller.resolve();
            canceller = $q.defer();
            $http({method: 'POST', url: '../server/articlewords', data: $scope.params, timeout: canceller.promise}).
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