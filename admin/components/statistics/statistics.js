admin.controller('statistics', ['$rootScope', '$scope', '$state', '$http', 'userService', function ($rootScope, $scope, $state, $http, userService) {

        $scope.params = {};
        $scope.getFilterData = function () {

            $http({method: 'GET', url: '../server/articlesForFilter'}).
                    success(function (data, status, headers, config) {
                        $scope.articles = data;
                        $scope.articles.forEach(function (article) {
                            $scope.articlesMap[article.id] = article;
                        })
                    });
            $http({method: 'GET', url: '../server/wordGroupsForFilter'}).
                    success(function (data, status, headers, config) {
                        $scope.groups = data;
                    });
        }
        $scope.getFilterData();
        $scope.getStatistics = function () {
            $http({method: 'POST', url: '../server/wordStatistics', data: $scope.params}).
                    success(function (data, status, headers, config) {
                        $scope.wordStatistics = data;
                    });
            $http({method: 'POST', url: '../server/wordOcuranceStatistics', data: $scope.params}).
                    success(function (data, status, headers, config) {
                        $scope.wordOccuranceStatistics = data;
                    });
            $http({method: 'POST', url: '../server/letterStatistics', data: $scope.params}).
                    success(function (data, status, headers, config) {
                        $scope.letterStatistics = data;
                    });
        }
      //  $scope.getStatistics();
        $scope.$watch('params', function () {
            $scope.getStatistics();
        }, true)


    }]);