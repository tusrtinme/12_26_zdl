$(function () {
  //layer全局配置
  layer.config({
    shadeClose: true
  });

 
 
  //全部删除
  $(".del-btn").on(ace.click_event,function(){
	var valarr = [];
	var checkitem = $(".check:checked")
    var len = checkitem.length;
    if(len>0){
    	for(let i = 0; i < len;i++){
    		valarr.push(checkitem.eq(i).val())
    	}
      layer.confirm('是否删除?',{icon: 3},function (index) {
        layer.close(index);
        window.location.href="delete_all/id/"+valarr;
      });
    }else{
      layer.msg("请先选择数据");
    }
  });

  //重置
  $(':reset').on(ace.click_event,function (e) {
    e.preventDefault();
    layer.confirm("是否清空本页面表单数据?",{icon: 3},function (index) {
      layer.close(index);
      $('form').get(0).reset();
    })
  });

  //表格全选
  $(".checkAll").on(ace.click_event,function(){
    $(".check:checkbox").prop("checked",$(this).prop("checked"))
  });

  //单选
  $(".check:checkbox").on(ace.click_event,function(){
    $(".checkAll").prop("checked",$(".check:checked").length == $(".check:checkbox").length);
  });

  //日期选择
  $('input[name="date-range-picker"]').daterangepicker({
    autoUpdateInput: false,
    locale: {
      applyLabel: '确定',
      cancelLabel: '清除'
    }
  });

  $('input[name="date-range-picker"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('input[name="date-range-picker"]').on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
  });

  //缩略图
  var $overflow = '';
  var colorbox_params = {
    rel: 'colorbox',
    reposition: true,
    scalePhotos: true,
    scrolling: false,
    previous: '<i class="ace-icon fa fa-arrow-left"></i>',
    next: '<i class="ace-icon fa fa-arrow-right"></i>',
    close: '&times;',
    current: '{current} of {total}',
    maxWidth: '100%',
    maxHeight: '100%',
    onOpen: function () {
      $overflow = document.body.style.overflow;
      document.body.style.overflow = 'hidden';
    },
    onClosed: function () {
      document.body.style.overflow = $overflow;
    },
    onComplete: function () {
      $.colorbox.resize();
    }
  };

  $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
  $("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange fa-spin'></i>");
  //let's add a custom loading icon


  $(document).one('ajaxloadstart.page', function (e) {
    $('#colorbox, #cboxOverlay').remove();
  });
});

/**
 * @param url: 控制器方法
 * @param id: 传入id
 * @param cb: 回调方法
 */
function ajaxQuery(url,id,cb) {
  $.ajax({
    url: url,
    type: 'get',
    data: {id:id},
    dataType: 'json',
    success: cb
  })
}



/**
 *
 * @param title: 提示标题
 * @param text: 提示内容
 */
function error(title,text) {
  $.gritter.add({
    // (string | mandatory) the heading of the notification
    title: title,
    // (string | mandatory) the text inside the notification
    text: text,
    class_name: 'gritter-error'
  });
}

function success(title,text) {
  $.gritter.add({
    // (string | mandatory) the heading of the notification
    title: title,
    // (string | mandatory) the text inside the notification
    text: text,
    class_name: 'gritter-success'
  });
}