(function() {
  $(function() {
    var editor;
    return editor = new Simditor({
      textarea: $('#txt-content'),
      placeholder: '',
      pasteImage: true,
	  toolbarFloat:false,
      toolbar: ['color','bold','image'],
      upload:{
        url: '/utils/uploadimg'
      }
    });
  });

}).call(this);
