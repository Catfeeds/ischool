;(function(RCS){
	var getTemplates = function(callback){
		var list = {
	        button: '/im/templates/button.html',
	        chat: '/im/templates/chat.html',
	        closebefore: '/im/templates/closebefore.html',
	        conversation: '/im/templates/conversation.html',
	        endconversation: '/im/templates/endconversation.html',
	        evaluate: '/im/templates/evaluate.html',
	        imageView: '/im/templates/imageView.html',
	        leaveword: '/im/templates/leaveword.html',
	        main: '/im/templates/main.html',
	        imMain: '/im/templates/imMain.html',
	        message: '/im/templates/message.html',
	        imMessage: '/im/templates/imMessage.html',
	        messageTemplate: '/im/templates/messageTemplate.html',
	        imMessageTemplate: '/im/templates/imMessageTemplate.html',
	        userInfo: '/im/templates/userInfo.html',
	    };
	    var templates = {};
	    for (var key in list) {
	    	var url = list[key];
	    	var html = RCS.templateCache[url];
	    	if (html) {
	    		templates[key] = html;
	    	} else {
		    	var xhr = new XMLHttpRequest();
		    	xhr.open('get', url, false);
		    	xhr.onreadystatechange = function(){
		    		if (xhr.readyState == 4 && xhr.status == 200) {
		    			templates[key] = xhr.responseText;
		    		}
		    	}
		    	xhr.send(null);
	    	}

	    }
	    return templates;
	}
	RCS.getTemplates = getTemplates;
})(RCS);