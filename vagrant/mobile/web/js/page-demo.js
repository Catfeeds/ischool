(function() {
  $(function() {
    var path=$("#path").val();
    var editor;
    return editor = new Simditor({
      textarea: $('#txt-content'),
      placeholder: '',
      pasteImage: true,
	  toolbarFloat:false,
      toolbar: ['color','bold','image','record','uloadfile'],
      upload:{
         url:'/utils/uploadimg',
      }
    });
  });

}).call(this);
