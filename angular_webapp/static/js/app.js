var db_caching = '';
var tpl_caching = '';
// create the module and name it lareApp
var lareApp = angular.module('lareApp', ['ngRoute', 'ngSanitize']);

lareApp.config(function($routeProvider) {
    $routeProvider
        .when('/', {
            templateUrl : 'templates/index.html',
            controller  : 'mainController'
        })
        .when('/imprint/', {
            templateUrl : 'templates/imprint.html',
            controller  : 'imprintController'
        })
        .when('/tags/:char?/:page?/', {
            templateUrl : 'templates/tags.html',
            controller  : 'tagsController',
            reloadOnSearch: false
        });
});

lareApp.controller('mainController', function($scope, $location, $templateCache) {
    if ($location.search().db_caching !== undefined) {
        db_caching = '&db_caching=true';
    }
    if ($location.search().tpl_caching !== undefined) {
        tpl_caching = '&tpl_caching=true';
    }
    if (tpl_caching == '') {
        $templateCache.removeAll();
    }
});

lareApp.controller('imprintController', function($scope, $location, $templateCache) {
    if ($location.search().db_caching !== undefined) {
        db_caching = '&db_caching=true';
    }
    if ($location.search().tpl_caching !== undefined) {
        tpl_caching = '&tpl_caching=true';
    }
    if (tpl_caching == '') {
        $templateCache.removeAll();
    }
});

lareApp.controller('tagsController', function($scope, $routeParams, $http, $location, $templateCache, $route) {

    var lastRoute = $route.current;
    $scope.$on('$locationChangeSuccess', function(event) {
        if (event.currentScope.current_char && event.currentScope.current_page && $route.current.pathParams.char && $route.current.pathParams.page) {
            var char = $route.current.pathParams.char;
            var page = parseInt($route.current.pathParams.page);
            $route.current = lastRoute;
            event.currentScope.getTags(char, page);
        }
    });
    var ctrl = this;
    if ($location.search().db_caching == true) {
        db_caching = '&db_caching=true';
    }
    if ($location.search().tpl_caching == true) {
        tpl_caching = '&tpl_caching=true';
    }
    $scope.getTags = function(char, page, initial) {
        if (tpl_caching == '') {
            $templateCache.removeAll();
        }
        if (char == $scope.current_char && page == $scope.current_page) {
            return;
        }
        var all_chars = '';
        if (initial) {
            all_chars = '&all_chars';
        }
        $scope.current_char = char;
        $scope.current_page = page;
        $http.get('/api/tags.php?current_char='+char+'&page='+page+all_chars+tpl_caching+db_caching).
            success(function(data, status, headers, config) {
                if (all_chars != '') {
                    $scope.chars = data.chars;
                }
                $scope.tags = data.tags;
                var page_count = 1;
                for (var i = 0; i < data.chars.length; i++) {
                    if (data.chars[i].char == char) {
                        page_count = Math.ceil(data.chars[i].char_count/30);
                    }
                }
                ctrl.paginate(char, page, page_count);
                $route.updateParams({
                    char: char,
                    page: page
                });
            }).
            error(function(data, status, headers, config) {
                alert('API not working properly.');
            });
    };

    ctrl.paginate = function(char, page, page_count) {
        $scope.tag_pages = [{'page': page==1 ? page : page-1, 'label': '<span class="fa fa-angle-left"></span>&nbsp;prev', disabled: page==1}];
        if (page >= 4) {
            $scope.tag_pages.push({'page': 1, 'label': '1'});
            if (page >= 5) {
                $scope.tag_pages.push({'page': 1, 'label': '...', disabled: true});
            }
        }
        for (var i = page-2; i<=page+2; i++) {
            if (i > 0 && i <= page_count) {
                $scope.tag_pages.push({'page': i, 'label': i});
            }
        }
        if (page <= page_count - 3) {
            if (page <= page_count - 4) {
                $scope.tag_pages.push({'page': 1, 'label': '...', disabled: true});
            }
            $scope.tag_pages.push({'page': page_count, 'label': page_count});
        }
        $scope.tag_pages.push({'page': page==page_count ? page : page+1, 'label': 'next&nbsp;<span class="fa fa-angle-right"></span>', disabled: page==page_count});
    };

    var current_page = parseInt($routeParams.page);
    if (!$routeParams.page) {
        current_page = 1;
    }
    var current_char = $routeParams.char;
    if (!$routeParams.char) {
        current_char = 'all';
    }
    $scope.getTags(current_char, current_page, true);
});