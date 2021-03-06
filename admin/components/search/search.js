admin.controller('search', ['$rootScope', '$scope', '$state', '$http', 'userService', '$sce', '$q', '$stateParams', function ($rootScope, $scope, $state, $http, userService, $sce, $q, $stateParams) {

        var canceller = $q.defer();
        $scope.maxSize = 5;
        $scope.numPerPage = 25;
        var initSearch = ($stateParams.search) ? $stateParams.search : '';
        $scope.params = {page: 1, numPerPage: 25, sort: {"word": "ASC"}, search: initSearch};
        $scope.articles = [];
        $scope.groups = [];
        $scope.articlesMap = {};
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
        $scope.getData = function () {
            canceller.resolve();
            canceller = $q.defer();
            $http({method: 'POST', url: '../server/articlewords', data: $scope.params, timeout: canceller.promise}).
                    success(function (data, status, headers, config) {
                        $scope.articleWords = data;


                    });
        }
        $scope.exportSearch=function (){
            var csvData=[];
            $scope.articleWords.rows.forEach(function(row){
               csvData.push([
                   row.id,
                   row.word,
                   row.context,
                   $scope.articlesMap[row.articleid].title,
                   row.paragraphNumber,
                   row.paragraphWordPosition
               ]);
            })
            csvExport(csvData,"search.csv");
        }
        $scope.searchf = function () {
            $scope.getData();
           // $scope.params.numPerPage = $scope.numPerPage;
           // $scope.params.search = $scope.search;
        }
        $scope.trustHtml = function (str) {
            return $sce.trustAsHtml(str);
        }
        $scope.$watch('params', function () {
            $scope.getData();
        }, true)
        $scope.sort = function (columnName) {
            if ($scope.params.sort && columnName in $scope.params.sort) {
                var dir = $scope.params.sort[columnName];
                if (dir === 'ASC') {
                    var newDir = 'DESC';
                    $scope.params.sort[columnName] = newDir;
                } else {
                    delete $scope.params.sort[columnName];
                }

            } else {
                if (!('sort' in $scope.params)) {
                    $scope.params.sort = {};
                }

                $scope.params.sort[columnName] = 'ASC';
            }
        }
    }]);