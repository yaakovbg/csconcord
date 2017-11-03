admin.controller('exportuser', ['$rootScope', '$scope', '$state', '$http','userService', function ($rootScope, $scope, $state, $http,userService) {
	$scope.timing={};
	$scope.timing.fromdate='';
	$scope.timing.todate='';
	
	$scope.exportAppUser=function(){
		$http({method: 'POST', url: '../server/data.php?type=getUserData', data:{email:$scope.email}}).
			success(setcsvFrmData);		
	}
	function setcsvFrmData(data, status, headers, config) {
				var csvData=[];
				csvData[0]=["מס' משחק","מס' מסתמש","מס' חשבונית","שם",'דוא"ל',"סיסמה","ניקוד מקסימלי","תאריך","מידע"," זמני משחק","זמני תוצאות","סוג שגיאה"];
				data.forEach(function(row){
					var error_type='';
					switch(row.problem_flag){
						case "0":
							error_type='';
						break;
						case "1":
							error_type='הכנסת תשובות שגוי';
						break;
						case "2":
							error_type='זמן גדול מהזמן הניתן';
						break;
						case "3":
							error_type='לא הונס מידע';
						break;
						case "4":
							error_type='זמנים לא הגיוניים';
						break;
						default:error_type='';
						break;							
					}
					csvData.push([
						row._id,
						row.auid,
						row.receit?row.receit:"0",
						//row.numberOfGames?row.numberOfGames:"0",
						row.name,
						row.email,
						row.pass,
						row.points?row.points:"",
						row.time?new Date(parseInt(row.time)).toString():"",
						row.data,					
						JSON.stringify(row.timings),
						JSON.stringify(row.timeanswerd),
						error_type
					]);
				});
				csvExport(csvData,$scope.email+".csv")
			}	
} ]);

function csvExport(data,fileName){
	var csvContent = "data:text/csv;charset=utf-8,\ufeff";
	console.log(data);
	data.forEach(function(infoArray, index){
		var row=[];
		infoArray.forEach(function(col){
			row.push( col.replace(/["]/g, '""'));
		})
		dataString = '"'+row.join('","')+'"';
		csvContent += index < data.length ? dataString+ "\n" : dataString;
	}); 
	
	var encodedUri = encodeURI(csvContent);
	var link = document.createElement("a");
	link.setAttribute("href", encodedUri);
	link.setAttribute("download", fileName);

	link.click(); // This will download the data file named "my_data.csv".
}