		function reloadImg(id) {
		   var obj = document.getElementById(id);
		   var src = obj.src;
		   var pos = src.indexOf('?');
		   if (pos >= 0) {
		      src = src.substr(0, pos);
		   }
		   var date = new Date();
		   obj.src = src + '?v=' + date.getTime();
		   return false;
		}