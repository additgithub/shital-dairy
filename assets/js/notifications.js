/* Webarch Admin Dashboard 
/* This JS is only for DEMO Purposes - Extract the code that you need
-----------------------------------------------------------------*/ 
function showErrorMessage(msg){
    Messenger.options = {
            extraClasses: 'messenger-fixed messenger-on-top',
            theme: 'flat'
        }
 Messenger().post({
	 message: msg,
	type: 'error',
    showCloseButton: true
	});
}	

function progressMessage(){
    Messenger.options = {
            extraClasses: 'messenger-fixed messenger-on-top',
            theme: 'flat'
        }
	var i = 0;
            Messenger().run({
              errorMessage: 'Error destroying alien planet',
              successMessage: 'Alien planet destroyed!',
              action: function(opts) {
                if (++i < 3) {
                  return opts.error({
                    status: 500,
                    readyState: 0,
                    responseText: 0
                  });
                } else {
                  return opts.success();
                }
              }
            });
}

function showSuccess(msg){
    Messenger.options = {
            extraClasses: 'messenger-fixed messenger-on-top',
            theme: 'flat',
        }
	Messenger().post({
	 message: msg,
    showCloseButton: true
	});
}
function showSuccessCustom(msg){
    Messenger.options = {
            extraClasses: 'messenger-fixed messenger-on-top',
            theme: 'flat',
            hideAfter:60
        }
	Messenger().post({
    
	 message: msg,
   hideAfter:600000,
    showCloseButton: true
	});
}

function showErrorMessageCustom(msg){
  Messenger.options = {
          extraClasses: 'messenger-fixed messenger-on-top',
          theme: 'flat'
      }
Messenger().post({
 message: msg,
type: 'error',
hideAfter:60,
  showCloseButton: true
});
}	